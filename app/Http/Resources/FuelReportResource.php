<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FuelReportResource extends JsonResource
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
            'Vehicle' => $this->vehicle_name ? $this->vehicle_name : $this->vehicle->code,
            'fuel_on' => Carbon::parse($this->fuel_on)->format('d/m/y'),
            'Litres' => $this->litres,
            'Currency' => $this->currency ? $this->currency : $this->supplier->currency,
            'Fuel Type' => $this->fueltype->name,
            'Invoice No' => $this->invoice_no,
            'Rate' => $this->supplier ? $this->supplier->price : $this->rate,
            'Cash Sale No' => $this->cash_sale_no,
            'Supplier' => $this->supplier_name ? $this->supplier_name : $this->supplier->name,
            'Previous Odometer Readings' => $this->vehicle ? $this->vehicle->odometer_readings :'',
            'Current Odometer readings' => $this->odometer_readings,
            'Driver' => $this->driver_name ? $this->driver_name : $this->vehicle->user->name,
        ];
    }
}
