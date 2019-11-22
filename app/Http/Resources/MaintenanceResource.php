<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceResource extends JsonResource
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
            'service_provider_type_id' => $this->service_provider_type_id,
            'service_provider_id' => $this->service_provider_id,
            'item_cost_qty' => $this->item_cost_qty,
            'service_required' => $this->service_required,
            'labour' => $this->labour,
            'created' => Carbon::parse($this->created_at)->format('d/m/y'),
            'cost' => $this->cost,
            'track_by_id' => $this->track_by_id,
            'supplier_id' => $this->supplier_id,
            'service_type_id' => $this->service_type_id,
            'service_type' => $this->service_type,
            'next_readings' => $this->next_readings,
            'current_readings' => $this->current_readings,
            'next_service_date' => Carbon::parse($this->next_service_date)->format('d/m/y'),
            'fuel_balance_id' => $this->fuel_balance_id,
            'actual_date' => Carbon::parse($this->actual_date)->format('d/m/y'),
            'completion_date' => Carbon::parse($this->completion_date)->format('d/m/y'),
            'checklist_file' => $this->checklist_file,
            'service_type_description' => $this->service_type_description,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'maintenance' => $this->maintenance,
            'card_no' => $this->card_no,
            'code' => $this->machine->code,
            'driver' => $this->machine->user->name,
            'supplier' =>  $this->internal_supplier ? $this->internal_supplier->name: $this->external_supplier->name,
            'machine' => $this->machine->code,
            'track_name' => $this->trackby->name,
            'make' => $this->machine->make,
            'status' => $this->status,
            'closed_at' => Carbon::parse($this->closed_at)->format('d/m/y'),
            'service_types' => $this->machine->service_types
        ];
    }
}
