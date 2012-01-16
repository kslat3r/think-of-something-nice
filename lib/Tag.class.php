<?php

	class Tag extends Base {
		
		protected $classname 	= "Tag";
		protected $table 		= "tblTags";
		protected $pk 			= "id";
		
		public function assign_post($Post) {
			$data['postID'] = $Post->id();
			$data['tagID'] 	= $this->id();
			
			$this->db->insert('tblPosts_Tags', $data); 
		}			
	}

?>
