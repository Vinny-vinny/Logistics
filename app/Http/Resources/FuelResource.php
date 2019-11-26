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
            'driver_id' => $this->driver_id,
            'expense_id' => $this->expense_id,
            'driver_name' => $this->driver_name,
            'vehicle' => $this->vehicle->code ,
            'fuel_type_id' => $this->fuel_type_id,
            'fuel_type' => $this->fueltype->name,
            'invoice_no' => $this->invoice_no,
            'fuel_on' => $this->fuel_on,
            'other_expenses' => $this->other_expenses,
            'rate' => $this->rate,
            'customer_type' => $this->customer_type,
            'asset_type' => $this->asset_type,
            'odometer_readings' => $this->odometer_readings,
            'previous_odometer' => $this->vehicle ? $this->vehicle->odometer_readings :'',
             ];
    }
}
