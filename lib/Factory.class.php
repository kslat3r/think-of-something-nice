<?php

class Factory
{    
	protected $db;
   
	function __construct() {
	    $this->db = DB::fetch('DB');
	}
	
	public function find($data=false, $limit=false) {
       	$sql    = 'SELECT * 
               	   FROM ' . $this->table;
		if ($data == true) {
			$sql .= ' WHERE ';
			$i=0;
			foreach ($data as $key=>$value) {
				if ($i > 0) {
					$sql .= ' AND ';
				}
				$sql .= $key . '='. $this->db->sterilise($value);
				$i++;
			}        
			if ($limit!=false) {
				$sql .= ' LIMIT '.$limit;
			}	            	  
		}	     
       
		$result = $this->db->select_rows($sql);
		
		if (!is_array($result)) {
			return null;
		}	   
		else if (is_array($result) && $limit==1) {
            return new $this->classname($result[0]);
        }
        else if ($limit>1 || $data==false) {
        	return $this->return_instances($result);
        }
        else {
        	return $this->return_instances($result);
        }
	}
    
	public function create($data, $once=false) {        
		if ($once==true) {
			$sql = 'SELECT * FROM '.$this->table.' WHERE ';
			foreach ($data as $key=>$value) {
				$rows[] = $key."=".$this->db->sterilise($value);				
			}
			$where = implode(' AND ', $rows);
			$sql .= $where;
			$result = $this->db->select_row($sql);
			if ($result) {
				return new $this->classname($result);
			}
		}
		$new = $this->db->insert($this->table, $data);        
		if ($new) {
			$sql = 'SELECT *
                        FROM ' . $this->table . ' 
                        WHERE ' .$this->pk . '='. $this->db->sterilise($new) .'
                        LIMIT 1';
			$result = $this->db->select_row($sql);            
			if ($result) {
        		return new $this->classname($result);
			}
		}
	}
	
	protected function return_instances($rows)
    {
        if (is_array($rows) && count($rows) > 0) {
            $out    = array();
            foreach($rows as $row) {
                $out[]  = new $this->classname($row);
            }
            return $out;
        }
        else {
        	return new $this->classname($rows);
        }
    }
        
}

?>
