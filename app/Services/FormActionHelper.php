<?php
namespace App\Services;

use App\Models\PersonnelCatalog;

class FormActionHelper
{

    public static function getFormTimeStamp($key = "")
    {
        return [    
            "{$key}modified_dt" => date('Y-m-d H:i:s'),
            "{$key}modified_by" => auth()->user()->personnel->personnel_id,
        ];
    }


    public static function getModifier($key, $data)
    {
        $modified_by =  PersonnelCatalog::query()->find($data['modified_by']);
        
        if(!$data || is_null($modified_by))
            return [
                "{$key}modified_dt" => '',
                "{$key}modified_by" => '',
            ];
            
        if(strpos(strtolower($data['modified_dt']), 'am') == true || strpos(strtolower($data['modified_dt']), 'pm') == true)
            $modified_dt  = $data['modified_dt'];
        else{
            $thisentry = new \DateTime($data['modified_dt']);
            $modified_dt = date('m-d-Y h:i a', ($thisentry->getTimestamp() * 1));
        }

        return [
            "{$key}modified_dt" => $modified_dt,
            "{$key}modified_by" => $modified_by->p->getFullname().' '.$modified_dt,
        ];
    }

}