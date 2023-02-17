<?php

namespace App\API\V1\Controllers\Diagnostic;

use Exception;
use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Doctor\DoctorOrderService;
use App\Models\Encounter;
use App\Services\Diagnostic\DiagnosticsService;
use Psy\Util\Json;
use App\Services\Doctor\OrderResultsService;
use Illuminate\Support\Facades\Storage;

class DiagnosticController extends Controller
{
    public  function  searchIcds(Request $request){
        try{
            
            $dService = new DiagnosticsService();
            $resp = $dService->searchIcds($request->input('q'), false);

            return $this->jsonResponsePure($resp);
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function saveDiagnosticLabOrders(Request $request)
    {
        try {
            DB::beginTransaction();

            $service = DoctorOrderService::init($request->input('encounterNo'));
            
            $result = $service->saveDiagnosticLabOrders($request->post('orders'));
            DB::commit();
            $result = collect([])->put("data", $result);
            return $this->jsonSuccess($result->get('data')['message'], $result);
        } catch (EhrException $e) {
            DB::rollBack();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            DB::rollBack();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function saveDiagnosticRadOrders(Request $request)
    {
        try {
            DB::beginTransaction();

            $service = DoctorOrderService::init($request->input('encounterNo'));

            $result = $service->saveDiagnosticRadOrders($request->post('orders'));
            DB::commit();
            $result = collect([])->put("data", $result);
            return $this->jsonSuccess($result->get('data')['message'], $result);
        } catch (EhrException $e) {
            DB::rollBack();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            DB::rollBack();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function deleteDiagnosticLabOrders(Request $request)
    {
        try {
            DB::beginTransaction();

            $service = DoctorOrderService::init($request->input('encounterNo'));

            $result = $service->deleteDiagnosticLabOrders($request->post('data'));
            DB::commit();
            $result = collect([])->put("data", $result);
            return $this->jsonSuccess($result->get('data')['message'], $result);
        } catch (EhrException $e) {
            DB::rollBack();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            DB::rollBack();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    public function deleteDiagnosticRadOrders(Request $request)
    {
        try {
            DB::beginTransaction();

            $service = DoctorOrderService::init($request->input('encounterNo'));

            $result = $service->deleteDiagnosticRadOrders($request->post('data'));
            DB::commit();
            $result = collect([])->put("data", $result);
            return $this->jsonSuccess($result->get('data')['message'], $result);
        } catch (EhrException $e) {
            DB::rollBack();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            DB::rollBack();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }



    public function getResults(Request $request)
    {
        $service = OrderResultsService::init($request->input('id'));
        return $this->jsonResponsePure($service->getResulst_forportlet());
    }


    public function getLabReportPdf(Request $request)
    {
        $service = new OrderResultsService(new Encounter());
        return $service->labReportPDF($request->input('pid'), $request->input('lis_order_no'));
    }

    public function getLabReportPdfFilePath(Request $request)
    {
        $user = md5($request->user()->personnel_id);
        $service = new OrderResultsService(new Encounter());
        $data = $service->labReportPDFData($request->input('pid'), $request->input('lis_order_no'));
        Storage::disk('reports')->put("temp/c_{$user}.pdf", $data);
        return $this->jsonResponsePure([
            'filepath' => "{$this->getBaseUrl($request)}/reports/temp/c_{$user}.pdf",
        ]);
    }


    public function getRadReportPdf(Request $request)
    {
        $service = new OrderResultsService(new Encounter());
        return $service->radReportPDF($request->input('pid'), $request->input('batch_nr_grp'));
    }

    public function getRadReportPdfFilePath(Request $request)
    {
        $user = md5($request->user()->personnel_id);
        $service = new OrderResultsService(new Encounter());
        $data = $service->radReportPDFData($request->input('pid'), $request->input('batch_nr_grp'));
        Storage::disk('reports')->put("temp/c_{$user}.pdf", $data);
        return $this->jsonResponsePure([
            'filepath' => "{$this->getBaseUrl($request)}/reports/temp/c_{$user}.pdf",
        ]);
    }


    public function getRadPacsUrl(Request $request)
    {
        $service = new OrderResultsService(new Encounter());
        return $this->jsonResponsePure(['url' => $service->radPacsUls($request->input('refno'))]);
    }
}
