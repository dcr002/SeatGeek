<?php

use SeatGeek\SeatGeek;

class Performers extends SeatGeek {

	private $seat = null;
	private $query = null;

	public function __construct(string $clientId, string $secret, string $format = "json")
    {

        if( is_null($clientId) || is_null($secret))
            throw new \Exception("Debe proveer parámetros de autenticación.");

        $this->seat = parent::__construct($clientId, $secret, $format);

        $this->query = ["client_id" => $clientId, "client_secret" => $secret, "format" => $format];

    }

    

}  