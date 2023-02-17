<?php
/**
 * User: iJhai
 * Date: 01/22/2020
 * Time: 10:20 AM
 */

namespace App\Services\User;

use App\Exceptions\EhrException\EhrException;
use App\Mail\SendVerificationMail;
use App\Models\TracerAuthentication;
use Keygen;
use Datatables;
use Auth;
use DateTime;
use Illuminate\Support\Facades\Mail;

class AuthService
{

    public function generateAuth($email){
        $authID = $this->generateKey();
        $checkExists = null;

        for( $i = 0; $i < 5; $i++ ){
            $checkExists = TracerAuthentication::where('auth_code', $authID)->first();
            if(!empty($checkExists)){ //if exist
                $authID = $this->generateKey();
            }else{
                break;
            }
        }

        if(!is_null($checkExists)){ //if exist
            throw new EhrException('Please try again!');
        }

        $authenticationModel = new TracerAuthentication();
        $authenticationModel->auth_code = $authID;
        $authenticationModel->auth_personnel_id = Auth::user()->personnel_id;
        $authenticationModel->auth_personnel_name = Auth::user()->personnel->p->name_first. ' ' . Auth::user()->personnel->p->name_last;
        $authenticationModel->auth_email = $email;

        if(!$authenticationModel->save()){
            throw new EhrException('Mail not sent!');
        }
        return true;
    }

    public function generateKey(){
        return Keygen::numeric(6)->generate();
    }

    public function rejectEmail($auth_code, $setStatus = 0){
        if($this->setMailStatus($auth_code, $setStatus)){
            return false;
        }
        return true;
    }

    public function sendVerificationEmail($auth_code){

        $mailSentCode = 2;
        $authenticationModel = TracerAuthentication::query()
            ->where([['auth_code', $auth_code]])->first();

        $data = [
            'name'  =>  $authenticationModel->auth_personnel_name,
            'auth_code' => $authenticationModel->auth_code,
        ];
        Mail::to($authenticationModel->auth_email)->send(new SendVerificationMail($data));
        if(Mail::failures()){
            throw new EhrException('Mail not sent!');
        }

        if(!$this->setMailStatus($auth_code, $mailSentCode)){
            throw new EhrException('Unable to set status into verifying!');
        }

    }

    public function checkEmailCode($auth_code){
        $mailCodeStatus = 1;
        $authenticationModel = TracerAuthentication::query()->where([['auth_code', $auth_code]])->first();
        if(empty($authenticationModel))
            throw new EhrException('Authentication code doesn\'t exist');

        if(Auth::user()->personnel_id != $authenticationModel->auth_personnel_id){
            throw new EhrException('Authentication code doesn\'t match the record you inputted');
        }
        return $this->setMailStatus($auth_code, $mailCodeStatus);
    }

    public function setMailStatus($auth_code, $setStatus = 0){
        $dt = new DateTime();
        $authenticationModel = TracerAuthentication::query()->where([['auth_code', $auth_code]])->first();
        $authenticationModel->auth_status = $setStatus;
        $authenticationModel->auth_verified_at = $dt->format('Y-m-d H:i:s');
        $authenticationModel->auth_modified_name = Auth::user()->personnel->p->name_first. ' ' . Auth::user()->personnel->p->name_last;
        $authenticationModel->auth_modified_id = Auth::user()->personnel_id;

        if(!$authenticationModel->save()){
            throw new EhrException('Authentication update failed');
        }
        return true;
    }

    public function getAuth($email=null){
        $query = TracerAuthentication::query();
        if(!empty($email)){
            $query = $query->where('auth_email', $email);
        }else{
            $query = $query->where('auth_status', 0);
        }
        $query = $query->LIMIT(10);

        return $query->get();
    }

}
