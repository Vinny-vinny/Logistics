<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistResource extends JsonResource
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
            'expiry_type_id' => $this->expiry_type_id,
            'expiry_type' => $this->expirytype->name,
            'duration_no' => $this->duration_no,
            'files' => $this->checklist_files,
            'name' => $this->name,
            'checklists' => $this->checklists,
            'duration_type' => $this->expirytype->name,
        ];
    }
}
