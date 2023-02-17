<?php

namespace App\API\V1\Controllers\Doctor;

use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Doctor\PertinentSignAndSymptomsService;
use Exception;
use Illuminate\Support\Facades\DB;

class PertinentSignsController extends Controller
{

    public function getPertinentSignsAndSympData(Request $request)
    {
        try{

            $service = PertinentSignAndSymptomsService::init($request->get('id'));

            return $this->jsonResponsePure($service->getMSelectedData());
        }catch(EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch(Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
        
    }

    public function getPertinentSignsAndSympOptions(Request $request)
    {
        try{
            return $this->jsonResponsePure(PertinentSignAndSymptomsService::getOptions());
        }catch(EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch(Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
        
    }


    public function pertinentSignsAndSympDefaultOptions(Request $request)
    {
        try{

            $pertServiceProv = new PertinentSignAndSymptomsService();

            $cataloge = $pertServiceProv->getMCatalog();

            return $this->jsonResponsePure($cataloge);
        }catch(Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
    }


    public function pertinentSignsAndSympDefaultOthersOptions(Request $request)
    {
        try{

            $pertServiceProv = new PertinentSignAndSymptomsService();

            $cataloge = $pertServiceProv->getSearchedOthersCatalog($request->get('q'));

            return $this->jsonResponsePure($cataloge);
        }catch(Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
    }

    public function pertinentSignsAndSympDefaultPainsOptions(Request $request)
    {
        try{

            $pertServiceProv = new PertinentSignAndSymptomsService();

            $cataloge = $pertServiceProv->getSearchedPainsCatalog($request->get('q'));

            return $this->jsonResponsePure($cataloge);
        }catch(Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
    }


    public function savepertinentSignsAndSymp(Request $request)
    {
        $service = null;
        try{

            $service = PertinentSignAndSymptomsService::init($request->input('id'));
            $result = $service->savePertinents([
                'psa_names' => $request->post('psa_names'),
                'pains' => $request->post('pains'),
                'others' => $request->post('others')
            ]);

            return $this->jsonSuccess($result['msg'], $result);
        }catch(EhrException $e){
            if($service)
                $service->ressetTracerUpdate();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch(Exception $e){
            if($service)
                $service->ressetTracerUpdate();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
        
    }
}
