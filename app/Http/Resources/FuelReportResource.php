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
            'Date' => Carbon::parse($this->fuel_on)->format('d/m/Y'),
            'Item Code' => $this->fueltype->code,
            'Item Description' => $this->fueltype->description,
            'Reference' => $this->fuel_no,
            'Quantity' => $this->litres, 
            'Unit Cost' => $this->rate, 
            'Amount' => $this->litres*$this->rate,
            'Project' => $this->vehicle_name ? $this->vehicle_name : $this->vehicle->code,   
        ];
    }
}
