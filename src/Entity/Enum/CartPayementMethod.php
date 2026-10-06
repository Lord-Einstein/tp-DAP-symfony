<?php

namespace App\Entity\Enum;

enum CartPayementMethod: string
{
    case CARD = 'card';
    case VOUCHER = 'voucher';

}
