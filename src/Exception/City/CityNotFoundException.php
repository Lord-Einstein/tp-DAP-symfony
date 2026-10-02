<?php

namespace App\Exception\City;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CityNotFoundException extends HttpException
{
    /**
     * Signals a lookup on a city identifier that matches nothing.
     */
    public function __construct()
    {
        parent::__construct(
            Response::HTTP_NOT_FOUND,
            'Ville non trouvée'
        );
    }
}