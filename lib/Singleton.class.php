<?php
 
	class Singleton {
			
		//create a singleton of this class (only one instance)
		public static function fetch($classname) {
		
			static $instances = array();
			
			if (!array_key_exists($classname, $instances)) {
				$instances[$classname] = new $classname;
			}
			
			return $instances[$classname];
		}   
		
	}
	
?>