<?php
namespace Duelist101\Db\View;

Class ReagentList extends \StampTemplateEngine\StampTE
{
    public function parse( $reagents ) {
        
        for ($i = 1; $i <= count($reagents); $i++) {
            $reagent = $reagents[$i];
            $cutReagent = $this->getReagent();
            if ( $i % 3 == 0 ) {
                $cutReagent->setLastOption('last');
                $cutReagent->injectRaw('lastFooter', '<div style="clear: both;"></div>');
            }
            $cutReagent->setName($reagent->name);
            $cutReagent->setLinkName( 'reagents/' . urlencode($reagent->name) );
            $cutReagent->setImage($reagent->image);
            $cutReagent->setClassName($reagent->class->name);
            $cutReagent->setRank($reagent->rank);
            $this->add($cutReagent);
        }
    }
}
