<?php
class AreaListViewTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupDatabases();
    }

    public function testAreaListView()
    {
        $file = 'views/AreaListExpected1.html';
    
        $world = W::addWorld( '1' );
        $area = W::addArea( $world, '1' );

        $stamp = new \Duelist101\Db\View\AreaSingle();
        $stamp->parse( $area );
        
        // XML must have one main element, so enclose in a div
        $actual = '<div>' . PHP_EOL . $stamp . PHP_EOL . '</div>';

        if ( !file_exists($file) ) {
            file_put_contents( $file, $actual );
            $this->assertTrue(false, 'Created ' . $file . '. If expected, re-run test to pass.');
        }

		$this->assertXmlStringEqualsXmlFile( $file, $actual );
    }
}