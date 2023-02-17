<?php


namespace App\API\V1\Controllers\Doctor;

use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use App\Services\Doctor\DoctorService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Doctor\CF4Service;
use Exception;

class DoctorController extends Controller
{
    public function searchAllDoctorPatients(Request $request){
        $user = auth()->user();
        $data = [
            'personnel_id'   => $user->personnel_id,
            'patient_type'   => $request->input('patient_type'),
            'person_search'   => $request->input('person_search')
        ];
        

        try {
            $doctor_service = new DoctorService($user->personnel);
            $patient_lists = $doctor_service->getPatientLists($data);
            return $this->jsonResponsePure($patient_lists);
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }


    }


    public function allDoctorTaggedPatients(Request $request){
        

        try {
            $doctor_service = new DoctorService($request->user()->personnel);
            return $this->jsonResponsePure($doctor_service->getTaggedPatients());
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }

    }


    public function tagPatient(Request $request){
        

        try {
            $doctor_service = new DoctorService($request->user()->personnel);
            return $this->jsonSuccess($doctor_service->favoritePatient($request->post('encounter_no')));
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(),$e->getCode(), $e->getRespDataJson());
        } catch (Exception $e) {
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }

    }

    public function checkIncompleData(Request $request){
        $service = CF4Service::init($request->input('encounter_no'));
        $service->checkMandatoryFields();
    }
}