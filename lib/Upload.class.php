<?php

	class Upload extends Base {
		
		protected $classname 	= "Upload";
		protected $table 		= "tblUploads";
		protected $pk 			= "id";
			
		public function delete() {
			global $Conf;
			
			unlink($Conf->uploadpath.'/'.$this->name());
			
			parent::delete();
		}	
		
	}

?>
