<?php

namespace App\Models\HIS;

use Illuminate\Database\Eloquent\Model;

class HisBillingEncounter extends Model
{
    protected $connection = 'his_mysql';

    protected $table = 'seg_billing_encounter';

    protected $primaryKey = 'bill_nr';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;


    public function caserate()
    {
        return $this->hasMany(HisBillingCaserate::class, 'bill_nr','bill_nr');
    }
}
