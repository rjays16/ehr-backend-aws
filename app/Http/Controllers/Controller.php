<?php

namespace App\Http\Controllers;

use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use App\Services\Doctor\Permission\PermissionService;
use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * @return array
     */
    public static function responseFormat($message = "", $code = 200,
        $otherData = [], $traces = [], $e = null
    ) {

        $resp_data = [
            'code'    => $code,
            'status'  => $code == 200 ? true : false,
            'saved'   => $code == 200 ? true : false,
            'message' => $message,
        ];


        if (config('app.debug')) {

            if ($code != 200) {
                /**
                 * @var \Illuminate\Http\Request $request
                 */
                $request = request();
                $reqData = $request->json()->all();
                if (count($reqData) > 0) {
                    $request->initialize($request->query(), $reqData);
                }
                $resp_data['request_data'] = array_merge(
                    $reqData, $request->all()
                );

                EhrLogException::logMessage(
                    "DEBUG LOG: {$message} ({$code})", $resp_data
                );

            }


            if (count($traces) > 0) {
                $resp_data['traces'] = $traces;
            }

            if ( ! is_null($e)) {
                $resp_data['traces'] = $e->getTrace();

                if ($e instanceof EhrException) {
                    /**
                     * @var EhrException $e
                     */
                    $resp_data['exception_data'] = $e->getRespDataJson();
                }

                // new EhrLogException($e, $resp_data);
            }
            // else
            // EhrLogException::logMessage("DEBUG LOG: {$message} ({$code})", $resp_data);
        }


        if ($otherData instanceof Collection) {
            /**
             * @var Collection $otherData
             */
            $otherData = $otherData->merge($resp_data);
            $otherData = $otherData->toArray();
        } else {
            $otherData = array_merge($resp_data, $otherData);
        }

        if($code == PermissionService::$errorCode){
            $otherData['permissions'] = PermissionService::$ehrPermissions;
        }

        if ($code >= 500 || ! is_int($code)) {
            $code = 500;
        }

        return [
            'data' => $otherData,
            'code' => $code,
        ];
    }


    public function getBaseUrl(Request $request)
    {
        return "{$this->getBaseHost($request)}{$request->getBasePath()}";
    }


    public function getBaseHost(Request $request)
    {
        return "{$request->getScheme()}://{$request->getHttpHost()}";
    }


    /**
     * @return array
     */
    public static function responseExceptionFormat($e)
    {
        return self::responseFormat(
            $e->getMessage(), $e->getCode(), [], [], $e
        );
    }

    public function jsonResponse($message = "", $code = 200, $otherData = [],
        $traces = []
    ) {
        $format = self::responseFormat($message, $code, $otherData, $traces);

        return response()->json($format['data'])->setStatusCode(
            $format['code']
        );
    }

    public function jsonResponsePure($data = [], $code = 200, $traces = [])
    {
        return response()->json(count($traces) > 0 ? $traces : $data)
            ->setStatusCode($code);
    }

    public function jsonSuccess($message = '', $otherData = [], $traces = [])
    {
        return $this->jsonResponse(
            $message,
            200,
            $otherData,
            $traces
        );
    }

    public function jsonError404($message = '', $otherData = [], $traces = [])
    {
        return $this->jsonResponse(
            $message,
            404,
            $otherData,
            $traces
        );
    }

    public function jsonError401($message = '', $otherData = [], $traces = [])
    {
        return $this->jsonResponse(
            $message,
            401,
            $otherData,
            $traces
        );
    }

    public function jsonError500($message = '', $otherData = [], $traces = [])
    {
        return $this->jsonResponse(
            $message,
            500,
            $otherData,
            $traces
        );
    }


    public function logs(Request $request)
    {

        $date = $request->input('date', date('Y-m-d'));

        $output = realpath(storage_path()."/app/logs/applogs_{$date}.log");
        if ($output === false) {
            throw new EhrException(
                "No Logs for this date {$date}", 404, [], true
            );
        }


        return response()->make(
            "<pre>".file_get_contents($output)."</pre>", 200, [
            'Content-disposition'       => "inline;filename=logs{$date}.txt",
            'Content-Transfer-Encoding' => "binary",
            'Accept-Ranges'             => "bytes",
        ]
        );
    }
}
