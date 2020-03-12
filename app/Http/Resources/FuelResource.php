<?php

namespace App\Http\Resources;

use App\FuelSupplier;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FuelResource extends JsonResource
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
            'litres' => $this->litres,
            'vehicle_id' => $this->vehicle_id,
            'customer_id' => $this->customer_id,
            'expense_id' => $this->expense_id,
            'vehicle' => $this->vehicle ? $this->vehicle->code :'',
            'fuel_type_id' => $this->fuel_type_id,
            'fuel_type' => $this->fueltype->description,
            'fuel_on' => $this->fuel_on,
            'date_fueled' => Carbon::parse($this->fuel_on)->format('d/m/Y'),
            'rate' => $this->rate,
            'status' => $this->status,
            'customer_type_id' => $this->customer_type_id,
            'asset_type' => $this->asset_type,
            'requested_by' => $this->requested_by,
            'person_authorizing' => $this->user->name,
            'job_card_id' => $this->job_card_id,
            'authorized_by' => $this->authorized_by,
            'odometer_readings' => $this->odometer_readings,
            'track_by' => $this->vehicle ? $this->vehicle->track->name : '',
            'current_readings' => $this->vehicle ? $this->vehicle->current_readings :'',
            'previous_odometer' => $this->previous_odometer,
            'store_man' => $this->store_man,
            'fuel_no' => $this->fuel_no,
            'asset_category_id' => $this->asset_category_id,
            'external_reference' => $this->external_reference,
            'fuel_category_id' => $this->fuel_category_id,
            'credit_account_id' => $this->credit_account_id,
            'where_to_charge' => $this->where_to_charge,
            'reversal_ref' => $this->reversal_ref,
            'reversal_litres' => $this->reversal_litres,
            'reversal_rate' => $this->reversal_rate,
            'invoiced' => $this->invoiced
             ];
    }
}
