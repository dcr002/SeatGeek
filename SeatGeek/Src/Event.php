<?php

namespace SeatGeek;

class Event extends SeatGeek
{

    private $method = "GET";
    /**
     * Constructor
     */

    private $path = "events";
    private $seat = null;
    private $param = null;
    private $query = null;
    private $pagination = null;
    private $response = null;

    private $events_query = [
        "geoip" => "?",   # TRUE / IP CLIENT (IP CLIENT SOLO EN USA Y CANADA)
        "range" => "?",   # 12mi
        "lat"   => "?",     # GRADOS DECIMALES 42.2711
        "lon"   => "?",     # GRADOS DECIMALES -89.0593
    ];

    private $events_pagination = [
        "per_page" => "?",
        "page" => "?"
    ];

    private $events_sorting = [
        
    ]; 

    public function __construct($seat = null)
    {
        if( !($seat instanceof \SeatGeek\SeatGeek))
            throw new \Exception("El constructor solo acepta un parametro de tipo \SeatGeek\SeatGeek");
            
        $this->seat = $seat;
        $this->query = [
            "client_id" => $seat->getClientId(),
            "client_secret" => $seat->getSecret(),
            "format" => $seat->getFormat()
        ];

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

    ################################## FUNCIONES DE RECUPERACION ##################################
    /**
     * OBTENER ATRIBUTOS QUERY STRING
     */
    public function getQuery()
    {
        $query = null;
        $index = 0;
        foreach ($this->query as $key => $value) {
        
            if($index == 0)
                $query .= "?";

            $query .= "{$key}={$value}&";
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
            $pagination .= "{$key}={$value}&";
        }

        return $pagination;
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
            
            if($this->hasQuery())
                $uri .= $this->getQuery();

            if($this->hasPagination())
                $uri .= $this->getPagination();

            var_dump($uri); 

            $request = \Httpful\Request::get($uri);
            
            if($this->seat->getFormat() == "json"){

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