<?php
namespace Duelist101\Db\View;

Class FishSingle extends \Duelist101\Stamp
{
    public function getScripts()
    {
        return array(
            array( 
                'handle' => 'jquery-core'
                , 'src' => '//code.jquery.com/jquery-1.11.0.js'
            )
            , array(
                'handle' => 'jquery-ui-core'
                , 'src' => '//code.jquery.com/ui/1.11.0/jquery-ui.js'
                , 'deps' => array( 'jquery-core' )
            )
            , array( 
                'handle' => 'et-shortcodes-js'
                , 'src' => '//cdn8.duelist101.com/wp-content/plugins/et-shortcodes/js/et_shortcodes_frontend.js?ver=3.0'
                , 'deps' => array( 'jquery-core' )
            )
            , array(
                'handle' => 'select2'
                , 'src' => \Duelist101\BASE_URL . '/js/select2.js'
            )
            , array(
                'handle' => 'fish-single'
                , 'src' => \Duelist101\BASE_URL . '/js/fish-single.js'
                , 'deps' => array ( 'jquery-ui-core', 'select2' )
                , 'in_footer' => true
            )
        );
    }
    
    public function getStyles()
    {
        return array(
            array(
                'handle' => 'select2'
                , 'src' => \Duelist101\BASE_URL . '/css/select2.css'
            )
        );
    }

    public function parse( $fish )
    {

        $cut = $this->getFish();
        $cut->setName( $fish->getName() );
        $cut->setImage(\Duelist101\BASE_URL . 'images/w101_fish/' . $fish->getImage() );
        $cut->setRank( $fish->getRank() );
        $cut->setDescription( $fish->getDescription() );
        $cut->setInitialXp( $fish->getInitialXP() );
		$rarity = $fish->getRarity();
        $cut->setRarityName( $rarity->getName() );
		$class = $fish->getClassname();
        $cut->setClassName( $class->getName() );
        $aquariums = $fish->getFishhousingitems();
        if ( $aquariums == NULL ) {
            $cutAquarium = $this->get('fish.noAquariums');
            $cut->add($cutAquarium);
        } else {
            foreach ($aquariums as $aquarium) {
                $cutAquarium = $this->get('fish.aquarium');
				$housingitem = $aquarium->getHousingitem();
                $cutAquarium->setAquariumUrl(\Duelist101\BASE_URL . 'housingitems/' . urlencode( $housingitem->getName() ));
                $cutAquarium->setAquariumName( $housingitem->getName() );
                $cut->add($cutAquarium);
            }
        }

        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cutEditLink = $this->get('fish.editLink');
            $cutEditLink->setEditLink( \Duelist101\BASE_URL . 'fish/' . urlencode( $fish->getName() ) . '/edit' );
            $cut->add( $cutEditLink );
        }
        
        $this->add($cut);

        // Areas
        $cut = $this->getSourceList();
        $cut->setHtmlId( 'areas' );

        $areafishList = $fish->getAreafishes();
        if ( empty($areafishList) ) {
            // TODO: this probably should be a stamp wrapped in a <p>
            $cut->setDefaultText('None.');
        } else {
            foreach ($areafishList as $areafish) {
                $cutSource = $this->get('sourceList.source');
                $cutSource->setHtmlId( 'areas' );
				$afArea = $areafish->getArea();
                $cutSource->setUrl( \Duelist101\BASE_URL . 'areas/' . urlencode( $afArea->getName() ) );
                $cutSource->setName( $afArea->getName() );
                $cut->add($cutSource);
            }
        }
        
        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cutAddLink = $this->get( 'sourceList.addLink' );
            $cutAddLink->setHtmlId( 'areas' );
            $cutAddLink->setListUrl( \Duelist101\BASE_URL . 'areas.json' );
            $cutAddLink->setAddUrl( \Duelist101\BASE_URL . 'areafish' );
            $cutAddLink->setFishId( $fish->getId() );
            $cut->add($cutAddLink);
        }
        $this->add( $cut );
        
    }
}

