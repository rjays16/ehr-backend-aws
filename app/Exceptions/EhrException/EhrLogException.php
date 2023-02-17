<?php
/**
 * Created by PhpStorm.
 * User: segworks-bonix
 * Date: 5/1/2019
 * Time: 10:24 PM
 */

namespace App\Exceptions\EhrException;

use Illuminate\Support\Facades\Storage;
class EhrLogException
{
    public function __construct($exception, $otherData = []){
        
        if(!config('app.debug'))
            return false;

        $log = (isset($exception->stamp)? $exception->stamp : date('Y-m-d h:i:sa')).' : '.(!auth()->guest() ? auth()->user()->username : '<Yii::app()->user undefined>').'('.(!auth()->guest() ? auth()->user()->id : '<Yii::app()->user undefined>').'): '.$exception->getMessage(). ' => '.$exception->getFile() . " ({$exception->getLine()})";
        
        self::logMessage($log, $otherData);
    }

    public static function logMessage($message, $otherData)
    {
        Storage::disk('local')->append("logs/applogs_".date('Y-m-d').".log", $message);
        if(count($otherData) > 0)
            Storage::disk('local')->append("logs/applogs_".date('Y-m-d').".log", ('-> Data: '.print_r($otherData, true)));
    }
}