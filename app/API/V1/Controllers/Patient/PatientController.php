<?php

namespace App\API\V1\Controllers\Patient;

use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use App\Services\Patient\PatientService;
use App\Services\Person\PersonService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class PatientController extends Controller
{


    public function patientInfo(Request $request){
        try{
            $pService = new PatientService($request->input('id','NO Encounter'));

            return $this->jsonResponsePure($pService->getPatientInfo());

        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
    }

    public function patientNurseInfo(Request $request){
        try{
            $pService = new PatientService($request->input('id','NO Encounter'));
            return $this->jsonResponsePure($pService->nursePatientInfo());
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
    }


    public function patientEncounterHistory(Request $request){

        try {
            $person_service = new PersonService($request->input('pid'));
            $encounters = $person_service->getPersonEncounter();
            return $this->jsonResponsePure($encounters);
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
    }
}
