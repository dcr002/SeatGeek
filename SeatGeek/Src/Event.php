<?php

namespace SeatGeek;

class Event extends SeatGeek
{

    private $method = "GET";

    private $path = "events";
    private $seat = null;
    private $param = null;
    private $query = null;
    private $pagination = null;
    private $response = null;
    private $sorting = null;
    private $filter = null;
    private $score = null;
    private $partner = null;

    private $specifity = null;

    private $events_query = [
        "geoip" => "?",   # TRUE | IP CLIENT (IP CLIENT SOLO EN USA Y CANADA)
        "range" => "?",   # 12mi | 18km
        "lat"   => "?",     # GRADOS DECIMALES 42.2711
        "lon"   => "?",     # GRADOS DECIMALES -89.0593
    ];

    private $events_pagination = [
        "per_page" => "?",
        "page" => "?"
    ];

    private $events_sorting = [
        "datetime_local"=> "datetime_local",
        "datetime_utc"  => "datetime_utc",
        "announce_date" => "announce_date",
        "id"            => "id",
        "score"         => "score"
    ];

    private $events_filter = [
        "listing_count" => "listing_count",
        "average_price" => "average_price",
        "lowwest_price" => "lowwest_price",
        "highest_price" => "highest_price",
    ];

    private $events_order = [
        "asc"   => "asc",
        "desc"  => "desc"
    ];

    private $events_filter_order = [
        "lte"   => "lte",   # > mayor que
        "gt"    => "gt"     # <= igual o menor que
    ];

    private $events_partner = [
        "aid" => "aid",
        "rid" => "rid"
    ];

    private $events_specifity = [
        "home_team" => "home_team",
        "home_team" => "away_team",
        "home_team" => "primary",
        "home_team" => "any"
    ];

    private $events_specifity_fields = [
        "" => ""
    ];

    public function __construct(string $clientId, string $secret, string $format = "json")
    {

        if( is_null($clientId) || is_null($secret))
            throw new \Exception("Debe proveer parámetros de autenticación.");

        $this->seat = parent::__construct($clientId, $secret, $format);

        $this->query = ["client_id" => $clientId, "client_secret" => $secret, "format" => $format];

    }

    /**
     * BUSCAR UN EVENTO POR ID
     */
    public function withId($id = null)
    {
        if( (is_null($id)))
            throw new \Exception("Debe proveer un id de evento");

        $this->param = $id;

        return $this;

    }

    ################################### FUNCIONES DE VALIDACION ###################################
    /**
     * VALIDACION DE PARAMETROS
     */
    public function hasParam()
    {
        return !(is_null($this->param));
    }

    /**
     * VALIDACION DE ATRIBUTOS QUERY STRING
     */
    public function hasQuery()
    {
        return count($this->query) != 0;
    }

    /**
     * VALIDACION DE ATRIBUTOS DE PAGINACION
     */
    public function hasPagination()
    {
        return count($this->pagination) != 0;
    }

    /**
     * VALIDACION DE ATRIBUTOS DE ORDENAMIENTO
     */
    public function hasSorting()
    {
        return !is_null(($this->sorting));
    }

    /**
     * VALIDACION DE ATRIUTOS DE FILTRADO
     */

    public function hasFilter()
     {
         return !is_null($this->filter);
     }

     /**
      * VALIDACION DE ATRIBUTO DE PUNTUACION
      */

    public function hasScore()
    {
        return !is_null($this->score);
    }

    /**
     * VALIDACION DE ATRIBUTO PARTNER
     */
    public function hasPartner()
    {
        return count($this->partner) != 0;
    }

    ################################### FUNCIONES DE INSERCION ####################################
    /**
     * INSERTAR ATRIBUTOS DE QUERY STRING
     */
    public function pushQuery($key, $value)
    {

        if(!array_key_exists($key, $this->events_query)){
            throw new \Exception("Parámetro ingresado no permitido");
        }

        $this->query[$key] = str_replace("?", $value, $this->events_query[$key]);

        return $this;
    }

    /**
     * INSERTAR ATRIBUTOS DE PAGINACION
     */
    public function pushPagination($key, $value)
    {
        if(!array_key_exists($key, $this->events_pagination))
            throw new \Exception("Parámetro de paginación ingresado no permitido");

        $this->pagination[$key] = str_replace("?", $value, $this->events_pagination[$key]);

        return $this;
    }

    /**
     * INSERTAR ATRIBUTOS DE ORDENAMIENTO
     */
    public function pushSorting($field, $order)
    {
        if( !is_null($this->sorting)){
            throw new \Exception("Ya existe un parámetro de ordenamiento asignado");
        }

        if( !array_key_exists($field, $this->events_sorting) ){
            throw new \Exception("Parámetro de clasificación ingresado no permitido");
        }

        if( !array_key_exists($order, $this->events_order) ){
            throw new \Exception("Parámetro de ordenamiento ingresado no permitido");
        }

        $this->sorting = "{$field}.{$order}";

        return $this;

    }

