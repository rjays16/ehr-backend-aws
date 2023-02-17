<?php

namespace App\API\V1\Controllers\Doctor;

use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Doctor\ExaminationService;
use Exception;
use Illuminate\Support\Facades\DB;

class PhysicalExaminationController extends Controller
{


    public function getPatientData(Request $request)
    {
        try{
            $service = ExaminationService::init($request->input('id'));
            return $this->jsonResponsePure($service->getMPhysicalExamData());
        }catch(EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch(Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function savePatientData(Request $request)
    {
        try{
            DB::beginTransaction();

            $service = ExaminationService::init($request->input('id'));
            $resp = $service->savePhysicalExaminationDetailedList($request->post('data'));
            DB::commit();
            return $this->jsonSuccess($resp['msg'], $resp);
        }catch(EhrException $e){
            DB::rollback();
            if(isset($service))
                $service->ressetTracerUpdate();
                return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch(Exception $e){
            DB::rollback();
            if(isset($service))
                $service->ressetTracerUpdate();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }
}
