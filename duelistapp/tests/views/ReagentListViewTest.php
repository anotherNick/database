<?php
class ReagentListViewTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupTestDatabase();
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
        $stamp->parse( $reagents );
        
        // XML must have one main element, so enclose in a div
        $actual = '<div>' . PHP_EOL . $stamp . PHP_EOL . '</div>';
		$this->assertXmlStringEqualsXmlFile( 'views/ReagentListExpected1.html', $actual );
    }
}
