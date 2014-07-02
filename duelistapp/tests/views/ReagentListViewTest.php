<?php
class ReagentListViewTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupCleanRedBean();
    }

    public function testReagentList1()
    {
		$reagents = array(
            W::addReagent( '1' ),
            W::addReagent( '2' ),
            W::addReagent( '3' ),
            W::addReagent( '4' )
        );
		
        $stamp = new \Duelist101\Db\View\ReagentList();
//        $stamp = new \Duelist101\Db\View\ReagentList( file_get_contents('../templates/ReagentList.html') );
        $stamp->parse( $reagents );

		$this->assertXmlStringEqualsXmlFile( 'views/ReagentListExpected1.html', $stamp );
    }
}
