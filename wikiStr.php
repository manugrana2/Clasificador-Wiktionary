<?php

function noEspacio($value){
        $value =  str_replace(array("c2a0"), "", bin2hex("$value")); 
        $value = hex2bin("$value");
        $value = str_replace(" ", '', $value);
        $value = mb_strtolower($value);
        $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
$value = strtr( $value, $unwanted_array );
        return $value;
}
  function inflexTable($xPath, $propiedades, $palabra){
        $query = "//table[@class='inflection-table']";
        $tabla = $xPath->query($query);
        if(count($tabla) > 0){
          $tra = $tabla->item(0)->getElementsByTagName('tbody')->item(0)->getElementsByTagName('tr');
          $nrTr = count($tra);
          $uso = array();
          if($nrTr == 2){
             $nro = $tra->item(0)->getElementsByTagName('th');
               $i =0;
            foreach ($nro as $tr) {
              $str = trim($tr->nodeValue);
            if($str !=null){
              $nr[$i] = mb_strtolower(trim($tr->nodeValue)); 
            $i++;

             }
            }
            $singular = $tra->item(1)->getElementsByTagName('td')->item(0)->nodeValue;
             $singular = trim(mb_strtolower($singular));
             $uso[$singular]['numero'] = [$nr[0]];

             $plural = $tra->item(1)->getElementsByTagName('td')->item(1)->nodeValue;
             $plural = trim(mb_strtolower($plural));
             if($plural){
             $uso[$plural]['numero'] = [$nr[1]];
           }

          }
          elseif($nrTr == 3){
            #nro
            $nro = $tra->item(0)->getElementsByTagName('th');
            $i =0;
            foreach ($nro as $tr) {
              $str = trim($tr->nodeValue);
            if($str !=null){
              $nr[$i] = mb_strtolower(trim($tr->nodeValue)); 
            $i++;

             }
            }
            # Item genero masculino
             $genero = $tra->item(1)->getElementsByTagName('th')->item(0)->nodeValue;
             $genero = trim(mb_strtolower($genero));
             
             #Item género masculino>Singular

             $singular = $tra->item(1)->getElementsByTagName('td')->item(0)->nodeValue;
             $singular = trim(mb_strtolower($singular));
             $uso[$singular]['genero'] = [$genero];
             $uso[$singular]['numero'] = [$nr[0]];

             #Item genero masculino>Plural 
             $plural = $tra->item(1)->getElementsByTagName('td')->item(1)->nodeValue;
             $plural = trim(mb_strtolower($plural));
             $uso[$plural]['genero'] = [$genero];
             $uso[$plural]['numero'] = [$nr[1]];

              # Item genero Femenino
             $genero = $tra->item(2)->getElementsByTagName('th')->item(0)->nodeValue;
             $genero = trim(mb_strtolower($genero));
             
             #Item género Femenio>Singular

             $singular = $tra->item(2)->getElementsByTagName('td')->item(0)->nodeValue;
             $singular = trim(mb_strtolower($singular));
             $uso[$singular]['genero'] = [$genero];
             $uso[$singular]['numero'] = [$nr[0]];

             #Item genero Femenino>Plural 
             $plural = $tra->item(2)->getElementsByTagName('td')->item(1)->nodeValue;
             $plural = trim(mb_strtolower($plural));
             $uso[$plural]['genero'] = [$genero];
             $uso[$plural]['numero'] = [$nr[1]];
          

            }
          }
           foreach ($uso as $word => $propiedad) {
                   foreach ($propiedad as $propiedad => $value) {
               $value = noEspacio($value[0]);

               if($value == 'singulariatantum'){
                $value = 'singular';
               }
               if($word == $palabra){
                if($propiedad == 'numero' or $propiedad == 'genero'){
                  foreach ($propiedades['funcion'] as $funcion => $x) {
                    if($funcion == 'sustantivo' or $funcion == 'adjetivo'){
                      $propiedades['funcion'][$funcion][$propiedad][$value]['puntuacion'] = 0.7;
                    }
                  }
                }
               }
             }

           }

 return $propiedades;

          }
          
      
 function wikiDef($xPath, $propiedades, $query, $funcion){

       if(!isset($query)){
        $query = "//span[@class='definicion-impropia']";
        $entries = $xPath->query($query);
        if(count($entries) > 0){
          $def = $entries->item(0)->nodeValue;
       $def =  str_replace(array("c2a0"), "", bin2hex("$def")); 
        $def = hex2bin("$def");
        $def = str_replace(" ", '', $def);
        $def = mb_strtolower($def);
        if($def == "formadelpluralde"){
          $propiedades['funcion'][$funcion]['numero']['plural']['puntuacion'] = 0.68;
        

                }
        elseif($def == "formadelfemeninopluralde"){
           $propiedades['funcion'][$funcion]['genero']['femenino']['puntuacion']=0.63;
        $propiedades['funcion'][$funcion]['numero']['plural']['puntuacion'] = 0.63;
        }
                           
          }
      }
          return $propiedades;

                                   }