    /**
     * INSERTA ATRIBUTOS DE FILTRADO
     */
    public function pushFilter($field, $order, $value)
    {
        if( !is_null($this->filter)){
            throw new \Exception("Ya existe un parámetro de ordenamiento asignado");
        }

        if( !is_numeric($value))
            throw new \Exception("Valor de filtrado debe ser numérico");

        if( !array_key_exists($field, $this->events_filter) ){
            throw new \Exception("Parámetro de filtrado ingresado no permitido");
        }

        if( !array_key_exists($order, $this->events_filter_order) ){
            throw new \Exception("Parámetro de ordenamiento ingresado no permitido");
        }

        $this->filter = "{$field}.{$order}={$value}";

        return $this;
    }

    /**
     * INSERTAR ATRIBUTO DE PUNTUACION
     */
    public function pushScore($value)
    {
        if(!is_null($this->score))
            throw new \Exception("Ya existe un parámetro de ordenamiento asignado");

        if(!is_numeric($value))
            throw new \Exception("Valor de puntuación debe ser numérico");

        if($value > 1 || $value < 0)
            throw new \Exception("Valor de puntuación debe estar entre 0 y 1");

        $this->score = "score={$value}";

        return $this;
    }

    /**
     * INSERTAR ATRIBUTO DE SOCIOS
     */
    public function pushPartner($field, $value)
    {
        if( !array_key_exists($field, $this->events_partner)){
            throw new \Exception("Parámetro de socio ingresado no permitido");   
        }

        $this->partner[$field] = "{$field}={$value}";

        return $this;
    }

    /*################################## FUNCIONES DE RECUPERACION ##################################*/
    /**
     * OBTENER ATRIBUTOS QUERY STRING
     */
    public function getQuery()
    {
        $query = null;
        $index = 0;
        foreach ($this->query as $key => $value) {

            if($index == 0){
                $query .= "{$key}={$value}";
            } else {
                $query .= "&{$key}={$value}";
            }

            $index++;
        }

        return $query;
    }

    /**
     * OBTENER ATRIBUTOS DE PAGINACION
     */
    public function getPagination()
    {
        $pagination = null;
        foreach ($this->pagination as $key => $value)
        {
            $pagination .= "&{$key}={$value}";
        }

        return $pagination;
    }

    /**
     * OBTENER ATRIBUTOS DE ORDENAMIENTO
     */
    public function getSorting()
    {
        $sorting = "";

        if(!is_null($this->sorting))
            $sorting = "&sort={$this->sorting}";

        return $sorting;
    }

    /**
     * OBTENER ATRIBUTOS DE FILTRADO
     */
    public function getFilter()
    {
        $filter = "";

        if(!is_null($this->filter))
            $filter = "&{$this->filter}";

        return $filter;
    }

    /**
     * OBTENER ATRIBUTO DE PUNTUACION
     */
    public function getScore()
    {
        $score = "";

        if(!is_null($this->score))
            $score = "&{$this->score}";

        return $score;
    }

    /**
     * OBTENER ATRIBUTO DE SOCIO
     */
    public function getPartner()
    {
        $partner = "";

        foreach ($this->partner as $key => $value) {
           $partner .= "&{$value}";  
        }

        return $partner;
    }

    #################################### FUNCIONES DE PETICION ####################################
    /**
     * REALIZAR PETICION Y FORMATEAR REPUESTA
     *
     * FORMATOS POSIBLES:
     *      - XML
     *      - JSON
     */
    public function requestAndResponse($returnArray = false)
    {

        $response = null;
        $uri = END_POINT . $this->path;

        try
        {

            if($this->hasParam())
                $uri .= "/{$this->param}";

            $uri .= "?";

            if($this->hasQuery())
                $uri .= $this->getQuery();

            if($this->hasPagination())
                $uri .= $this->getPagination();

            if($this->hasSorting())
                $uri .= $this->getSorting();

            if($this->hasFilter())
                $uri .= $this->getFilter();

            if($this->hasScore())
                $uri .= $this->getScore();

            if($this->hasPartner())
                $uri .= $this->getPartner();

            echo $uri;

            exit;

            $request = \Httpful\Request::get($uri);

            if($this->getFormat() == "json"){

                $response = $request->expectsJson()->send();
                $response = $response->body;

                if($returnArray)
                    $response = json_decode(json_encode($response, true));


            } else {

                $response = $request->expectsXml()->send();
                $response = $response;

            }

        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $response;
    }

}
