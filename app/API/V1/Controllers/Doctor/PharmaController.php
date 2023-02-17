<?php


namespace App\API\V1\Controllers\Doctor;
use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use App\Services\Doctor\DrugsAndMedicine\PharmaService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PharmaController extends Controller
{
    public function patientMedication(Request $request)
    {
        try {
            $pharmaService = PharmaService::init($request->post('encounter_no'));
            $meds = $pharmaService->generateMeds();
            return $this->jsonResponsePure($meds);
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function actionMedication(Request $request){
        $data = [
            "refno" => $request->post('refno'),
            "item_code" => $request->post('itemID'),
            "frequency" => $request->post('frequency'),
            "route" => $request->post('route'),
            "user_id" => Auth::user()->personnel_id
        ];

        DB::beginTransaction();
        try {
            $pharmaService = PharmaService::init($request->post('encounter_no'));
            $meds = $pharmaService->actionMedication($data);
            DB::commit();
            $result = collect([])->put("data", $meds);
            return $this->jsonSuccess($meds['message'], $result);
        }catch (EhrException $e){
            DB::rollback();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            DB::rollback();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function getMedication(Request $request){

        $data = [
            "refno" => $request->input('refno'),
            "item_id" => $request->input('item_id')
        ];

        try {
            $pharmaService = PharmaService::init($request->input('encounter_no'));
            $meds = $pharmaService->getMedication($data);
            return $this->jsonResponsePure($meds);
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }
}
