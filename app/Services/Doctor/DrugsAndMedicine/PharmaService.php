<?php


namespace App\Services\Doctor\DrugsAndMedicine;


use App\Exceptions\EhrException\EhrException;
use App\Exceptions\His\HisActiveResource;
use App\Models\Encounter;
use App\Models\ItemCatalog;
use App\Services\Doctor\MedicinesServices;
use App\Services\Doctor\Permission\PermissionService;
use App\Services\Doctor\PMH\PastMedicalHistoryService;

class PharmaService
{

    /**
     * @var Encounter $encounter
     */
    public $encounter;

    /** @var PermissionService $permService */
    public $permService;

    function __construct(Encounter $encounter_no)
    {
        $this->encounter = $encounter_no;
        $this->permService = new PermissionService($encounter_no);
    }

    public static function init($encounter){
        $encounter = Encounter::query()->find($encounter);
        if (is_null($encounter))
            throw new EhrException('Encounter was not found. ');

        return new PharmaService($encounter);
    }


    

    public function generateMeds(){
        
        $medServ = new MedicinesServices();

        $parent_encounter = $this->encounter->parent_encounter_nr;
        $billing = $this->encounter->hisEncounter->billing;
        $is_final = false;

        if(!is_null($billing))
            $is_final = !($billing->is_final);

        $medicines = collect();
        if ($parent_encounter && $is_final) {
            $medicines = $medicines->merge($medServ->medication($this->encounter->encounter_no));
            $medicines = $medicines->merge($medServ->medication($parent_encounter));
        } else {
            $medicines = $medicines->merge($medServ->medication($this->encounter->encounter_no));
        }
        
        $medService = new MedicinesServices();
        return $medicines->map(function($item) use ($medService){
            return $this->getMedItemDetails($item,$medService );
        })->toArray();
    }


    private function getMedItemDetails($item, MedicinesServices $medService ) {
        $gen = ItemCatalog::query()->where('item_code' ,$item['item_id'])->first();
        if($gen){
            $item['item_name'] = $gen->item_name;
            $item['generic_name'] = $gen->brand_name;
        }
        else{
            $item['item_name'] = '';
            $item['generic_name'] = $gen->generic_name;
        }

        $details = $medService->medicine_details($item['refno'],$item['item_id']);
        $item['route'] = $details?$details->route:'';
        $item['frequency'] = $details?$details->frequency:'';
        return $item;
    }

    public function hisMedicines()
    {
        $medicines_service = new MedicinesServices();
        $medicines_parent = $medicines_outside2 = [];
        
        if ($this->encounter->parent_encounter_nr && !($this->encounter->hisEncounter->final_billing)) {
            $medicines_parent = $medicines_service->medication($this->encounter->parent_encounter_nr);
            $medicines_outside2 = $medicines_service->outsideMedication($this->encounter->parent_encounter_nr);
        }

        $medicines = $medicines_service->medication($this->encounter->encounter_no);
        $medicines_outside = $medicines_service->outsideMedication($this->encounter->encounter_no);
        

        $medicines = collect($medicines)
                            ->merge($medicines_parent)
                            ->merge($medicines_outside)
                            ->merge($medicines_outside2)
                            ->toArray();

        return $medicines;
    }

    public function actionMedication($data){

        if(!$this->permService->hasDrugsMedsEdit())
            throw new EhrException(PermissionService::$errorMessage, PermissionService::$errorCode);


        $rules = [
            'refno' => 'required',
            'item_code' => 'required',
            'frequency' => 'required|max:50',
            'route' => 'required|max:500'
        ];

        $validator = validator($data, $rules);

        if($validator->fails())
            throw new EhrException("Invalid Fields!",500, [
                'errors' => $validator->errors()
            ]);

        $medServ = new MedicinesServices();
        $freq_route = $medServ->onSaveFreqRoute($data);

        if(!$freq_route)
            throw new EhrException('Unable to frequency and route ');

        return [
            "message"   => "Frequency and route successfully saved",
            "data"      =>  $data
        ];

    }

    public function getMedication($data){

        $medService = new MedicinesServices();
        $item = $medService->getHISRefItem($data['refno'],$data['item_id']);

        $item[0] = $this->getMedItemDetails($item[0],$medService);

        return $item;
    }

    public static function config(){
        return [
            'm-patient-medication' => [
                'p-medication-view' => [
                    'role_name' => [
                        PermissionService::$doctor,
                        PermissionService::$nurse
                    ],
                    'other-permissions' => []
                ],
                'p-medication-save' => [
                    'role_name' => [
                        PermissionService::$doctor
                    ],
                    'other-permissions' => []
                ],
            ],
        ];
    }
}