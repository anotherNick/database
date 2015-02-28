<?php
namespace Duelist101\Db\View;

Class FishList extends \Duelist101\Stamp
{
    public function parse( $fishList ) {
        
        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cut = $this->getAddLink1();
            $cut->setAddLink( \Duelist101\BASE_URL . 'fish/new' );
            $this->add($cut);
        }

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
            $cut->setName( $fish->getName() );
            $cut->setLinkName( \Duelist101\BASE_URL . 'fish/' . urlencode( $fish->getName() ) );
            $cut->setImage( \Duelist101\BASE_URL . 'images/w101_fish/' . $fish->getImage() );
			$class = $fish->getClassname();
            $cut->setClassName( $class->getName() );
            $cut->setInitialXp( $fish->getInitialXp() );
			$rarity = $fish->getRarity();
            $cut->setRarity( $rarity->getName() );
            // TODO: add rank?
            $this->add($cut);
        }

        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cut = $this->getAddLink2();
            $cut->setAddLink( \Duelist101\BASE_URL . 'fish/new' );
            $this->add($cut);
        }

    }
}
