<?php
namespace Codeception\Module;
use \Codeception\Util\Fixtures as Fixtures;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class ApiHelper extends \Codeception\Module
{

	protected $requiredFields = array('username', 'password', 'wp_url');
	
	public function haveWordpressAuth( $I )
	{
		try{
			$cookie = Fixtures::get('wp_cookie');
		}catch( \Exception $e ){
			$cookie_name = 'wordpress_' . md5( $this->config['wp_url'] );
			
			$I->amOnUrl( rtrim( $this->config['wp_url'], '/' ) . '/wp-login.php' );
			$I->see( 'Username' );
			$I->see( 'Password' );
			$I->checkOption( 'rememberme' );
			$I->fillField(['name' => 'log'], $this->config['username'] );
			$I->fillField(['name' => 'pwd'], $this->config['password'] );
			$I->click( 'Log In' );
			$cookie_value = $I->grabCookie( $cookie_name );
			Fixtures::add('wp_cookie', ['name' => $cookie_name, 'value' => $cookie_value]);
		}
	}

}
