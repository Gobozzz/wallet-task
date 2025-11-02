<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'user' => new BaseUserResource($this->user),
            'to_user' => new BaseUserResource($this->relatedUser),
            'type' => $this->type->label(),
            'amount' => $this->amount,
            'comment' => $this->comment,
            'balance' => $this->user->balance,
        ];
    }
}
