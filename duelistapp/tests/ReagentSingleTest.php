<?php
class ReagentSingleTest extends PHPUnit_Framework_TestCase
{
    public function testReagentSingleView()
    {
        include 'WizardTestObjects.php';
        include '../vendor/stamp/stampTE.php';
        include '../templates/ReagentSingle.php';
        
        $reagent = W::getStoneBlock();

        $stamp = new \Duelist101\Db\View\ReagentSingle( file_get_contents('../templates/ReagentSingle.html') );
        $stamp->parse( $reagent );
        
		$this->assertXmlStringEqualsXmlFile( 'ReagentSingle-StoneBlock.html', $stamp );
    }
}