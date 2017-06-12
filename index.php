<?php
    header( "content-type: text/xml; charset=utf-8" );
    require_once './Database.class.php';
    $database = new Database();

    $rawCarData = $database->selectAllCarRows();
    buildXmlDocument($rawCarData);


    function buildXmlDocument($data){
        $xml = new DOMDocument("1.0", "UTF-8");
        $xml->formatOutput = true;

        $xml = buildXmlDocumentHead($xml);


        echo $xml->saveXML();

    }


    function buildXmlDocumentHead($xml){
        $autoCatalogNode = $xml->createElement('auto-catalog');

        $creationDateNode = $xml->createElement('creation-date', getCurrentDateString());
        $autoCatalogNode->appendChild($creationDateNode);

        $hostNode = $xml->createElement('host', 'selection.renault.by');
        $autoCatalogNode->appendChild($hostNode);

        $xml->appendChild($autoCatalogNode);
        return $xml;
    }

    function getCurrentDateString(){
        $timezoneOffset = "GMT+3";
        date_default_timezone_set('Europe/Minsk');
        return date('Y-m-d H:i:s ').$timezoneOffset;
    }