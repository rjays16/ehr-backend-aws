<?php
/**
 * Created by PhpStorm.
 * User: debzl
 * Date: 8/26/2019
 * Time: 9:05 PM
 */

namespace App\Services\Patient;


use App\Exceptions\EhrException\EhrException;
use App\Exceptions\His\HisActiveResource;
use App\Models\Encounter;
use App\Models\FamilyHistory;
use App\Models\ImmunizationRecord;
use App\Models\MenstrualHistory;
use App\Models\PatientCatalog;
use App\Models\PregnantHistory;
use App\Models\PresentIllness;
use App\Models\PastMedicalHistory;
use App\Models\SocialHistory;
use App\Models\SurgicalHistory;
use App\Services\Doctor\Permission\PermissionService;
use App\Services\Doctor\Soap\SoapService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PatientService
{
    /**
     * @var Encounter
     */
    private $_encounter;


    function __construct($encounter)
    {
        $this->_encounter = Encounter::query()->find($encounter);
        if(!$this->_encounter)
            throw new EhrException('Encounter does not exist.', 404);
    }

    /**
     * @return Collection
    */
    public function getPatientInfo():Collection{

        $his = HisActiveResource::instance();
        $patient = $this->_encounter::query()->where('encounter_no',$this->_encounter->encounter_no)
            ->with([
            'spin0',
            'currentDeptEncounter.area',
        ])->first();
            
        $patient->{'is_favorite'} = !is_null($patient->thisDoctorFavorite(auth()->user()->personnel_id)->first());
        $patient->{'hospital_days'} = $patient->spin0->getHospitalDate($this->_encounter);

        $patientInfo = $his->getPersonBasicInformation($this->_encounter->spin);
        if($patientInfo['status']){
            $patientInfo['data']['person_data']['age'] = $patient->spin0->getAge();;
            $patient->spin0->{'person_his'} = $patientInfo['data']['person_data'];
        }
        else
            $patient->spin0->{'person_his'} = [];
        
        $patient = $patient->toArray();

        $permServ = new PermissionService($this->_encounter);



        unset($patient['spin0']['person']);
        $data = collect($patient);
        $data=$data->map(function($value, $key){
            if($key == 'encounter_date')
                $value = strtotime($value);
            if($key == 'spin0' && isset($value['person_his']['dateOfBirth']))
                $value['person_his']['dateOfBirth'] = strtotime($value['person_his']['dateOfBirth']);
            
            return $value;
        });
        $data['isInMyDept'] = $permServ->isInMyDept();

        return $data;

//        $his = HisActiveResource::instance();
//        $patientInfo = $his->getPersonBasicInformation($this->_encounter->spin0->spin);
//        $response = $his->getResponseData();
//        return $patientInfo;
    }



    

    public function nursePatientInfo()
    {
        
        $physicians = $this->_encounter->getPhysicians();

        $soap = new SoapService($this->_encounter);
        $subjective = $soap->getCheifComplaintSelected();
        $subjective_others = $subjective['others']?($subjective['others']['value']?:''): '';



        return $this->getPatientInfo()
        ->merge([
            'attending_team' => $physicians
        ])->merge([
            'medical_info' => [
                'chiefComplaint' => "{$subjective['names']} - {$subjective_others}",
                'impression' => $soap->getClinicalImpression($this->_encounter->encounter_no)['clinical_imp'],
            ]
        ]);
    }

}