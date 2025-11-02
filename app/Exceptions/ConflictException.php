<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConflictException extends Exception
{
    protected $message;
    protected $code;

    public function __construct(string $userMessage = 'Conflict occurred')
    {
        $this->message = $userMessage;
        $this->code = Response::HTTP_CONFLICT;

        parent::__construct($userMessage, Response::HTTP_CONFLICT);
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], $this->code);
    }

}
