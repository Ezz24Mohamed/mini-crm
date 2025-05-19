<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'customer'=>[
                'id'=>$this->customer->id,
                'name'=>$this->customer->name,
                'phone'=>$this->customer->phone,
                'email'=>$this->customer->email,
            ],
            
            'action_type'=>$this->action_type,
            'result'=>$this->result
        ];
    }
}
