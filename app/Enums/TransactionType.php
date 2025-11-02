<?php


namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case TRANSFER_OUT = 'transfer_out';
    case TRANSFER_IN = 'transfer_in';

    public function label(): string
    {
        return match ($this) {
            self::DEPOSIT => 'Пополнение',
            self::WITHDRAW => 'Списание',
            self::TRANSFER_OUT => 'Исходящий перевод',
            self::TRANSFER_IN => 'Входящий перевод',
        };
    }
}