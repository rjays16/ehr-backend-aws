<?php

namespace App\Models\HIS;

use App\Models\PersonCatalog;
use Illuminate\Database\Eloquent\Model;

class HisPerson extends PersonCatalog
{
    protected $connection = 'his_mysql';

    protected $table = 'care_person';

    protected $primaryKey = 'pid';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

}
