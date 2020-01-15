<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequisitionReportResource extends JsonResource
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
            'date_requested' => Carbon::parse($this->requested_on)->format('d/m/Y'),
            'description' => $this->description,
            'where_to_charge' => $this->where_to_charge,
            'project_id' => $this->project_id,
            'project' => $this->project ? $this->project->name : '',
            'requested_by' => $this->requested_by,
            'person_requested' => $this->user->name,
            'inventory_items_internal' => $this->inventory_items_internal,
            'inventory_items_external' => $this->inventory_items_external,
            'group_name' => $this->group_name,
            'type' => $this->type,
            'used' => $this->used,
            'req_no' => $this->req_no,
            'user' => $this->user->name
        ];

       
    }
}
