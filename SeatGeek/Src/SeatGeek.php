<?php

namespace SeatGeek;

define("END_POINT", "https://api.seatgeek.com/2/");

class SeatGeek
{
    /**
     * ID CLIENTE
     */
    private const CLIENT_ID = "client_id";
    private const CLIENT_SECRET = "client_secret";

    /**
     * Formato de respuesta
     * Opciones
     *      1- Xml
     *      2- Json
     */
    
    private static $response_format = ["xml", "json"];
    private static $clientId = null;
    private static $secret = null;
    private static $format = "json";

    /**
     * Constructor
     */
    public function __construct(string $clientId, string $secret, string $format = "json")
    {

        self::$clientId = $clientId;
        self::$secret = $secret;

        $this->setFormat($format);
    }

    public function getClientId()
    {
        return self::$clientId;
        
    }

    public function setClientId($clientId)
    {
        self::$clientId = $clientId;
    }

    public function getSecret()
    {
        return self::$secret;
    }

    public function setSecret($secret)
    {
        self::$secret = $secret;
    }
    
    public function setFormat($format)
    {

        $format = strtolower($format);

        if(!in_array($format, self::$response_format))
        {
            throw new \Exception("Formato ingresado es inválido");
        }

        self::$format = $format;
    }

    public function getFormat()
    {
        return self::$format;
    }
    
}