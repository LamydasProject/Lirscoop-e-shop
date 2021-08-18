<?php

/**
 * 
 */
class Database
{
	
	private $con;
	public function connect(){
		//Database Connection ("Server", "Username", "Password", "Database name")
		$this->con = new Mysqli("localhost", "root", "", "lirs");
		return $this->con;
	}
}

?>