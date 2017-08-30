<?php
class Validation{
	
	/*---------------PROPRIETA' ----------------------------------------------------------------------------------------------------------------------------------------------------------*/
	
	private $_passed = false, //un flag per la validazione
	$_errors = array(), //contiene eventuali errori
	$_db = null; //un oggetto di classe DB 
	
	
	
	/*---------------COSTRUTTORE -------------------------------------------------------------------------------------un oggetto Validation racchiude un oggetto DB-------------------*/
	
	public function __construct(){
		$this->_db = DB::getInstance();
	}
	
	
	
	/*------------METODI-----------------------------------------------------------------------------------------------------------------------------------------------------------------*/
	
	//metodo di validazione dei dati
	//scorre l'array $items facendo un confronto coi dati in input $source
	public function check($source, $items = array()){
		
		foreach($items as $item => $rules){
			foreach($rules as $rule => $rule_value){
				
				$value = trim($source[$item]);
				$item = escape($item);
				
				if($rule === 'required' && empty($value)){
					$this->addError("{$item} is required");
				}
				else if(!empty($value)){
					switch($rule){
						case 'min':
							if(strlen($value)< $rule_value){
								$this->addError("{$item} must be a minimum of {$rule_value} characters");
							}
						break;
							
						case 'max':
							if(strlen($value)> $rule_value){
								$this->addError("{$item} must be a maximum of {$rule_value} characters");
							}
							
						break;
						
						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("{$rule_value} must match with {$item}");
							}
							
						break;
							
						case 'unique':
							$check = $this->_db->select($rule_value,array($item, '=', $value));
								if($check->getCount()){
									$this->addError("{$item} already exists");
								}
							
						break;
							
						
					}
				}
			}
			
		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	
		
	}
	
	
	//aggiunge un errore alla variabile privata _errors
	private function addError($error){
		$this->_errors[] = $error;
	}
	
	//recupera gli errori dalla variabile privata _errors
	public function getErrors(){
		return $this->_errors;
	}
	
	//un flag; diventa true se check() da esito positivo
	public function passed(){
		return $this->_passed;
	}
}



?>