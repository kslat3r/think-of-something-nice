<?php

class Page {

	public $server_root;
	public $title;
	public $cssarray = array('reset.css', 'default.css');
	public $rawcssstring = '';
	public $jsarray = array('jquery.js', 'default.js');
	public $args = array();
	public $get = array();
	public $page;
	public $dir;
	public $request_url;
	public $request_uri;
	public $authfile;
	public $ajaxfile;
	public $prefile;
	public $postfile;
	public $cachefile;
	public $cache = false;
	public $auth_req;
	public $layout = 'default';
	public $format = 'default';
	public $debug = '';
	public $output_debug = true;
	public $original_dir = '';
	public $original_page = '';

	function __construct() {
		global $Conf;

		//get request details
		$this->request_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$this->dir = $_SERVER['REQUEST_URI'];
		$tmp = explode('?', $this->dir);
		$this->request_uri = $tmp[0];
		$this->server_root = 'http://'.$_SERVER['SERVER_NAME'];

		//find get requests
		$tmp = explode('?', $this->dir);
		$this->dir = $tmp[0];
		if (isset($tmp[1])) {
			$gets = explode('&', $tmp[1]);
			if (is_array($gets)) {
				foreach ($gets as $pair) {
					$s = explode('=', $pair);
					if (isset($s[0]) && isset($s[1])) {
						$this->get[$s[0]] = $s[1];
					}
				}
			}
		}

		//find extension, layout and format
		$tmp = explode('.', $this->dir);
		if (isset($tmp[1])) {
			$this->format = $tmp[1];
			$this->layout = $tmp[1];
			if (!file_exists($Conf->layoutpath.'/'.$this->layout.'/top.inc.php')) {
				$this->format = 'default';
				$this->layout = 'default';
			}
		}
		$this->original_dir = $tmp[0];
		$this->dir = $tmp[0];
		$tmp = explode('/', $this->dir);
		$this->section = preg_replace('%/%', '', $tmp[1]);

		//change page based on preg_matches
		$this->rewrite_page($this->request_url);

		//find page from current directory
		if ($this->dir=="/") $this->dir="/home";
		$this->page = explode('/', $this->dir);
		$this->page = end($this->page);

		//keep original page for login form redirect
		$this->original_page = $this->page;

		//debug switch
		$this->output_debug	= $Conf->debug;
	}

	public function init() {
		global $Conf;

		if ($this->output_debug==true) {
			Util::debug('Format: ' . $this->format);
			Util::debug('Page: ' . $this->original_page);
			// Spit post data to debug
			if (isset($_POST) && count($_POST)>0) {
		    	$s  = 'POST:';
		    	foreach($_POST as $key=>$val) {
		        	$s .= '<br />' .$key . ': ' . $val;
		    	}
		    	Util::debug($s, 'post');
			}
		}

		$this->ajaxfile = $Conf->pagepath."/".$this->dir."/".$this->page.".ajax.php";
		$this->authfile = $Conf->pagepath."/".$this->dir."/".$this->page.".auth.php";
		$this->prefile = $Conf->pagepath."/".$this->dir."/".$this->page.".pre.php";
		$this->postfile = $Conf->pagepath."/".$this->dir."/".$this->page.".post.php";
		$this->cachefile = $Conf->cachepath.'/'.$this->page.'.html';

		if (!file_exists($this->prefile) || !file_exists($this->postfile) || !file_exists($this->authfile)) {			
			$this->authfile = $Conf->pagepath.'/error/404/404.auth.php';
			$this->prefile = $Conf->pagepath."/error/404/404.pre.php";
			$this->postfile = $Conf->pagepath."/error/404/404.post.php";
		}
	}

	public function check_cache_file() {
		if (!file_exists($this->cachefile)) {
			return false;
		}
		$lastmodified = filemtime($this->cachefile);
		$currenttime = Util::microtime_float();
		if ($currenttime - 3600 > $lastmodified) {
			return false;
		}
		$filecontents = file_get_contents($this->cachefile);
		if ($filecontents == '') {
			return false;
		}
		return true;
	}

	public function set_cache($bool) {
		$this->cache = $bool;
	}

	private function rewrite_page($requesturl) {
		//for /register/confirm/[confirmhash]
		preg_match('%/thing/([A-Za-z0-9]+)%', $requesturl, $matches);
		if (count($matches)==2) {
			$out = "/thing";
			$this->args['hash'] = $matches[1];
		}
		preg_match('%/admin/thing/([A-Za-z0-9]+)%', $requesturl, $matches);
		if (count($matches)==2) {
			$out = "/admin/thing";
			$this->args['hash'] = $matches[1];
		}
		preg_match('%/tag/([A-Za-z0-9]+)%', $requesturl, $matches);
		if (count($matches)==2) {
			$out = "/tag";
			$this->args['tag'] = $matches[1];
		}
		if (isset($out)) $this->dir=$out;
	}

	public function set_title($title) {		
		$this->title = $title;
	}

	public function add_css($css) {
		$this->cssarray[] = $css;
	}

	public function add_raw_css($css) {
		$this->rawcssstring .= $css;
	}

	public function add_js($js) {
		$this->jsarray[] = $js;
	}

	public function get_css() {
		$output = '';
		foreach ($this->cssarray as $cssitem) {
			$output .= "\t".'<link rel="stylesheet" href="/assets/css/'.$cssitem.'" type="text/css" />'."\n";
		}
		if (!empty($this->rawcssstring)) {
			$output .= "\t".'<style type="text/css">'.$this->rawcssstring.'</style>'."\n";
		}
		return $output;
	}

	public function get_js() {
		$output = '';
		foreach ($this->jsarray as $jsitem) {
			$output .= "\t".'<script type="text/javascript" src="/assets/js/'.$jsitem.'"></script>'."\n";
		}
		return $output;
	}

	public function force_page($page) {
		$this->dir = $page;
		$this->page = explode('/', $page);
		$this->page = end($this->page);
		Util::debug('Forcing page to: '.$this->page, 'notice');
		$this->init();
	}

	public function set_auth($bool) {
		$this->auth_req = $bool;
	}

	public function set_last_page() {
		global $Session;
		$Session->set('lastPage', $this->request_uri);
	}

}
?>
