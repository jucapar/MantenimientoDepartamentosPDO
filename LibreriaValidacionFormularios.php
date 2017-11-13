<?php

	/*
		Juan Carlos Pastor Regueras
		Recorrer el array anterior utilizando funciones para obtener el mismo resultado.
		Fecha revision: 19-10-2017
		
		Cada una estas funciones devolverá	
		0: Si el valor introducido es correcto
		1: Si el valor introducido es vacio
		2: Si el valor introducido no es valido
		3: Si el tamaño del campo es menor al establecido
		4: Si el tamaño del campo es mayot al establecido
	
	*/
	function limpiarCampos($valor) {
		htmlspecialchars(strip_tags(trim($valor)));
	}



	function validarCadenaAlfanumerica($valor,$minimo = 3,$maximo = 100){
		limpiarCampos($valor);
		$valida = 0;
		if(empty($valor)){
			$valida = 1;
		}
		else if(strlen($valor) < $minimo){
			$valida = 3;
		}
		
		else if(strlen($valor) > $maximo){
			$valida = 4;
		}
		return $valida;
		
	}

	function validarCadenaAlfabetica($valor,$minimo = 3,$maximo = 100) {
		limpiarCampos($valor);
		$valida = 0;
		$patron_texto = "/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]+$/";
       
		if(empty($valor)) {
			$valida = 1;
		}
		else if(!preg_match($patron_texto, $valor)) {
			$valida = 2;
		}
		else if(strlen($valor) < $minimo){
			$valida = 3;
		}
		
		else if(strlen($valor) > $maximo){
			$valida = 4;
		}
		return $valida;
		
	}

	function validarEntero($valor,$minimo,$maximo){
		limpiarCampos($valor);
		$valida = 0;
		
		if(empty($valor)){
			$valida = 1;
		}
		else if(!filter_var($valor,FILTER_VALIDATE_INT)){
			$valida = 2;
		}
		else if($valor < $minimo){
			$valida = 3;
		}
		
		else if($valor > $maximo){
			$valida = 4;
		}
		return $valida;
		
	}
	
	function validarReal($valor,$minimo,$maximo){
		limpiarCampos($valor);
		$valida = 0;
		if(empty($valor)){
			$valida = 1;
		}
		else if(!filter_var($valor,FILTER_VALIDATE_FLOAT)){
			$valida = 2;
		}
		else if($valor < $minimo){
			$valida = 3;
		}
		
		else if($valor > $maximo){
			$valida = 4;
		}
		return $valida;
	}

	
	function validarBooleano($valor) {
		limpiarCampos($valor);
		$valida = 0;
		if(empty($valor)){
			$valida = 1;
		}
		else if(!filter_var($valor,FILTER_VALIDATE_BOOLEAN)){
			$valida = 2;
		}
		return $valida;
	}

	function validarURL($valor) {
		limpiarCampos($valor);
		$valida = 0;
		if(empty($valor)){
			$valida = 1;
		}
		else if(!filter_var($valor,FILTER_VALIDATE_URL)){
			$valida = 2;
		}
		
		return $valida;
	}
	
	function validarEmail($valor) {
		limpiarCampos($valor);
		$valida = 0;
		if(empty($valor)){
			$valida = 1;
		}
		else if(!filter_var($valor,FILTER_VALIDATE_EMAIL)){
			$valida = 2;
		}
		
		return $valida;
	}
	
	function validarDNI($valor) {
		limpiarCampos($valor);
		$letra = substr($valor, -1);
		$numeros = substr($valor, 0, -1);
		$valida = 0;
		if (empty($valor)){
			$valida = 1;
		}
		else if (!( substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros%23, 1) == $letra && strlen($letra) == 1 && strlen ($numeros) == 8 )){
			$valida = 2;
		}

		return $valida;
	}
	
	function validarTelefono($valor) {
		
		
		limpiarCampos($valor);
		$valida = 0;
		$patron =  "/^((\+?34([ \t|\-])?)?[9|6|7]((\d{1}([ \t|\-])?[0-9]{3})|(\d{2}([ \t|\-])?[0-9]{2}))([ \t|\-])?[0-9]{2}([ \t|\-])?[0-9]{2})$/";
       
		if(empty($valor)) {
			$valida = 1;
		}
		else if(!preg_match($patron, $valor)) {
			$valida = 2;
		}
		return $valida;
		
		
		
	}
	
	

?>