<?php

class DB
{
	//utilizzato per controllare se l'oggetto,quindi la connessione al DB,esiste già
	private static $_instance = null;
	private $_pdo,//rappresenta l'oggetto PDO instanziato
			$_query, //rappresenta una query
			$_error = false, 
			$_errorMessage = array(),
			$_result, 
			$_count = 0,
			$_debug;
	
	
	/*--------------------------------------------------------------------------------------------------------------------COSTRUTTORE-----------------------------------------------------------------------------------------*/
	
	//costruttore privato di un oggetto DB tramite PDO
	private function __construct(){
		try{
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host').';'.
								      'dbname=' . Config::get('mysql/db'), 
								   				  Config::get('mysql/username'),
								   			      Config::get('mysql/password'));
			
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}
	
	//accede al costruttore dall'esterno, instanziando un oggetto DB
	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	
	
	/*--------------------------------------------------------------------------------------------------------------------QUERY-----------------------------------------------------------------------------------------*/
			
			/*metodi privati*/
	//metodo privato; imposta un prapared statement 
	public function query($sql, $params = array()){
		$this->_error = false;
		//assegna un prepared statement all'oggetto _query
		if($this->_query = $this->_pdo->prepare($sql)){
			
			//bind dei parametri
			$x=1;
			if(count($params)){
				foreach($params as $param){
					if($this->_query->bindValue($x, $param)){
						$x++;
					}
					else{
					$this->addErrorMessage("Errore nel binding!<br>");
					$this->_error = true;
					}
				}
			
			
				//esegue la query
				if($this->_query->execute()){
					$this->_result = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count = $this->_query->rowCount();

				}
				else{
					$this->addErrorMessage("La query è fallita!<br>");
					$this->_error = true;
				}
			}
			else{
					$this->addErrorMessage("Nessun parametro in entrata alla query!<br>");
					$this->_error = true;
			}
			
		if($this->_error){
			foreach($this->getErrorMessage() as $message){
			echo $message;
			}
		}
		
		
		}
		else{
					$this->addErrorMessage("Errore nel metodo prepare()!<br>");
					$this->_error = true;
				}
		return $this;
	}
	
	//metodo privato; costruisce le query utilizzate nei metodi pubblici
	public function action($action, $table, $where = array()){
		if(count($where) === 3){
			$operators = array('=', '>', '<', '>=', '<=');
			
			$field = $where[0];
			$operator = $where[1];
			$value = $where[2];
			
			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
				
				if(!$this->query($sql, array($value))->getError()){
					return $this;
				}
				else{
					$this->_error = true;
					$this->addErrorMessage('Errore!Verificare i valori inseriti.<br>Esecuzione query fallita.<br>');
				}
			}
			else{
				$this->_error = true;
				$this->addErrorMessage('Errore!Array operators vuoto.<br>');
			}
		}
		else if(count($where) === 0){
			$sql = "{$action} FROM {$table}";
			
			if(!$this->query($sql)->getError()){
					return $this;
		}
			else{
				$this->_error = true;
				$this->addErrorMessage('Tabella non trovata!<br>');
			}
		}
		else{
				$this->_error = true;
				$this->addErrorMessage('Problema col parametro Where!<br>');
		}
		
	if($this->_error){
		foreach($this->getErrorMessage() as $message){
			echo $message;
		}
	}
		
			
	return false;
			
		
	}
	 		
			/*metodi pubblici*/
	//esegue una SELECT
	public function select($table, $where){
		return $this->action('SELECT *', $table, $where);
	}
	
	//esegue una SELECT su un'intera tabella
	public function selectTable($table){
		return $this->action('SELECT *', $table);
	}
	
	//esegue un INSERT
	public function insert($table, $fields = array()){
		if(count($fields)){
			$keys = array_keys($fields);
			$values = '';
			$x = 1;
			
			foreach($fields as $field){
				$values .= '?';
				if($x < count($fields)){
					$values .= ', ';
				}
				$x++;
			}
			
			$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`)  VALUES({$values})";
		
			if(!$this->query($sql, $fields)->getError()){
				
				return true;
			}
		}
		return false;
	}
	
	//esegue un UPDATE
	public function update($table, $id, $fields){
		$set = '';
		$x = 1;
		
		foreach($fields as $name => $value){
			$set .= "{$name} = ?";
			if($x < count($fields)){
				$set .= ', ';
			}
			$x++;
		}
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		if(!$this->query($sql, $fields)->getError()){
				return true;
		}
		else 
			return false;
		echo $sql;
	}
	
	//esegue un DELETE
	public function delete($table, $where){
		return $this->action('DELETE FROM', $table, $where);
	}
	
	//esegue un DELETE su un intera tabella
	public function deleteTable($table){
		return $this->action('DELETE * FROM', $table);
	}
	
	
	/*--------------------------------------------------------------------------------------------------------------------METODI GETTER--------------------------------------------------------------------------------------*/
	
	//ritorna l'array _result
	public function getResult(){
		return $this->_result;
		}
	
	public function first() {
		 return $this->_result[0];
		}
	
	//setta il valore di _errorMessage
	public function addErrorMessage($error){
		return $this->_errorMessage[] = $error;
	}
	
	//ritorna il valore di _errorMessage
	public function getErrorMessage(){
		return $this->_errorMessage;
	}
	
	//ritorna il valore di _error
	public function getError(){
		return $this->_error;
	}
	
	//ritorna _count
	public function getCount(){
		return $this->_count;
	}
	
	public function getDebug(){
		return $this->_debug;
	}

	
	/*--------------------------------------------------------------------------------------------------------------------MOSTRA RISULTATI DI UNA QUERY----------------------------------------------------------------------*/
	//accetta come argomento un array contenente i nomi delle colonne da visualizzare
	
	public function showRecords($fields=array()){
		if($this->_result){
			if(!empty($fields)){
			
				$results = $this->_result;
				$x=1;
				foreach($results as $result){
					echo "Row #$x<br>";
					$x++;
					foreach($fields as $field){
					echo "$field : " . $result->$field, '<br>';
					}
					echo "<br>";
				}
			}
			else
				echo "Non sono stati immessi campi da visualizzare";
				
				
    
			}
		else
			echo "Nessun record da visualizzare.Verificare i valori inseriti<br>";
		}
	
}

?>