<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class RequisitionResource extends JsonResource
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
            'requested_on' => $this->requested_on,
            'date_requested' => Carbon::parse($this->requested_on)->format('d/m/y'),
            'description' => $this->description,
            'where_to_charge' => $this->where_to_charge,
            'project_id' => $this->project_id,
            'project' => $this->project->code,
            'requested_by' => $this->requested_by,
            'person_requested' => $this->user->name,
            'inventory_items' => $this->inventory_items,
            'used' => $this->used,
        ];
    }
}
