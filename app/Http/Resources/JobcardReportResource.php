<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobcardReportResource extends JsonResource
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
            'Card' => $this->card_no,
            'Driver' => $this->machine->user->name,
            'Machine' => $this->machine->code,
            'labour' => $this->labour,
            'cost' => $this->cost,
            'Next Readings' => $this->next_readings,
            'Current Readings' => $this->current_readings,
            'Next Service Date' => $this->next_service_date,
            'Fuel Balance' => $this->fuelbalance->name,
            'Actual Date' => $this->actual_date,
            'Completion Date' => $this->completion_date,
            'Time In' => $this->time_in,
            'Time Out' => $this->time_out,
            'Supplier' =>  $this->internal_supplier ? $this->internal_supplier->name: $this->external_supplier->name,
            'Created' => Carbon::parse($this->created_at)->diffForHumans()

        ];
    }
}
