<!-- Koboyoda Arthur Assima - Fouad Medjahed -->
<?php

	class ParmsException extends Exception{};

	const METHOD = INPUT_GET;
	const CARBUS = ["Gazole","SP95","E85","GPLc","E10","SP98"];


	 function checkEnum($name, $values, $default = NULL){
		 $res = filter_input(METHOD, $name,FILTER_UNSAFE_RAW);
		 if ($res === NULL)
		 	if (is_null($default))
				throw new ParmsException("param $name : valeur absente");
		  else
		 		$res = $default;
		else if (! in_array($res, $values)) {
		 	throw new ParmsException("param $name : valeur indefinie");
		 }
		return $res;
	 }

   function checkString($name){
     $res = filter_input(METHOD,$name,FILTER_SANITIZE_STRING);
     /*if ($res === NULL)
        throw new ParmsException("param $name : valeur indefinie");*/
     return $res;
   }

   function checkNonEmptyString($name){
     $res = filter_input(METHOD,$name,FILTER_SANITIZE_STRING);
     if ($res === NULL || $res === '' )
        throw new ParmsException("param $name : valeur absente");
     return $res;
   }

   function checkRayon($name){
     $res = filter_input(METHOD,$name,FILTER_VALIDATE_INT);
     return $res;
   }

		$commune=checkNonEmptyString('ville');
		$rayon=checkRayon('rayon');
    $carburants = filter_input(METHOD, 'carburants', FILTER_UNSAFE_RAW, array('flags'=>FILTER_REQUIRE_ARRAY));
		if ($carburants === NULL)
		      throw new ParmsException("param carburants : valeur absente");
		foreach ($carburants as $k=>$v) {
		  if (!in_array($v, CARBUS))
		    throw new ParmsException("param carburants : valeur incorrecte");
		}


?>
