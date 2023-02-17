<?php


namespace App\Api\V1\Controllers\Auth;


use App\Exceptions\EhrException\EhrException;
use App\Exceptions\EhrException\EhrLogException;
use App\Http\Controllers\Controller;
use App\Services\User\UserService;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    public function login(Request $request)
    {

        try{
            DB::beginTransaction();

            $userserv = new UserService();

            $resp = $userserv->loginMobileApi(
                $request->post('username'),
                $request->post('password'),
                $request->post('device_uuid'),
                $request->post('device_device_unique_id'),
                $request->post('device_platform'),
                $request->post('device_model'),
                $request->post('email')
            );

            DB::commit();
            return $this->jsonSuccess('Login success.', $resp);

        }catch (EhrException $e){
            DB::rollBack();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            DB::rollBack();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }


        return $this->jsonError500('Something went wrong.');
    }

    public function loginAuth(Request $request){


        $userserv = new UserService();
        $datas = $request->all();

        $status = $userserv->loginWebApi($datas['username'], $datas['password']);

        return $this->jsonSuccess($status['token'], [$status]);
    }

}