function wikiWordFunction($nodeList, $xPath){
            $propiedades = array("funcion" => array());
            $es=0; 
      foreach ($nodeList as $infoEspañol) {   
        $valorg = $infoEspañol->nodeValue;
          $valor = str_replace(" ", "", $infoEspañol->nodeValue);
      $valor = mb_strtolower($valor, 'UTF-8');
      $valor = str_replace("c2a0", "", bin2hex($valor));
      $valorg =  str_replace("c2a0", "20", bin2hex($valorg));
      $valorg = hex2bin($valorg);
      $valor = noEspacio(hex2bin($valor));
      # Comprobar si es  una palabra en español
       if($es==0 & $valor=='espanol'){
         $es=true;
       }elseif($es==true & ($valor == 'formasustantivamasculina' || $valor == 'sustantivomasculino' || $valor =='formasustantiva')){  
        $funcion ="sustantivo";
        $propiedades['funcion']['sustantivo']['puntuacion'] = 0.5 ;
        if($valor!='formasustantiva'){
        $propiedades['funcion']['sustantivo']['genero']['masculino']['puntuacion']=0.62;
      }else{
       $propiedades['funcion']['sustantivo']['genero']['masculino']['puntuacion']=0.32;
      }
        $propiedades['funcion']['sustantivo']['numero']['singular']['puntuacion'] = 0.32;
              $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);
       }elseif($es==true & ($valor=='formasustantivafemenina' || $valor == 'sustantivofemenino')){
        $funcion ="sustantivo";
        $propiedades['funcion']['sustantivo']['puntuacion'] = 0.5 ;
        $propiedades['funcion']['sustantivo']['genero']['femenino']['puntuacion']=0.63;
        $propiedades['funcion']['sustantivo']['numero']['singular']['puntuacion'] = 0.32;
              $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);
       }elseif($es==true & $valor=='formaverbal' || strpos($valor, 'verbo')){
          $funcion = 'verbo';
      $propiedades['funcion'][$funcion]['puntuacion']= 0.5;

       }elseif ($es == true  & ($valor == "adjetivo" || $valor=='formaadjetiva' || strpos($valor, "adjetivo"))){
          
           $funcion = 'adjetivo';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);
      }elseif(($es == true)  & (strpos($valor, 'adverbio'))){
           $funcion = 'adverbio';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);
      }elseif(($es == true)  & (strpos($valor, 'adverbio'))){
           $funcion = 'adverbio';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

      }elseif(($es == true) & (strpos($valor, 'articulo'))){
           $funcion = 'articulo';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

      }elseif(($es == true)  & (strpos($valor, 'pronombre'))){
           $funcion = 'pronombre';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

      }elseif(($es == true) & (strpos($valor, 'preposicion'))){
           $funcion = 'preposicion';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

      }

          

           $valorg = explode(" ", $valorg);
       
           $strtype = noEspacio(trim(mb_strtolower($valorg[0])));
           if(count($valorg) == 2 || count($valorg) == 3){            
            if($strtype == 'adverbio'  || $strtype == 'articulo' || $strtype == 'adjetivo' || $strtype == 'pronombre'){
            $funcion = $strtype;
            if(count($valorg) == 2){
              $tipo = noEspacio(mb_strtolower($valorg[1]));
             }else{$tipo = noEspacio(mb_strtolower($valorg[2]));}
             $propiedades['funcion'][$funcion]['tipo'][$tipo]['puntuacion']= 0.65;
                     }
                   }
       }
              # Veamos si tiene una definicion 'forma plural de';
       $es = true;
      
  return $propiedades;
  }
          
 

 


