<?php

namespace App\Exception\Cart;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CartNotFoundException extends HttpException
{
    public function __construct(){
        parent::__construct(
            Response::HTTP_NOT_FOUND,
            'Panier introuvable/inexistant.',
        );
    }
}
