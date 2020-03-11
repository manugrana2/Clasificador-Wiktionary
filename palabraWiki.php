<?php
    error_reporting(E_ALL);
ini_set('display_errors', 1);
include_Once('wikiStr.php');
function palabraWiki($palabra){
    $palabra = mb_strtolower($palabra);
   $doc = new DomDocument();
   libxml_use_internal_errors(true);
   if($doc->loadHTMLFile("https://es.wiktionary.org/wiki/$palabra")){
   $xPath = new DomXpath($doc);
   $infoEspañol = $xPath->query("//li[contains(@class, 'toclevel-1')]");

   /*comprobar si info español contiene toclevel-2, lo que significa que la palabra tiene diferentes usos
   */
   if(count($infoEspañol) > 0){
    #Del sumario seleccionar el 1er 'ul'  y aveririguar si es español
    $idioma = mb_strtolower($infoEspañol->item(0)->getElementsByTagName('a')->item(0)->nodeValue);
    $entidad = $infoEspañol->item(0);
    if(strstr($idioma, "español")){  
    #Verificar si tiene sólo un segundo nivel de navegación
    $query = "./li[@class='toclevel-2']/ul/li/a/span[@class='toctext']";
    $entries = $xPath->query($query, $entidad);

    if(count($entries) > 0){
  foreach ($entries as $infoEspañol) {   
  }

    }else{
      #comprobar si el menú tiene un tercer nivel
      $propiedades = array("funcion" => array());
      $query = './/li[contains(@class, "toclevel-3")]';
    $entries = $xPath->query($query, $entidad);
    if(count($entries) > 0){

    foreach ($entries as $level3item) {
      $query = ".//span[@class='toctext']";
    $nodeList = $xPath->query($query, $level3item);
    $info = wikiSumario($nodeList, $xPath, $propiedades, $palabra);
    if(count($info) > 0){
      $propiedades = $info;
    }
    }
     if(array_key_exists('adjetivo', $propiedades['funcion']) || array_key_exists('sustantivo', $propiedades['funcion'])):
      $propiedades = inflexTable($xPath, $propiedades, $palabra);
    endif;

              
  }else{ 
    #Tiene dos niveles de navegación
         $entries = $infoEspañol->item(0);
            $query = ".//span[@class='toctext']";
    $nodeList = $xPath->query($query, $entries);
     $propiedades = wikiWordFunction($nodeList, $xPath);
     if(array_key_exists('adjetivo', $propiedades['funcion']) || array_key_exists('sustantivo', $propiedades['funcion'])):
     $propiedades = inflexTable($xPath, $propiedades, $palabra);
    endif;      
    }

    }
  }   
  }else{
   # No tiene tabla de sumarización
   # Averiguar si es la forma plural de un sustantivo
   $nodeList = $xPath->query("//span[contains(@class, 'mw-headline')]");
   $propiedades = wikiWordFunction($nodeList, $xPath);

}
}
return $propiedades;
} 
?>
