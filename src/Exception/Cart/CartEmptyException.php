<?php

namespace App\Exception\Cart;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartEmptyException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            Response::HTTP_CONFLICT,
            message: 'Le Panier est vide.',
        );
    }
}
