<?php

namespace App\Http\Requests\Api\V1\Transaction;


use App\DTO\Transactions\TransferDto;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'from_user_id' => 'required|integer|exists:users,id',
            'to_user_id' => 'required|integer|exists:users,id|different:from_user_id',
            'amount' => 'required|numeric|min:0.01',
            'comment' => 'sometimes|string|max:600',
        ];
    }

    public function getDto(): TransferDto
    {
        return new TransferDto(
            $this->get('from_user_id'),
            $this->get('to_user_id'),
            $this->get('amount'),
            $this->get('comment')
        );
    }

}
