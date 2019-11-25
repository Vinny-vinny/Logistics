<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignChecklistResource extends JsonResource
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
            'checklist' => $this->checklist->name,
            'card' => $this->jobcard->card_no,
            'jobcard_id' => $this->jobcard_id,
            'checklist_id' => $this->checklist_id,
            'start_date' => $this->start_date,
            'reminder_before' => $this->reminder_before,
            'date_type' => $this->checklist->expirytype->name,
            'counter' => $this->checklist->duration_no
        ];
    }
}
