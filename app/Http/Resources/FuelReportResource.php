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
            'date' => Carbon::parse($this->fuel_on)->format('d/m/Y'),
            'item_code' => $this->fueltype->code,
            'description' => $this->fueltype->description,
            'reference' => $this->fuel_no,
            'quantity' => $this->litres, 
            'unit_cost' => $this->rate, 
            'amount' => $this->litres*$this->rate,
            'project' => $this->vehicle_name ? $this->vehicle_name : $this->vehicle->code,   
        ];
    }
}
