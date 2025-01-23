<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "applicant" => new UserResource($this->whenLoaded('applicant')), // Formatear relación con UserResource
        "approver" => new UserResource($this->whenLoaded('approver')),
            "message_applicant"=> $this->message_applicant,
            "message_approver"=> $this->message_approver,
            "status"=> $this->status ?? 'pending',
            "start_date"=> $this->start_date_Formatted,
            "end_date"=> $this->end_date_Formatted,
            "created_at"=> $this->created_at,
            "updated_at"=> $this->updated_at,
        ];
    }
}
