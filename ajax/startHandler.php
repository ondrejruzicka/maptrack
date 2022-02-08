<?php
/*
  Skript pro logování začátku relace
  rozhoduje se podle souboru config.php, kam bude zapisovat jestli do MySQL nebo XML
*/


  include_once('../config.php');


  $event = array(

  'ip' => $_SERVER['REMOTE_ADDR'],
  'id' => trim(strip_tags($_POST['id'])),
  'start' => date('Y-m-d H:i:s', ($_POST['id']/1000)),
  'mapsize' => trim(strip_tags($_POST['div'])),
  'screen' => trim(strip_tags($_POST['screen'])),
  'browser' => trim(strip_tags($_POST['browser'])),
  'os' => trim(strip_tags($_POST['os'])),
  'var' => trim(strip_tags($_POST['var']))

  );


switch($logger) {

case 'xml':

  $doc = new DOMDocument();
  $doc->formatOutput = true;
  $doc->preserveWhiteSpace = false;


  $r = $doc->createElement("session");
  $doc->appendChild( $r );



  // -------------- ID
  $id = $doc->createElement( "id" );
  $id->appendChild(
          $doc->createTextNode( $event['id'] )
  );
  $r->appendChild( $id );


  // ------------- IP
  $ip = $doc->createElement( "ip" );
  $ip->appendChild(
          $doc->createTextNode( $event['ip'] )
  );
  $r->appendChild( $ip );       +

  // ------------- IP
  $var = $doc->createElement( "var" );
  $var->appendChild(
          $doc->createTextNode( $event['var'] )
  );
  $r->appendChild( $var );

  // ------------ TIME
  $time = $doc->createElement( "start" );
  $time->appendChild(
          $doc->createTextNode( $event['start'] )
  );
  $r->appendChild( $time );

  // ------------ DIV
  $div = $doc->createElement( "mapsize" );
  $div->appendChild(
          $doc->createTextNode( $event['mapsize'] )
  );
  $r->appendChild( $div );

  // ------------ SCREEN
  $screen = $doc->createElement( "screen" );
  $screen->appendChild(
          $doc->createTextNode( $event['screen'] )
  );
  $r->appendChild( $screen );


  // ------------ BROWSER
  $browser = $doc->createElement( "browser" );
  $browser->appendChild(
          $doc->createTextNode( $event['browser'] )
  );
  $r->appendChild( $browser );


  // ------------ TIME
  $os = $doc->createElement( "os" );
  $os->appendChild(
          $doc->createTextNode( $event['os'] )
  );
  $r->appendChild( $os );


  // ------------ EVENTS
  $events = $doc->createElement( "events" );
  $r->appendChild( $events );


  $doc->save("../results/session-".$event['id']."-".$event['var'].".xml");

break;

case 'mysql':

$keys = implode(', ',array_keys($event));
$values = "'" .implode("','",$event)."'";

$sql = "INSERT INTO ".$table_session." (".$keys.") VALUES (".$values.")";
mysql_query($sql);
break;


  }


  ?>

