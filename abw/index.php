<?php
    header( "content-type: text/xml; charset=utf-8" );
    require_once '../Database.class.php';
    $database = new Database();

    $rawCarData = $database->selectAllCarRows();
    buildXmlDocument($rawCarData);


    function buildXmlDocument($carsData){
        $xml = new DOMDocument("1.0", "UTF-8");
        $xml->formatOutput = true;

        $catalogNode = createCatalogNode($xml);
        $catalogNode->appendChild(createOffersNode($xml, $carsData));

        $xml->appendChild($catalogNode);
        echo $xml->saveXML();

    }


    function createCatalogNode($xml){
        $autoCatalogNode = $xml->createElement('auto-catalog');

        $creationDateNode = $xml->createElement('creation-date', getCurrentDateString());
        $autoCatalogNode->appendChild($creationDateNode);

        $hostNode = $xml->createElement('host', 'selection.renault.by');
        $autoCatalogNode->appendChild($hostNode);

        return $autoCatalogNode;
    }

    function createOffersNode($xml, $carsData){
        $offersNode = $xml->createElement('offers');
        foreach ($carsData as $car){
            $singleOfferNode = $xml->createElement('offer');
            $singleOfferNode->setAttribute("type", "private");

            $singleOfferNode = appendRegularOfferNodes($xml, $singleOfferNode, $car);
            $singleOfferNode = appendEquipmentTypes($xml, $singleOfferNode, $car);

            $offersNode->appendChild($singleOfferNode);
        }

        return $offersNode;
    }

    function appendRegularOfferNodes($xml, $singleOfferNode, $car){
        $singleOfferNode->appendChild($xml->createElement('mark', $car['_brand']));
        $singleOfferNode->appendChild($xml->createElement('model', $car['_model']));
        $singleOfferNode->appendChild($xml->createElement('year', $car['_year']));
        $singleOfferNode->appendChild($xml->createElement('body-type', $car['_kuzov']) );
        $singleOfferNode->appendChild($xml->createElement('doors-count', $car['_dveri']) );
        $singleOfferNode->appendChild($xml->createElement('engine-type', $car['_engine_type'] ));
        $singleOfferNode->appendChild($xml->createElement('displacement', round($car['_engine_obem']*1000) ));
        $singleOfferNode->appendChild($xml->createElement('gear-type', $car['_privod']) );
        $singleOfferNode->appendChild($xml->createElement('transmission', $car['_transmissiya']) );
        $singleOfferNode->appendChild($xml->createElement('state', $car['_sostoyanie']) );
        $singleOfferNode->appendChild($xml->createElement('run', $car['_probeg']) );
        $singleOfferNode->appendChild($xml->createElement('run-metric', 'км') );
        $singleOfferNode->appendChild($xml->createElement('seller', 'Официальный дилер Рено') );
        $singleOfferNode->appendChild($xml->createElement('seller-city', $car['_addres_city']));
        $singleOfferNode->appendChild($xml->createElement('seller-phone', $car['_phone']));
        $singleOfferNode->appendChild($xml->createElement('price', $car['_price']*10000));
        $singleOfferNode->appendChild($xml->createElement('currency-type', 'BYR'));
        $singleOfferNode->appendChild($xml->createElement('haggle', 'Невозможен'));
        $singleOfferNode->appendChild($xml->createElement('custom-house-state', 'Растаможен'));
        $singleOfferNode->appendChild($xml->createElement('color', $car['_colour_body']) );
        $singleOfferNode->appendChild($xml->createElement('image', $car['_img_url1']) );
        $singleOfferNode->appendChild($xml->createElement('additional-info', $car['_dop_info']) );

        return $singleOfferNode;
    }

    function appendEquipmentTypes($xml, $node, $car){
        $equipmentTypes = require_once './equipmentTypes.php';

        foreach ($equipmentTypes as $equipmentKey=>$equipment){
            if ($car[$equipmentKey] == '1'){
                $node->appendChild($xml->createElement('equipment', $equipment) );
            }
        }
        return $node;

    }

    function getCurrentDateString(){
        $timezoneOffset = "GMT+3";
        date_default_timezone_set('Europe/Minsk');
        return date('Y-m-d H:i:s ').$timezoneOffset;
    }
