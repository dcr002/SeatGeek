# SeatGeet

# Instalacion

    $ git clone 
    $ cd SeatGeek/
    $ composer install

## Namespaces

- [SeatGeek\Event](/Doc/Seatgeek.md)

## SeatGeek\Event

### Constructor
Los atributos **\$clientId** y **\$clientSecret** son requeridos en el constructor, se obtienen a través del portal [SeatGeek API KEY](https://seatgeek.com/account/develop), el parámetro  **$format** es **opcional** indica el tipo de respuesta que nos dará la librería,  acepta dos valores **xml ó json**, el valor por defecto es **json**

	<?php
	
	use SeatGeek\Event;
	
	$event;
	
	try {
	
	    $event = new Event($clientId, $clientSecret, $format);
	    
	} catch (Exception $ex) {
	
		Manejo de excepciones
		
	}

## **withId($id = null)**
Consultar un evento por su $id, al usar esta función se debe proveer el id obligatoriamente.

**Retorna**
- Referencia $this

## **pushQuery($key, $value)**	
Agregar parámetros para filtrar por localización 

|$key| $value |
|--|--|
| geoip (opcional)| **Boolean**, IP (Solo en USA y Canadá), <br> Default: **false** |
| range (opcional)| **String** <br> default: "30mi", 12mi para millas ó 12km para kilómetros |
| lat | **Decimal** <br>Requerido  si longitud es usado  |
| lon | **Decimal** <br>Requerido  si latitud es usado |


**Retorna**
- Referencia $this

## **pushPagination($key, $value)**	
Agregar parámetros de paginación 

|$key| $value |
|--|--|
| per_page (opcional)| **string**,<br> Default: **10** |
| page (opcional)| **string** <br> default: "1" |


**Retorna**
- Referencia $this

## **pushSorting($field, $order)**	
Agregar parámetros de ordenamiento

|$field| $order |
|--|--|
| datetime_utc (opcional)| **string**, <br>asc\|desc  |
| datetime_local (opcional)| **string**, <br>asc\|desc |
| announce_date (opcional)| **string**, <br>asc\|desc |
| id (opcional)| **string**, <br>asc\|desc |
| score (opcional)| **string**, <br>asc\|desc |


**Retorna**
- Referencia $this

## **pushFilter($field, $order, $value)**	
Agregar parámetros de filtrado

|$field| $order | $value |
|--|--|--|
| listing_count (opcional)| gt \| lte | **string**, <br>asc\|desc  |
| average_price (opcional)| gt \| lte | **string**, <br>asc\|desc |
| lowwest_price (opcional)| gt \| lte | **string**, <br>asc\|desc |
| highest_price (opcional)| gt \| lte | **string**, <br>asc\|desc |

- **gt:** Mayor que
- **lte:** Menor o igual que.

**Retorna**
- Referencia $this

## **pushScore($value)**	
Agregar parámetros de puntuación, el parámetro **$value** debe ser decimal entre Cero y Uno (0 .00 - 1.00) 

**Retorna**
- Referencia $this

## **pushPartner($field, $value)**	
Agregar parámetros de socio.

|$field| $value |
|--|--|
| aid (opcional)| **integer** |
| rid (opcional)| **integer** |

**Retorna**
- Referencia $this

## **requestAndResponse()**	
Función que organiza los datos que pasan por la url y ejecuta la petición hacia el api de seatgeek.

**Retorna**
- Estructura JSON o XML según se haya instanciado el constructor en el parameto **$format**