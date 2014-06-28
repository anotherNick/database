<?php
class ReagentListTest extends PHPUnit_Framework_TestCase
{
    public function testReagentListView()
    {
        include 'WizardTestObjects.php';
        include '../templates/ReagentList.php';
 		
		$reagents[0] = W::getStoneBlock();
		$reagents[1] = W::getStoneBlock();
		$reagents[2] = W::getStoneBlock();
		$reagents[3] = W::getStoneBlock();
		
        $stamp = new \Duelist101\Db\View\ReagentList( file_get_contents('../templates/ReagentList.html') );
        $stamp->parse( $reagents );
        
		$expected = file_get_contents('ReagentList.html');
		$this->assertRegExpTest( $expected, $view );
    }
}
