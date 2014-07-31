<?php
namespace Duelist101\Db\View;

Class FishList extends \Duelist101\Stamp
{
    public function parse( $fishList ) {
        
        $i = 0;

        foreach ($fishList as $fish) {
            $cut = $this->getFish();
            $i = $i + 1;
            if ( $i == 3 ) {
                $i = 0;
                $cut->setLastOption('last');
                $cutLastFooter = $this->get('fish.lastFooter');
                $cut->add($cutLastFooter);
            }
            $cut->setName($fish->name);
            $cut->setLinkName( \Duelist101\BASE_URL . 'fish/' . urlencode($fish->name) );
            $cut->setImage( \Duelist101\BASE_URL . 'images/w101_fish/' . $fish->image);
            $cut->setClassName($fish->class->name);
            $cut->setInitialXp($fish->initialXp);
            $cut->setRarity($fish->rarity->name);
            // TODO: add rank?
            $this->add($cut);
        }
    }
}
