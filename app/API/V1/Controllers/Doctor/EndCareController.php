<?php


namespace App\API\V1\Controllers\Doctor;


use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use App\Http\Controllers\Controller;
use App\Services\Doctor\EndCare\EndCareService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EndCareController extends Controller
{
    public function patientEndCare(Request $request){
        try {
            $endCareService = EndCareService::init($request->input('id'));
            $endCare = $endCareService->patientEndCare();
            return $this->jsonResponsePure($endCare);
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function actionEndCare(Request $request){
        $data = [
            "treatment"  =>  $request->post("treatment"),
            "reason"  =>  $request->post("reason"),
            "doc_advice"  =>  $request->post("doc_advice"),
        ];
        try {
            DB::beginTransaction();
            $endCareService = EndCareService::init($request->input('id'));
            
            $endCare = $endCareService->actionEndCare($data);
            DB::commit();
            return $this->jsonSuccess($endCare['message'], $endCare);
        }catch (EhrException $e){
            DB::rollBack();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            DB::rollBack();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function getTreatmentOptions(Request $request){

        try {
            $endCareService = new EndCareService();
            $getTreatment = $endCareService->getTreatment();
            return $this->jsonResponsePure($getTreatment);
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

}