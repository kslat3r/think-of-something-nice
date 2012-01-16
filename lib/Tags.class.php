<?php

	class Tags extends Factory {
	
		protected $classname 	= "Tag";
		protected $table 		= "tblTags";
		protected $pk 			= "id";
	
		public function get_tags_list($Post) {
			$sql = "SELECT tblTags.value FROM tblTags, tblPosts_Tags
					WHERE tblTags.id = tblPosts_Tags.tagID
					AND tblPosts_Tags.postID = ".$this->db->sterilise($Post->id())."
					ORDER BY tblTags.date_created DESC";
			$result = $this->db->select_rows($sql);
			
			if ($result) {
				//flatten
				
				$arr = array();
				foreach ($result as $res) {
					$arr[] = $res['value'];
				}
				
				//return
				
				return implode(', ', $arr);
			}
			
			return '';
		}
		
		public function delete_all($Post) {
			$data['postID'] = $Post->id();
			
			$this->db->delete('tblPosts_Tags', $data);
		}
		
		public function get_from_post($Post) {
			$sql = "SELECT tblTags.* from tblTags, tblPosts_Tags
					WHERE tblTags.id = tblPosts_Tags.tagID
					AND tblPosts_Tags.postID = ".$this->db->sterilise($Post->id())."
					ORDER BY tblTags.date_created DESC";
			$result = $this->db->select_rows($sql);
			
			if ($result) {
				return $this->return_instances($result);
			}
			
			return array();
		}
		
		public function get_tag_cloud($minsize = 11, $maxsize = 40) {
			$sql = "SELECT value, COUNT(postID) AS quantity FROM tblTags, tblPosts_Tags
					WHERE tblTags.id = tblPosts_Tags.tagID					
					GROUP BY value
					ORDER BY value ASC";
			
			$result = $this->db->select_rows($sql);
			
			if (!is_array($result)) return false;		

			$searches = array();
			
			foreach ($result as $r) {
				$searches[$r['value']] = $r['quantity'];			
			}
			
			$maxquantity = max(array_values($searches));
			$minquantity = min(array_values($searches));
			
			$spread = $maxquantity - $minquantity;
			
			//avoid division by 0
			
			if ($spread == 0) $spread = 1;
			
			$fontstep = ($maxsize - $minsize) / ($spread);
			
			$output = '';
			
			ksort($searches, SORT_REGULAR);		
			
			foreach ($searches as $key=>$value) {
				$size = round($minsize + (($value - $minquantity) * $fontstep));
				$size = ceil($size);
			 	$output .= '<a href="/tag/'.urlencode($key).'" style="font-size: '.$size.'px" title="'.number_format($value).($value == 1 ? ' thing' : ' things').'">'.$key.'</a>';
			}
			
			echo $output;
		}
			
	}

?>