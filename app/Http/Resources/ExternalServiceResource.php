<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExternalServiceResource extends JsonResource
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
            'job_card_id' => $this->job_card_id,
            'service_provider_id' => $this->service_provider_id,
            'service_type' => $this->service_type,
            'description' => $this->description,
            'job_card' => $this->job_card->card_no,
            'vehicle' => $this->job_card->machine->code,
            'make' => $this->job_card->machine->make,
            'supplier' => $this->service_type=='labour' ? $this->service_provider->name : '',
            'labours' => $this->labours,
            'parts' => $this->parts
        ];
    }
}
