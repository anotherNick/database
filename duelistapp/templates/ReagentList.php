<?php
namespace Duelist101\Db\View;

Class ReagentList extends \Duelist101\Stamp
{
    public function parse( $reagents ) {
        
        $i = 0;
        foreach ($reagents as $reagent) {
            $cutReagent = $this->getReagent();
            $i = $i + 1;
            if ( $i == 3 ) {
                $i = 0;
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
