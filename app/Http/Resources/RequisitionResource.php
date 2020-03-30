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
            'date_requested' => Carbon::parse($this->requested_on)->format('d/m/Y'),
            'description' => $this->description,
            'where_to_charge' => $this->where_to_charge,
            'project_id' => $this->project_id,
            'project' => $this->project ? $this->project->name : '',
            'vehicle' => $this->vehicle ? $this->vehicle->description : '',
            'requested_by' => $this->requested_by,
            'person_requested' => $this->user->name,
            'inventory_items_internal' => json_decode($this->inventory_items_internal),
            'inventory_items_external' => json_decode($this->inventory_items_external),
            'group_name' => $this->group_name,
            'type' => $this->type,
            'used' => $this->used,
            'req_no' => $this->req_no,
            'user' => $this->user->name,
            'external_reference' => $this->external_reference,
            'person_collecting' => $this->person_collecting,
            'customer_id' => $this->customer_id,
            'subproject_id' => $this->subproject_id,
            'reversal_ref' => $this->reversal_ref,
            'inventory_items_reversal' => json_decode($this->inventory_items_reversal),
            'credit_account_id' => $this->credit_account_id
        ];
    }
}
