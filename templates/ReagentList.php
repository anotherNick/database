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
            $cut->setName($reagent->getName());
            $cut->setLinkName( \Duelist101\BASE_URL . 'reagents/' . urlencode($reagent->getName()) );
            $cut->setImage(\Duelist101\BASE_URL . 'images/w101_reagents/' . $reagent->getImage());
			$cut->setClassName($reagent->getClassname());
            $cut->setRank($reagent->getRank());
            $this->add($cut);
        }
    }
}
