<?php

namespace App\API\V1\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Doctor\CF4Service;
use App\Services\Doctor\PrescriptionService;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use Exception;

// use PHPJasper\PHPJasper;

class ReportsController extends Controller
{
    public function generateCf4(Request $request)
    {
        
        try {
            
            $service = CF4Service::init($request->input('encounter_nr'));
            return $service->printCf4PDF();
        }catch (EhrException $e){
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        } catch (Exception $e) {
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(),[], $e->getTrace());
        }
        
    }


    public function getCf4Url(Request $request)
    {
        $user = md5($request->user()->personnel_id);
        $data = file_get_contents(config('app.url')."/printCf4?{$request->getQueryString()}&token={$_SESSION['token']}");
        Storage::disk('reports')->put("temp/c_{$user}.pdf", $data);
        
        $validation = CF4Service::init($request->input('encounter_nr'));
        $checker = $validation->checkMandatoryFields();

        $convert_to_array = explode(', ', $checker);
        $results = [];
        if(empty($convert_to_array[count($convert_to_array)-1])) {
            unset($convert_to_array[count($convert_to_array)-1]);
        }
        if(!empty($convert_to_array)){
            $results = [
                'mandatories' => $convert_to_array,
                'message'     => 'These are the fields that still need encoding'
            ];
        }
        
        return $this->jsonResponsePure([
            'filepath' => "{$this->getBaseUrl($request)}/reports/temp/c_{$user}.pdf",
            'check'    =>  $results
        ]);
    }



    public function prescription(Request $request)
    {
        $service = PrescriptionService::init($request->input('encounter_no'));
        return $service->prescriptionPDF($request->input('selected_orders'), $request->input('MedsOrder'), $request->input('is_group'));
    }


    public function getPrescriptionFilePath(Request $request)
    {
        $user = md5($request->user()->personnel_id);
        $query = urldecode($request->getQueryString());
        $data = file_get_contents(config('app.url')."/doctor/patient/prescription?{$query}&token={$_SESSION['token']}");
        Storage::disk('reports')->put("temp/c_{$user}.pdf", $data);
        return $this->jsonResponsePure([
            'filepath' => "{$this->getBaseUrl($request)}/reports/temp/c_{$user}.pdf",
        ]);
    }


    public function mobileManual(Request $request)
    {
        $output = getcwd() ."/documents/User_Manual_EHRv2-mobile.pdf";
        return response()->make(file_get_contents($output), 200,[
            'Content-type' => "application/pdf",
            'Content-disposition' => "inline;filename=User_Manual_EHRv2-mobile.pdf",
            'Content-Transfer-Encoding' => "binary",
            'Accept-Ranges' => "bytes",
        ]);
        
    }

    public function mobileManualFilepath(Request $request)
    {
        $user = md5($request->user()->personnel_id);
        $data = file_get_contents(getcwd() ."/documents/User_Manual_EHRv2-mobile.pdf");
        Storage::disk('reports')->put("temp/c_{$user}.pdf", $data);
        return $this->jsonResponsePure([
            'filepath' => "{$this->getBaseUrl($request)}/reports/temp/c_{$user}.pdf",
        ]);
    }
}
