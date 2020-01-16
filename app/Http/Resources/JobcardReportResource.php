<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobcardReportResource extends JsonResource
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
            'requistions' => $this->requisition,           
            'Created' => Carbon::parse($this->created_at)->format('m-d-Y'),
            'Reference' => $this->card_no,
            'Machine' =>  $this->machine->code,
            'service_date' => Carbon::parse($this->actual_date)->format('d-m-Y')

        ];
    }
}
