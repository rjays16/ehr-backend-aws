<?php

namespace App\Http\Controllers\Auth;

use App\AuthGenerator;
use App\Exceptions\EhrException\EhrException;
use App\Http\Controllers\Controller;
use App\Services\User\AuthService;
use Exception;
use Illuminate\Http\Request;
use Keygen;
use Illuminate\Support\Facades\DB;
use App\Exceptions\EhrException\EhrLogException;

class AuthGeneratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('AuthGenerator.auth-generator');
    }

    public function generateAuth(Request $request){
        $authService = new AuthService();
        $status = $authService->generateAuth($request->post('email'));
        if(!$status){
            return $this->jsonError500('Request error! Please contact you administrator');
        }
        return $this->jsonSuccess('Your request has been sent');
    }

    public function verifyEmail(Request $request){
        $authService = new AuthService();
        $datas = $request->all();
        if($datas['status']){
            return $this->jsonSuccess($authService->rejectEmail($datas['auth_code'], $datas['status']));
        }
        return $this->jsonSuccess($authService->sendVerificationEmail($datas['auth_code']));
    }

    public function getAuth(Request $request){
        $authService = new AuthService();
        $datas = $request->all();

        return response()->json(['data'=>$authService->getAuth($datas['authCode'])]);
    }

    public function setAuth(Request $request){
        $datas = $request->all();
        DB::beginTransaction();
        try{
            $authService = new AuthService();
            $status = $authService->checkEmailCode($datas['auth_code']);
            DB::commit();
            return $this->jsonSuccess('Successfully authorized');
        }catch (EhrException $e){
            DB::rollback();
            return $this->jsonResponse($e->getMessage(), $e->getCode(), $e->getRespDataJson(), $e->getTrace());
        }catch (Exception $e){
            DB::rollback();
            new EhrLogException($e, $request->all());
            return $this->jsonError500($e->getMessage(), [], $e->getTrace());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AuthGenerator  $authGenerator
     * @return \Illuminate\Http\Response
     */
    public function show(AuthGenerator $authGenerator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AuthGenerator  $authGenerator
     * @return \Illuminate\Http\Response
     */
    public function edit(AuthGenerator $authGenerator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AuthGenerator  $authGenerator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AuthGenerator $authGenerator)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AuthGenerator  $authGenerator
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuthGenerator $authGenerator)
    {
        //
    }
}
