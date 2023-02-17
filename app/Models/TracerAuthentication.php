<?php


namespace App\Models;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

/**
 * @property $auth_code
 * @property $auth_personnel_id
 * @property $auth_personnel_name
 * @property $auth_email
 * @property $auth_status
 * @property $auth_verified_at
 * @property $auth_modified_id
 * @property $auth_modified_name
 */

class TracerAuthentication extends Eloquent
{
    use SoftDeletes;
    use HybridRelations;

    protected $form_code = 'AT';

    protected $connection = 'mongodb';
    protected $collection = 'entities.authentication';

    protected $fillable = [
        'auth_code',
        'auth_personnel_id',
        'auth_personnel_name',
        'auth_email',
    ];

    /**
     * AUTH_STATUS
     * on_process = 0
     * used = 1
     * verifying = 2
     * deleted = 3
     */
    protected $attributes = [
        'auth_status' => 0,
        'auth_verified_at' => '',
        'auth_modified_id' => '',
        'auth_modified_name' => '',
        'auth_personnel_id' => '',
        'auth_personnel_name' => '',
    ];

}
