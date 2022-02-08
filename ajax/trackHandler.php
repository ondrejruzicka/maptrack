<?php
/*
  Skript pro logování jednotlivých interakcí
  rozhoduje se podle souboru config.php, kam bude zapisovat jestli do MySQL nebo XML
*/
  include_once('../config.php');

  // pokud nějaká interakce používá proměnnou value
  if(isSet($_POST['value']))
    {
    $value = trim(strip_tags($_POST['value']));
    }
    else {
    $value=0;
    }


  // ošetření a naplnění vstupních proměnných
  $event = array(
  'session' => trim(strip_tags($_POST['id'])),
  'time' => trim(strip_tags($_POST['time'])),
  'name' => trim(strip_tags($_POST['name'])),
  'value' => $value,
  'center' => trim(strip_tags($_POST['center'])),
  'zoom' => trim(strip_tags($_POST['zoom']))
  );


// na základě konfigurace použije XML nebo MySQL
switch($logger) {


case 'xml':

  //$soubor = "../results/session-".$event['session'].".xml";
  $path = '../results/';
  $files = glob($path.'/session-'.$event['session'].'*');
  $soubor = $files[0];

  $doc = new DOMDocument();
  $doc->formatOutput = true;
  $doc->preserveWhiteSpace = false;

  if(file_exists($soubor))
  {
  $doc->load($soubor);
  $r = $doc->getElementsByTagName("events")->item(0);



  $b = $doc->createElement( "event" );

  // -------------- TIME -------------
  $time = $doc->createElement( "time" );
  $time->appendChild(
          $doc->createTextNode( $event['time'] )
  );
  $b->appendChild( $time );

  // -------------- NAME -------------
  $name = $doc->createElement( "name" );
  $name->appendChild(
        $doc->createTextNode( $event['name'] )
        );
  $b->appendChild( $name );

  // -------------- VALUE -------------
  $value = $doc->createElement( "value" );
  $value->appendChild(
        $doc->createTextNode( $event['value'] )
        );
  $b->appendChild( $value );

  // -------------- CENTER -------------
  $center = $doc->createElement( "center" );
  $center->appendChild(
          $doc->createTextNode( $event['center'] )
           );
  $b->appendChild( $center );


  // -------------- ZOOM -------------
  $zoom = $doc->createElement( "zoom" );
  $zoom->appendChild(
          $doc->createTextNode( $event['zoom'] )
           );
  $b->appendChild( $zoom );



  $r->appendChild($b);


  $doc->save($soubor);

   }

break;

case 'mysql':

$keys = implode(', ',array_keys($event));
$values = "'" .implode("','",$event)."'";

$sql = "INSERT INTO ".$table_event." (".$keys.") VALUES (".$values.")";
mysql_query($sql);
echo $sql;
break;

}

  ?>

