<?php
class ReagentSingleViewTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupCleanRedBean();
    }

    public function testReagentSingleView()
    {
        $reagent = W::addReagent( '1' );

        $stamp = new \Duelist101\Db\View\ReagentSingle();
//        $stamp = new \Duelist101\Db\View\ReagentSingle( file_get_contents('../templates/ReagentSingle.html') );
        $stamp->parse( $reagent );
        
		$this->assertXmlStringEqualsXmlFile( 'views/ReagentSingleExpected1.html', $stamp );
    }
}