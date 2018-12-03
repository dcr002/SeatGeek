<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

use SeatGeek\Event;
use SeatGeek\SeatGeek;

    try{
        
        $seat = new SeatGeek("MTQwMzI1MzB8MTU0MzU0NjUxNS40Mw","8e58a312e92f8e7f832ba783af3f51f19eb3a9b8040b56feae22372df9b1cc30");

        $seat->setFormat("json");

        $event = new Event($seat);

        $event->pushPagination("per_page", "25")->pushPagination("page", "5");

        $result = $event->requestAndResponse(true);

        echo "TIPO DE RESPUESTA: " . gettype($result) . "\n";

        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
    catch (Exception $e){
        print_r($e->getMessage());
    }

