<?php
class Input{
	
	//analizza se una variabile POST o GET contiene valori 
	public static function exists($type = 'post'){
		switch($type){
			
			case 'post':
				return (!empty($_POST)) ? true : false;
				break;
			case 'get':
				return (!empty($_GET)) ? true : false;
				break;
			default:
				return false;
				break;
					
		}
	}
	
	//ritorna i valori delle variabili POST o GET 
	public static function get($item){
		if(isset($_POST[$item])){
			return $_POST[$item];
		}
		else if(isset($_GET[$item])){
			return $_GET[$item];
		}
		//return '';
	}
}