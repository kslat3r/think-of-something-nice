<?php 

class DB extends Singleton {

	private $host;
	private $username;
	private $password;
	private $connection;
	private $database;
	static public $queries = 0;	

	public function __construct() {
		global $Conf;		
		$this->host = $Conf->DB['host'];
		$this->username = $Conf->DB['username'];
		$this->password = $Conf->DB['password'];
		$this->database = $Conf->DB['db'];		
	}

	public function __destruct() {
		$this->disconnect_db();
	}

	//connect to the db
	private function connect_db() {
		$this->connection = mysql_connect($this->host, $this->username, $this->password);
		if (!$this->connection) {
			Util::debug("Could not create DB link!", 'error');
			return false;
		}
		else {
			mysql_select_db($this->database);
		}
	}

	//disconnect from db
	private function disconnect_db() {
		if ($this->connection) {
			mysql_close($this->connection);
			unset($this->connection);
		}
	}

	//return the connection if it exists
	private function get_connection() {
		if (!$this->connection) {
			$this->connect_db();
		}
		return $this->connection;
	}
	
	public function execute_sql($sql) {
		Util::debug($sql, 'db');		
		$result = mysql_query($sql, $this->get_connection());
		self::$queries++;		
		if (mysql_error()) {
			Util::debug("Invalid query: " . mysql_error(), 'error');
			return false;
		}		
		$newid	= mysql_insert_id();		
		if (!$newid) {
			return mysql_affected_rows($this->get_connection());
		}		
		return $newid;		
	}

	//select a single row as an array
	public function select_row($sql) {	
		Util::debug($sql, 'db');
		$result = mysql_query($sql, $this->get_connection());
		self::$queries++;				
		if ($result) {			
			if (mysql_num_rows($result) > 0) {
				$out = mysql_fetch_array($result, MYSQL_ASSOC);
			}
			else {
				$out = false;
			}
			mysql_free_result($result);
			return $out;
			
		}
		else {	
			Util::debug("Invalid query: " . mysql_error(), 'error');	
			return false;
		}
	}

	//select multiple rows as an array
	public function select_rows($sql) {
		Util::debug($sql, 'db');
		$result = mysql_query($sql, $this->get_connection());
		self::$queries++;		
		if ($result) {			
			if (mysql_num_rows($result) > 0) {
				$r = array();
				while ($a = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$r[] = $a;
				}
			}
			else {
				$r = false;
			}
			mysql_free_result($result);
			return $r;			
		}
		else {
			Util::debug("Invalid query: " . mysql_error(), 'error');
			return false;
		}	
	}

	//select a single value
	public function get_value($sql) {
		$result = $this->select_row($sql);
		if (is_array($result)) {
			foreach ($result as $output) {
				return $output;
			}
		}
	}

	//insertion function (calls execute_sql)
	public function insert($table, $data) {
		$columns = array();
		$values = array();
		foreach ($data as $key=>$value) {
			$columns[] = $key;
			$values[] = $this->sterilise($value);
		}
		$sql = 'INSERT INTO '.$table
			.'('.implode(',', $columns)
			.') VALUES('.implode(',', $values).')';
		return $this->execute_sql($sql);
	}

	//update function (calls execute sql)	
	public function update($table, $data, $column, $id) {
		$sql = 'UPDATE '.$table.' SET ';
		$items = array();
		foreach ($data as $key=>$value) {
			$items[] = $key.' = '.$this->sterilise($value);
		}
		$sql .= implode(', ', $items);
		$sql .= ' WHERE '.$column.' = '.$this->sterilise($id);
		return $this->execute_sql($sql);		
	}

	//delete function (calls execute sql)
	public function delete($table, $data) {
		$sql = 'DELETE FROM '.$table.' WHERE ';
		foreach ($data as $key=>$value) {
			$wherearray[] = $key.'='."'".$value."'";
		}
		$sql .= implode(' AND ', $wherearray);
		return $this->execute_sql($sql);
	}

	//count length of returned array from get_value
	public function get_count($sql) {
		$result = $this->execute_sql($sql);
		return intval($result);	
	}
	
	//sterilise input for same db transactions
	public function sterilise($value) {
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		switch (gettype($value)) {
			case 'integer':
			case 'double':
				$escaped_value = $value;
				break;
			case 'string':
				$escaped_value = "'".mysql_real_escape_string($value, $this->get_connection())."'";
				break;
			case 'NULL':
				$escaped_value = 'NULL';
				break;
			default: 
				$escaped_value = "'".mysql_real_escape_string($value, $this->get_connection())."'";
		}
		return $escaped_value;
	}
	
	public function set_db($name) { 
		Util::debug('Database: ' .$name);
		if 
		(!$this->connection) {
			$this->database	= $name;
		}
		else{
			mysql_select_db($name, $this->get_connection());
		}
	}
	
	public function create_db($dbname) {
		$sql = "CREATE DATABASE ".$dbname;
		if ($this->execute_sql($sql)) {
			Util::debug('Db '.$dbname.' created.', 'db');			
			shell_exec('mysql -u '.$this->username.' --password='.$this->password.' --database '.$dbname.' < '.$this->default_sql);			
			return true;
		}
		return false;
	}
}

?>