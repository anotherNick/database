<?php
class ReagentListViewTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupDatabases();
    }

    public function testReagentList1()
    {
        $file = 'views/ReagentListExpected1.html';
        
        $class = W::addClass( '1' );
		$reagents = array(
            W::addReagent( $class, '1' ),
            W::addReagent( $class, '2' ),
            W::addReagent( $class, '3' ),
            W::addReagent( $class, '4' )
        );

        $stamp = new \Duelist101\Db\View\ReagentList();
        $stamp->parse( $reagents );
        
        // XML must have one main element, so enclose in a div
        $actual = '<div>' . PHP_EOL . $stamp . PHP_EOL . '</div>';
        
        if ( !file_exists($file) ) {
            file_put_contents( $file, $actual );
            $this->assertTrue(false, 'Created ' . $file . '. If expected, re-run test to pass.');
        }

		$this->assertXmlStringEqualsXmlFile( $file, $actual );
    }
}
