<?php
use \ApiTester;
use \Duelist101\W as W;

class BasicApiCest
{
	public function _before(ApiTester $I)
	{
		$I->haveWordpressAuth( $I );
	}
	
	
}
?>