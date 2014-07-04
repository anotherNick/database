<?php
class ReagentSingleViewTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupTestDatabase();
    }

    public function testReagentSingleView()
    {
        $reagent = W::addReagent( '1' );

        $stamp = new \Duelist101\Db\View\ReagentSingle();
        $stamp->parse( $reagent );
        
        // XML must have one main element, so enclose in a div
        $actual = '<div>' . PHP_EOL . $stamp . PHP_EOL . '</div>';
		$this->assertXmlStringEqualsXmlFile( 'views/ReagentSingleExpected1.html', $actual );
    }
}