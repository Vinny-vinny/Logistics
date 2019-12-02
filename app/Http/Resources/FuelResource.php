<?php

namespace App\Http\Resources;

use App\FuelSupplier;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class FuelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'litres' => $this->litres,
            'vehicle_id' => $this->vehicle_id,
            'customer_id' => $this->customer_id,
            'expense_id' => $this->expense_id,
            'vehicle' => $this->vehicle->code ,
            'fuel_type_id' => $this->fuel_type_id,
            'fuel_type' => $this->fueltype->name,
            'invoice_no' => $this->invoice_no,
            'fuel_on' => $this->fuel_on,
            'rate' => $this->rate,
            'customer_type_id' => $this->customer_type_id,
            'asset_type' => $this->asset_type,
            'requested_by' => $this->requested_by,
            'jobcard_id' => $this->jobcard_id,
            'authorized_by' => $this->authorized_by,
            'odometer_readings' => $this->odometer_readings,
            'previous_odometer' => $this->vehicle ? $this->vehicle->odometer_readings :'',
             ];
    }
}
