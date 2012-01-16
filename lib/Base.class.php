<?php

class Base {

	protected $db;
	protected $details;

	function __construct($details) {	
		$this->db   = DB::fetch('DB');
		$this->details  = $details;
	}

	function __call($method, $arguments) {
		if (isset($this->details[$method])) {
			return $this->details[$method];
		}
		else {
			if (isset($this->details[$this->pk])) {
				$sql= 'SELECT ' . $method . ' FROM ' . $this->table . ' WHERE ' . $this->pk . '='. $this->db->sterilise($this->details[$this->pk]);
				$this->details[$method] = $this->db->get_value($sql);
				return $this->details[$method];
			}
		}
		return false;
	}

	public function to_array() {
		return $this->details;
	}
	
	public function add_detail($detail) {
		foreach ($detail as $key=>$value) {
			$this->details[$key] = $value;
		}
	}
	
	public function remove_detail($key) {
		unset($this->details[$key]);
	}

	public function id() {
		return $this->details[$this->pk];
	}

	public function update($data) {
		$this->db->update($this->table, $data, $this->pk, $this->details[$this->pk]);
		$this->details = array_merge($this->details, $data);
	}

	public function delete() {
		$data[$this->pk]=$this->details[$this->pk];
		$this->db->delete($this->table, $data);
	}
}

?>
