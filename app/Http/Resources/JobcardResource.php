<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'track_by_id' => $this->track_by_id,
            'service_type_id' => $this->service_type_id,
            'job_type_id' => $this->job_type_id,
            'customer_id' => $this->customer_id,
            'mechanic_id' => $this->mechanic_id,
            'project_id' => $this->project_id,
            'jobcard_category_id' => $this->jobcard_category_id,
            'category' => $this->category ? $this->category->name : '',
            'service_type' => $this->service_type,
            'customer_type_id' => $this->customer_type_id,
            'next_readings' => $this->next_readings,
            'current_readings' => $this->current_readings,
            'previous_readings' => $this->machine->current_readings,
            'next_service_date' => $this->next_service_date,
            'fuel_balance_id' => $this->fuel_balance_id,
            'actual_date' => $this->actual_date,
            'start_date' => Carbon::parse($this->actual_date)->format('d/m/Y'),
            'complete_date' => Carbon::parse($this->completion_date)->format('d/m/Y'),
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
            'requisition_id' => $this->requisition_id,
            'inventory_items' => $this->requisition_id ? $this->requisition->inventory_items : '',
            'checklist_assigned' => $this->checklist_assigned,
            'service_types' => $this->machine->service_types
        ];
    }
}
