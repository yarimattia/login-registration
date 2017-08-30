<?php

class User {
	
	private $_db,
			$_data,
			$_sessionName;
	
	public function __construct($user = null){
		$this->_db = DB::getInstance();
		$this->_sessionName = Config::get('session/session_name');
	}
	
	public function create($fields = array()){
		if(!$this->_db->insert('users', $fields))
			throw new Exception('There was a problem creating an account');
	}
	public function getResult(){
		$this->_db->showRecords();
	}
	
	
	public function find($user = null){
		if($user){
			$field = (is_numeric($user)) ? 'id' : 'username';
			$data = $this->_db->select('users', array($field, '=', $user));
			
			
			if($data->getCount()){
				$this->_data = $data->first();
				return true;
			}
		}
		return false;
	}
	
	public function login($username = null, $password = null){
		$user = $this->find($username);
			if($user){
				if($this->_data->password === Hash::make($password, $this->_data->salt)){
					Session::put($this->_sessionName, $this->_data->id);
					return true;
				}
			}
		
		return false;
	}
	
}

?>