function wikiSumario($nodeList, $xPath, $propiedades, $palabra){
      foreach ($nodeList as $infoEspañol) {   
          $valor = str_replace(" ", "", $infoEspañol->nodeValue);
      $valor = mb_strtolower($valor, 'UTF-8');
      $valor =  str_replace(array("c2a0"), "", bin2hex("$valor")); 
      $valor = hex2bin($valor);
      $valorg = $infoEspañol->nodeValue;
      $valorg =  str_replace("c2a0", "20", bin2hex($valorg));
      $valorg = hex2bin($valorg);

       if($valor == 'formasustantivamasculina' || $valor =="sustantivomasculino" || $valor =='formasustantiva'){
         $funcion = 'sustantivo';
         $propiedades['funcion']['sustantivo']['puntuacion'] = 0.5 ;
         if($valor!='formasustantiva'){
        $propiedades['funcion']['sustantivo']['genero']['masculino']['puntuacion']=0.62;
      }else{
       $propiedades['funcion']['sustantivo']['genero']['masculino']['puntuacion']=0.32;
      }
            $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

        $propiedades['funcion']['sustantivo']['numero']['singular']['puntuacion'] = 0.32;

       }elseif($valor=='formasustantivafemenina' || $valor=='sustantivofemenino'){
        $funcion = 'sustantivo';
       $propiedades['funcion']['sustantivo']['puntuacion'] = 0.5 ;
        $propiedades['funcion']['sustantivo']['genero']['femenino']['puntuacion']=0.63;
        $propiedades['funcion']['sustantivo']['numero']['singular']['puntuacion'] = 0.32;
              $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

       }elseif($valor=='formaverbal' || strpos($valor, 'verbo')){
          $funcion = 'verbo';
      $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
       }elseif ($valor == "adjetivo" || $valor=='formaadjetiva') {
      $funcion = 'adjetivo';
      $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
       }elseif(strpos($valor, 'adverbio')){
           $funcion = 'adverbio';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);
      }elseif(strpos($valor, 'adverbio')){
           $funcion = 'adverbio';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

      }elseif(strpos($valor, 'articulo')){
           $funcion = 'articulo';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

      }elseif(strpos($valor, 'pronombre')){
           $funcion = 'pronombre';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);
      }elseif(strpos($valor, 'preposicion')){
           $funcion = 'preposicion';
           $propiedades['funcion'][$funcion]['puntuacion']= 0.5;
           $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);

      }

          

           $valorg = explode(" ", $valorg);
       
           $strtype = noEspacio(trim(mb_strtolower($valorg[0])));
           if(count($valorg) == 2 || count($valorg) == 3){            
            if($strtype == 'adverbio'  || $strtype == 'articulo' || $strtype == 'adjetivo' || $strtype == 'pronombre'){
            $funcion = $strtype;
            if(count($valorg) == 2){
              $tipo = noEspacio(mb_strtolower($valorg[1]));
             }else{$tipo = noEspacio(mb_strtolower($valorg[2]));}
             $propiedades['funcion'][$funcion]['tipo'][$tipo]['puntuacion']= 0.65;
                    $propiedades =  wikiDef($xPath, $propiedades, null, $funcion);
                     }
                   }      
  }


  return $propiedades;
}

  ?>
