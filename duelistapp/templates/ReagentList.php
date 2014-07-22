<?php
namespace Duelist101\Db\View;

Class ReagentList extends \Duelist101\Stamp
{
    public function parse( $reagents ) {
        
        $i = 0;

        foreach ($reagents as $reagent) {
            $cut = $this->getReagent();
            $i = $i + 1;
            if ( $i == 3 ) {
                $i = 0;
                $cut->setLastOption('last');
                // leaving old way in case we need to reference in future
                // don't like including raw HTML in code if we can help it
                // old way was $cut->injectRaw('lastFooter', '<div style="clear: both;"></div>');
                $cutLastFooter = $this->get('reagent.lastFooter');
                $cut->add($cutLastFooter);
            }
            $cut->setName($reagent->name);
            $cut->setLinkName( \Duelist101\BASE_URL . 'reagents/' . urlencode($reagent->name) );
            $cut->setImage($reagent->image);
            $cut->setClassName($reagent->class->name);
            $cut->setRank($reagent->rank);
            $this->add($cut);
        }
    }
}
