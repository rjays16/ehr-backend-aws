<?php

/**
 * LaboratoryService.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2016, Segworks Technologies Corporation
 *
 */

namespace App\Models\Order\Diagnostic;

use App\Models\LabService;
use App\Models\LabServiceGroup;
use App\Models\TransactionType;
use App\Services\Doctor\Permission\PermissionService;
/**
 *
 * Description of LaboratoryService
 *
 */

class LaboratoryService implements DiagnosticServiceInterface
{

    /** @var LabService $service */
    protected $service;

    /**
     * LaboratoryService constructor.
     */
    public function __construct($service)
    {
        $this->service = $service;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return 'LABORATORY:'.$this->service->service_id;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return is_null($this->service->group)? 0 : $this->service->group->group_code;
    }

    /**
     * @return string
     */
    public function getSentOut()
    {
        return $this->service->is_for_sendout;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->service->service_id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->service->service_name;
    }

    /**
     * @return string
     */
    public function getGroupCode()
    {
        return $this->service->group_id;
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->service->group->group_name;
    }

    public function getServicePrice() {

        return is_null($this->service->group)? 0 : $this->service->group->group_name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    public function getServicename()
    {
        return 'LABORATORY';
    }

    public function getSocialized()
    {
        return $this->service->is_socialized;
    }

    public function getStatus()
    {
        return $this->service->status;
    }

    public function getFemale()
    {
        return $this->service->is_for_female;
    }

    public function getMale()
    {
        return $this->service->is_for_male;
    }

    public function getCash()
    {
        return $this->service->price_cash;
    }

    public function getCharge()
    {
        return $this->service->price_charge;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getIdentifier(),
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'groupCode' => $this->getGroupCode(),
            'groupName' => $this->getGroupName(),
            'sentOut' => (int)$this->getSentOut(),
            'servicename' => $this->getServicename(),
            'is_socialized' => $this->getSocialized(),
            'status'        => $this->getStatus(),
            'female'        => (int)$this->getFemale(),
            'male'          => (int)$this->getMale(),
            'price_cash'    => $this->getCash(),
            'price_charge'  => $this->getCharge(),
        ];
    }

    /**
     * @param $serviceCode
     *
     * @return static
     * @throws \CException
     */
    public static function instance($serviceCode)
    {
        $service = LabService::model()->findByPk($serviceCode);
        if ($service) {
            return new LaboratoryService($service);
        } else {
            throw new \CException('Laboratory service does not exist');
        }
    }

    /**
     *
     * @param string|null $query
     * @param array $options
     *
     * @return CActiveDataProvider
     */
    public static function getSearchProvider($query, $options = [])
    {
        $criteria = new \CDbCriteria();
        if ($query === '') {
            $criteria->condition = '0';
        } else {
            $criteria->compare('service_name', $query, true);
        }

        $criteria->addCondition('t.is_deleted=0');
        $criteria->with = [
                'group' => [
                        'select' => [
                                'group_id',
                                'area_id',
                                'group_name',
                                'group_alt_name',
                                'area_id',
                        ],
                ],
        ];

        $criteria->addCondition('group.group_id NOT IN("B", "SPL", "SPC", "ECHO", "CATH")');
        $criteria->addCondition('group.is_deleted=0');
        $criteria->order = 'service_name ASC';

        // return new CActiveDataProvider(
        //         LabService::model(), [
        //                 'criteria'   => $criteria,
        //                 'pagination' => false,
        //         ]
        // );
    }

    public static function config()
    {
        return [
            'm-patient-laboratory' => [
                    'p-patient-laboratory-view' => [
                            'role_name'         => [],
                            'other-permissions' => [],
                    ],
                    'p-patient-laboratory-save' => [
                            'role_name'         => [
                                // PermissionService::$doctor,
                            ],
                            'other-permissions' => [],
                    ],
                    'default-options' => collect([])
                        ->merge([
                            // 'transaction_type' => self::getChargeType(),
                            'transaction_type' => [],
                        ])
                        // ->merge(self::getDiagnosticLabServiceOptions())
                        ->merge([])
                    ,
            ],
        ];
    }


    private static function getDiagnosticLabServiceOptions()
    {
        // if (@$options['gender'] == 'M') {
        //     $condition = 'is_for_male = 1 OR (is_for_female = 0 && is_for_male = 0 )';
        // } else {
        //     $condition = 'is_for_female = 1 OR (is_for_male = 0 && is_for_female = 0 )';
        // }

        $lab = LabService::query()->orderBy('service_name')->get()->map(function ($service) {
            /** @var LabService $service */
            $lab = new LaboratoryService($service);

            return $lab->toArray();
        });

        $labGroups = LabServiceGroup::query()->whereNotIn('group_id',["B", "SPL", "SPC", "CATH", "ECHO"])->get()->map(function ($group) {
            /** @var LabServiceGroup $group */
            return [
                'id'   => $group->group_id,
                'name' => $group->group_name,
            ];
        });


        return [
            'lab-services' => $lab,
            'lab-group-services' => $labGroups,
        ];
    }

    public static function getChargeType()
    {
        $model = new TransactionType();
        $transaction = $model->TransactionType();
        $menu = [
            [
                'id' => '',
                'charge_name' => 'PERSONAL',
            ]
        ];
        foreach ($transaction as $key => $entry) {
            $menu[] = [
                'id' => $entry['id'],
                'charge_name' => $entry['charge_name']
            ];
        }

        return $menu;
    }
}
