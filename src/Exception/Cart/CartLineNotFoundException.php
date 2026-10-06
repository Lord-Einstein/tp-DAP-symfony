<?php

namespace App\Exception\Cart;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartLineNotFoundException extends HttpException
{
    public function __construct(){
        parent::__construct(
            Response::HTTP_NOT_FOUND,
            'Ligne de panier introuvable.',
        );
    }
}
