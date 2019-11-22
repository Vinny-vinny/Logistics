<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FuelPrintResource extends JsonResource
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
            'supplier_id' => $this->supplier_id,
            'driver_id' => $this->driver_id,
            'currency' => $this->currency,
            'expense_id' => $this->expense_id,
            'vehicle_name' => $this->vehicle_name,
            'driver_name' => $this->driver_name,
            'vehicle' => $this->vehicle_name ? $this->vehicle_name : $this->vehicle->code ,
            'fuel_type_id' => $this->fuel_type_id,
            'fuel_type' => $this->fueltype->name,
            'invoice_no' => $this->invoice_no ? $this->invoice_no :$this->cash_sale_no ,
            'fuel_on' => Carbon::parse($this->fuel_on)->format('d/m/y'),
            'price' => $this->supplier ? $this->supplier->price : $this->rate,
            'other_expenses' => $this->other_expenses,
            'supplier_type' => $this->supplier_type,
            'supplier_name' => $this->supplier_name,
            'rate' => $this->rate,
            'odometer_readings' => $this->odometer_readings,
            'km_readings' => $this->vehicle ? $this->vehicle->current_readings:'',
            'previous_odometer' => $this->vehicle ? $this->vehicle->odometer_readings :'',
        ];
    }
}
