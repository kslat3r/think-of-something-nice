<?php

class Conf
{
	public $php_dir = '/usr/share/php';
	
	public $name = array('ownerName'=>'Ed Kelly', 'siteName'=>'Think of Something Nice');
	public $email = array('sysadmin'=>'us@thinkofsomethingnice.com', 'mail'=>'no-reply@thinkofsomethingnice.com');
	
	public $subdomain = '';
	public $domain = '';
	public $tld = '';
	
	function __construct($httphost = false) {

		if ($httphost != false) {
			$this->http_host = $httphost;
		}
		else {
			$this->http_host = getenv('HTTP_HOST');
		}

		$tmp = preg_split('%\.%', $this->http_host);
		$this->domain = $tmp[1];
		$this->subdomain = $tmp[0];
		$this->tld = $tmp[2];

		switch($this->domain){
	    	case('bowlerhat'):
				$this->DB['host'] = 'localhost';
			   	$this->DB['username'] = 'mysql-tosn';
			   	$this->DB['password'] = 'g4g44gw4';
			   	$this->DB['db']	= 'dbTOSN';
				
			   	$this->libpath	= '/home/kslat3r/Projects/thinkofsomethingnice/trunk/lib';
				$this->fragmentpath = '/home/kslat3r/Projects/thinkofsomethingnice/trunk/fragments';
				$this->layoutpath = '/home/kslat3r/Projects/thinkofsomethingnice/trunk/layouts';
				$this->pagepath = '/home/kslat3r/Projects/thinkofsomethingnice/trunk/pages';
				$this->sqlpath = '/home/kslat3r/Projects/thinkofsomethingnice/trunk/data/sql';
				$this->cachepath = '/home/kslat3r/Projects/thinkofsomethingnice/trunk/data/cache';
				$this->uploadpath = '/home/kslat3r/Projects/thinkofsomethingnice/trunk/public_html/assets/uploads';
				
				$this->recaptcha_public_key = '6LdldMYSAAAAAAIPdjEgEg8urDwllpllUJCbtSky';
				$this->recaptcha_private_key = '6LdldMYSAAAAAFjaLimayszKp2xGRG2BikbrOCwO';
				
				$this->cookie_domain = 'bowlerhat.eajk';
				
				$this->production_status = 'dev';
	    	break;	
			case('thinkofsomethingnice'):
				$this->DB['host'] = 'localhost';
			   	$this->DB['username'] = 'mysql-tosn';
			   	$this->DB['password'] = 'g4g44gw4';
			   	$this->DB['db']	= 'dbTOSN';
				
			   	$this->libpath	= '/var/www/thinkofsomethingnice/lib';
				$this->fragmentpath = '/var/www/thinkofsomethingnice/fragments';
				$this->layoutpath = '/var/www/thinkofsomethingnice/layouts';
				$this->pagepath = '/var/www/thinkofsomethingnice/pages';
				$this->sqlpath = '/var/www/thinkofsomethingnice/data/sql';
				$this->cachepath = '/var/www/thinkofsomethingnice/data/cache';
				$this->uploadpath = '/var/www/thinkofsomethingnice/public_html/assets/uploads';
				
				$this->recaptcha_public_key = '6LdhdMYSAAAAACMN3XUdG4fd8-8NZC4kiNYuxl0b';
				$this->recaptcha_private_key = '6LdhdMYSAAAAAAirUCYovy2ZAYDbCLEERXr9agyp';
				
				$this->cookie_domain = 'thinkofsomethingnice.com';
				
				$this->production_status = 'live';
	    	break;	    	
		}

		switch ($this->production_status) {
			case 'dev':
				$this->debug = false;
				$this->cache = false;
			break;
			case 'staging':
				$this->debug = false;
				$this->cache = false;
			break;
			case 'production':
				$this->debug = false;
				$this->cache = true;
			break;
		}
	}
}

$Conf = new Conf();

?>