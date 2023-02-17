<?php

namespace App\API\V1\Controllers\Doctor;

use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Doctor\DoctorOrderService;
use App\Services\Doctor\ReferralService;
use Exception;
use Illuminate\Support\Facades\DB;

class PlanManagmentController extends Controller
{
    

    public function getAllOrders(Request $request)
    {
        try {
            $service = DoctorOrderService::init($request->input('encounterNo'));
            
            $data = $service->getAllOrders();
            return $this->jsonResponsePure([
                'batchOrders' => $data
            ]);
        } catch (EhrException $e) {
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }



    public function getAllReferralOrders(Request $request)
    {
        try {
            $service = ReferralService::init($request->input('id'));
            
            $data = $service->getAllReferrals();
            return $this->jsonResponsePure($data);
        } catch (EhrException $e) {
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }


    public function finalizedOrder(Request $request)
    {
        try {
            DB::beginTransaction();
            $service = DoctorOrderService::init($request->input('encounterNo'));

            $resp = $service->finalizeOrders();
            DB::commit();
            return $this->jsonSuccess($resp['msg'], $resp);
        } catch (EhrException $e) {
            DB::rollBack();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            DB::rollBack();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [
            ], $e->getTrace());
        }        
    }
}
