<?php

/**
 * RadiologyService.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2016, Segworks Technologies Corporation
 *
 */

namespace App\Models\Order\Diagnostic;

use App\Models\AreaCatalog;
use App\Models\RadioService;
use App\Models\RadioServiceGroup;
use App\Models\TransactionType;
use App\Services\Doctor\Permission\PermissionService;

/**
 *
 * Description of RadiologyService
 *
 */

class RadiologyService implements DiagnosticServiceInterface
{

    /** @var RadioService $service */
    protected $service;

    /**
     * RadiologyService constructor.
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
        return 'RADIOLOGY:'.$this->service->service_id;
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
    public function getGroup()
    {
        return is_null($this->service->group)? 0 : $this->service->group->group_code;
    }

    /**
     * @return string
     */
    public function getServicePrice() {
        return is_null($this->service->latestRadpriceCatalog)? 0 : $this->service->latestRadpriceCatalog->price;
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->service->group->group_name;
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
        return 'RADIOLOGY';
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
            'price' => $this->getServicePrice(),
            'cash_price' => $this->getCash(),
            'charge_price' => $this->getCharge(),
            'servicename' => $this->getServicename()
        ];
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
     * @param $serviceCode
     *
     * @return static
     * @throws \CException
     */
    public static function instance($serviceCode)
    {
        $service = RadioService::model()->findByPk($serviceCode);
        if ($service) {
            return new RadiologyService($service);
        } else {
            throw new \CException('Laboratory service does not exist');
        }
    }

    /**
     * @param string|null $query
     * @param array $options
     *
     * @return CActiveDataProvider
     */
    public static function getSearchProvider($query, $options=[])
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
                    'group_name'
                ]
            ]
        ];
        $criteria->addCondition('group.is_deleted=0');
        $criteria->order = 'service_name ASC';
        return new CActiveDataProvider(RadioService::model(), [
            'criteria' => $criteria,
            'pagination' => false
        ]);
    }

    public static function config()
    {
        return [
                'm-patient-radiology' => [
                        'p-patient-radiology-view' => [
                                'role_name'         => [],
                                'other-permissions' => [],
                        ],
                        'p-patient-radiology-save' => [
                                'role_name'         => [
                                        PermissionService::$doctor,
                                ],
                                'other-permissions' => [],
                        ],
                        'default-options'           => collect([])
                        ->merge([
                            'rad-group-areas' => self::getArea(),
                            // 'rad-group-areas' => [],
                        ])
                        ->merge([
                            // 'transaction_type' => LaboratoryService::getChargeType(),
                            'transaction_type' => [],
                        ])
                        // ->merge(self::getDiagnosticRadServiceOptions())
                        ->merge([])
                        ,
                ],
        ];
    }

    private static function getArea()
    {
        return AreaCatalog::query()
                    ->whereIn('dept_id',['158'])
                    ->orderBy('area_desc')->get()
                    ->map(function (AreaCatalog $area) {
                        return [
                                'id'   => $area->area_id,
                                'name' => $area->area_desc,
                        ];
                    })
                    ;

        
    }


    private static function getDiagnosticRadServiceOptions()
    {

        $rad = RadioService::query()
            ->select('smed_radio_service.*')
            ->join('smed_radio_service_group','smed_radio_service_group.group_id','=','smed_radio_service.group_id')
            ->where('smed_radio_service.is_deleted',0)
            ->orderBy('smed_radio_service.service_name')
            ->get()
            ->map(function ($service) {
                /** @var RadioService $service */
                $lab = new RadiologyService($service);

                return $lab->toArray();
            }
        );

        $radGroups = RadioServiceGroup::query()->orderBy('group_name')->get()
            ->map(function ($group) {
                /** @var RadioServiceGroup $group */
                return [
                    'id'      => $group->group_id,
                    'area_id' => $group->area_id,
                    'name'    => $group->group_name,
                ];
            }
        );


        return [
            'rad-services' => $rad,
            'rad-group-services' => $radGroups,
        ];
    }

}
