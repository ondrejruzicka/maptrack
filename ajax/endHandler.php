<?php
/*
  Skript pro logování konce relace - přidá odpověď na otázku
  rozhoduje se podle souboru config.php, kam bude zapisovat jestli do MySQL nebo XML
*/
  include_once('../config.php');


  $event = array(
  'session' => trim(strip_tags($_POST['id'])),
  'answer' => trim(strip_tags($_POST['answer']))
  );


switch($logger) {

case 'xml':


  $path = '../results/';
  $files = glob($path.'/session-'.$event['session'].'*');
  $soubor = $files[0];

  $doc = new DOMDocument();
  $doc->formatOutput = true;
  $doc->preserveWhiteSpace = false;

  if(file_exists($soubor))
  {
  $doc->load($soubor);
  $r = $doc->getElementsByTagName("session")->item(0);


  $answer = $doc->createElement( "answer" );
  $answer->appendChild(
          $doc->createTextNode( $event['answer'] )
  );
  $r->appendChild( $answer );



  $doc->save($soubor);

   }

break;

case 'mysql':



$sql = "UPDATE ".$table_session." SET answer = '".$event['answer']."' WHERE id = ".$event['session'];
mysql_query($sql);
echo $sql;

break;

}

  ?>

