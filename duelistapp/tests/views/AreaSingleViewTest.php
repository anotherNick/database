<?php
class AreaSingleViewTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupTestDatabase();
    }

    public function testAreaSingleView()
    {
        $area = W::addArea( '1' );

        $stamp = new \Duelist101\Db\View\AreaSingle();
        $stamp->parse( $area );
        
        // XML must have one main element, so enclose in a div
        $actual = '<div>' . PHP_EOL . $stamp . PHP_EOL . '</div>';
		$this->assertXmlStringEqualsXmlFile( 'views/AreaSingleExpected1.html', $actual );
    }
}