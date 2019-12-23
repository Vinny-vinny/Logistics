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
             'Date' => $this->id,
             'Item Code' => $this->id,
             'Item Description' => $this->id,
             'Reference' => $this->id,
             'Quantity' => $this->id,
             'Unit Cost' => $this->id,
             'Amount' => $this->id,
             'Project' => $this->id,

        ];
    }
}
