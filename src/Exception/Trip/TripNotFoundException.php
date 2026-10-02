<?php

namespace App\Exception\Trip;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TripNotFoundException extends HttpException
{
    /**
     * Signals a lookup on a city identifier that matches nothing.
     */
    public function __construct()
    {
        parent::__construct(
            Response::HTTP_NOT_FOUND,
            'Voyage/Trajet non trouvé'
        );
    }
}
