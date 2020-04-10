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
            'asset_category_id' => $this->asset_category_id,
            'project' => $this->project ? $this->project->name : '' ,
            'jobcard_category_id' => $this->jobcard_category_id,
            'category' => $this->category ? $this->category->name : '',
            'service_type' => $this->service_type,
            'customer_type_id' => $this->customer_type_id,
            'next_readings' => $this->next_readings,
            'current_readings' => $this->current_readings,
            'previous_readings' => $this->machine ? $this->machine->current_readings:'',
            'next_service_date' => $this->next_service_date,
            'actual_date' => $this->actual_date,
            'start_date' => $this->actual_date ? Carbon::parse($this->actual_date)->format('d/m/Y') : '',
            'complete_date' =>$this->completion_date ? Carbon::parse($this->completion_date)->format('d/m/Y') : '',
            'completion_date' => $this->completion_date,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'maintenance' => json_decode($this->maintenance),
            'card_no' => $this->card_no,
            'standing_fee' => $this->standing_fee,
            'driver' =>$this->machine ? $this->machine->user->name :'',
            'machine' => $this->machine ? $this->machine->description :'',
            'plate_no' =>$this->machine ? $this->machine->plate_no : '',
            'cost_center' => $this->machine ? $this->machine->cost_center : '',
            'mechanic' => $this->mechanic ? $this->mechanic->name :'',
            'track_name' => $this->trackby ? $this->trackby->name :'',
            'make' => $this->machine ? $this->machine->make : '',
            'status' => $this->status,
            'cost_code' => $this->cost_code,
            'costcode' => $this->account ? $this->account->account : '',
            'requisition_id' => $this->requisition_id,
            'inventory_items' => $this->requisition_id ? $this->requisition->inventory_items : '',
            'checklist_assigned' => $this->checklist_assigned,
            'service_types' => $this->machine ? $this->machine->service_types : '',
            'labour_cost' => $this->labour_cost,
            'closed_at' => $this->closed_at,
            'fuel' => $this->fuel ? $this->fuel->id : '',
            'checklist' => $this->checklist ? $this->checklist->id :'',
            'requisitions' => $this->requisition,
            'hours_spent' => $this->hours_spent,
            'inventory_items_reversal' =>  json_decode($this->inventory_items_reversal),
             'reversal_ref' => $this->reversal_ref,
             'invoiced'=> $this->invoiced
        ];
    }
}
