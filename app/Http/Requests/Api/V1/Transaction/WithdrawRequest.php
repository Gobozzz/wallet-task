<?php

namespace App\Http\Requests\Api\V1\Transaction;


use App\DTO\Transactions\WithdrawDto;
use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'comment' => 'sometimes|string|max:600',
        ];
    }

    public function getDto(): WithdrawDto
    {
        return new WithdrawDto($this->get('user_id'), $this->get('amount'), $this->get('comment'));
    }

}
