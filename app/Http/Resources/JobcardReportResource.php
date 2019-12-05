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
            'Labour Cost' => $this->labour_cost,
            'Next Readings' => $this->next_readings,
            'Current Readings' => $this->current_readings,
            'Next Service Date' => $this->next_service_date,
            'Fuel Balance' => $this->fuelbalance->litres.' Litres',
            'Actual Date' => $this->actual_date,
            'Completion Date' => $this->completion_date,
            'Time In' => $this->time_in,
            'Time Out' => $this->time_out,
            'Customer' =>  $this->customer->name,
            'Created' => Carbon::parse($this->created_at)->diffForHumans()

        ];
    }
}
