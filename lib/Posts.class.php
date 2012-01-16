<?php

	class Posts extends Factory {
	
		protected $classname 	= "Post";
		protected $table 		= "tblPosts";
		protected $pk 			= "id";
	
		public function find_all() {
			$sql = "SELECT * FROM tblPosts
					ORDER BY created_date DESC";
			$result = $this->db->select_rows($sql);
			
			if ($result) {
				return $this->return_instances($result);
			}
			
			return array();
		}
	
		public function get_random_post() {
			$sql = "SELECT * FROM tblPosts
					WHERE moderated = 1
					ORDER BY RAND()
					LIMIT 1";
			$result = $this->db->select_row($sql);
			
			if ($result) {
				return new $this->classname($result);			
			}
			
			return false;
		}
		
		public function has_posted($Session) {
			$sql = "SELECT * FROM tblPosts
					WHERE session_id = ".$this->db->sterilise($Session->session_id);
			$result = $this->db->select_rows($sql);
			
			if (is_array($result)) {
				return true;
			}
			
			return false;
		}
		
		public function get_prev_id($Post) {
			$sql = "SELECT id from tblPosts
					WHERE id < ".$Post->id()."
					AND moderated = 1
					ORDER BY id DESC
					LIMIT 1";
			$result = $this->db->select_row($sql);
			
			if (!is_array($result)) {
				return $this->get_first_id();
			}			
			
			return $result['id'];	
		}
		
		public function get_next_id($Post) {
			$sql = "SELECT id from tblPosts
					WHERE id > ".$Post->id()."
					AND moderated = 1
					ORDER BY id ASC
					LIMIT 1";
			$result = $this->db->select_row($sql);
			
			if (!is_array($result)) {
				return $this->get_last_id();
			}			
			
			return $result['id'];					
		}
		
		public function get_first_id() {
			$sql = "SELECT * FROM tblPosts
					WHERE moderated = 1
					ORDER BY id ASC
					LIMIT 1";
			$result = $this->db->select_row($sql);
			
			if ($result) {
				return $result['id'];
			}
			
			return 0;
		}
		
		public function get_last_id() {
			$sql = "SELECT * FROM tblPosts
					WHERE moderated = 1
					ORDER BY id desc
					LIMIT 1";
			$result = $this->db->select_row($sql);
			
			if ($result) {
				return $result['id'];
			}
			
			return 0;
		}
		
		public function is_first($Post) {
			$first_id = $this->get_first_id();
			
			if ($Post->id() == $first_id) {
				return true;
			} 
			
			return false;
		}
		
		public function is_last($Post) {
			$last_id = $this->get_last_id();
			
			if ($Post->id() == $this->get_last_id()) {
				return true;
			}
			
			return false;
		}
		
		public function get_from_tag($Tag) {
			$sql = "SELECT tblPosts.* FROM tblPosts, tblPosts_Tags
					WHERE tblPosts.id = tblPosts_Tags.postID
					AND tblPosts_Tags.tagID = ".$Tag->id()."
					AND moderated = 1
					ORDER BY tblPosts.created_date DESC";			
			$result = $this->db->select_rows($sql);
			
			if ($result) {
				return $this->return_instances($result);
			}
			
			return array();
		}
	
	}

?>