<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobcardResource extends JsonResource
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
            'machine_id' => $this->machine_id,
            'item_cost_qty' => $this->item_cost_qty,
            'service_required' => $this->service_required,
            'track_by_id' => $this->track_by_id,
            'service_type_id' => $this->service_type_id,
            'mechanic_id' => $this->mechanic_id,
            'jobcard_category_id' => $this->jobcard_category_id,
            'service_type' => $this->service_type,
            'next_readings' => $this->next_readings,
            'current_readings' => $this->current_readings,
            'next_service_date' => $this->next_service_date,
            'fuel_balance_id' => $this->fuel_balance_id,
            'actual_date' => $this->actual_date,
            'completion_date' => $this->completion_date,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'maintenance' => $this->maintenance,
            'card_no' => $this->card_no,
            'driver' => $this->machine->user->name,
            'machine' => $this->machine->code,
            'track_name' => $this->trackby->name,
            'make' => $this->machine->make,
            'status' => $this->status,
            'cost_code' => $this->cost_code,
            'service_types' => $this->machine->service_types
        ];
    }
}
