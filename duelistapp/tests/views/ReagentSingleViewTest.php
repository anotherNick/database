<?php
class ReagentSingleViewTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once '../vendor/autoload.php';
        require_once 'models/WizardTestObjects.php';
        W::setupDatabases();
    }

    public function testReagentSingleView()
    {
        $file = 'views/ReagentSingleExpected1.html';
    
        $class = W::addClass( '1' );
        $reagent = W::addReagent( $class, '1' );
        $reagent->id = 1;
        
        R::wipe( 'area' );
        $world = W::addWorld( '1' );
        $area = W::addArea( $world, '1' );
        R::store( $area );
        $areas = R::findAll( 'area', 'ORDER BY name');

        $stamp = new \Duelist101\Db\View\ReagentSingle();
        $stamp->parse( $reagent, $areas );
        
        // XML must have one main element, so enclose in a div
        $actual = '<div>' . PHP_EOL . $stamp . PHP_EOL . '</div>';

        if ( !file_exists($file) ) {
            file_put_contents( $file, $actual );
            $this->assertTrue(false, 'Created ' . $file . '. If expected, re-run test to pass.');
        }

		$this->assertXmlStringEqualsXmlFile( $file, $actual );
    }
}