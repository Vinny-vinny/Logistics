<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
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
            'code' => $this->code,
            'description' => $this->description,
            'make' => $this->make,
            'year_of_make' => $this->year_of_make,
            'year_of_reg' => $this->year_of_reg,
            'chasis_no' => $this->chasis_no,
            'engine_no' => $this->engine_no,
            'assign_to_id' => $this->user->name,
            'assign_to' => $this->user->id,
            'track_by_id' => $this->track->id,
            'track_name' => $this->track->name,
            'next_readings' => $this->next_readings,
            'current_readings' => $this->current_readings,
            'warranty' => $this->warranty,
            'fuel_type_id' => $this->fuel_type_id,
            'reminder_before' => $this->reminder_before,
            'status' => $this->status,
            'odometer_readings' => $this->odometer_readings,
            'service_types' => $this->service_types,
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'plate_no' => $this->plate_no,
            'type' => $this->type,
             ];
    }
}
