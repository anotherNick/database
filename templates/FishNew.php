<?php
namespace Duelist101\Db\View;

Class FishNew extends \Duelist101\Stamp
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
                , 'src' => \Duelist101\BASE_URL . 'vendor/select2/select2-built.js'
            )
            , array(
                'handle' => 'cropper-custom'
                , 'src' => \Duelist101\BASE_URL . 'vendor/cropper/cropper-built.js'
            )
            , array(
                'handle' => 'fish-new'
                , 'src' => \Duelist101\BASE_URL . 'js/fish-new.js'
                , 'deps' => array ( 'jquery-ui-core', 'select2', 'cropper-custom' )
                , 'in_footer' => true
            )
        );
    }
    
    public function getStyles()
    {
        return array(
            array(
                'handle' => 'select2'
                , 'src' => \Duelist101\BASE_URL . 'css/select2.css'
            )
            , array(
                'handle' => 'cropper-custom'
                , 'src' => \Duelist101\BASE_URL . 'vendor/cropper/dist/cropper.min.css'
            )
            , array(
                'handle' => 'fish-new'
                , 'src' => \Duelist101\BASE_URL . 'css/fish-new.css'
            )
        );
    }

    public function parse( )
    {

//        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            
            $cut = $this->getLink();
            $cut->setAddUrl( \Duelist101\BASE_URL . 'fish' );
            $this->add( $cut );
            
            $classNames = \W101\ClassnameQuery::create()->find();
            foreach( $classNames as $className ) {
                $cut = $this->getClassName();
                $cut->setId( $className->getId() );
                $cut->setName( $className->getName() );
                $this->add( $cut );
            }
            
            $rarities = \W101\RarityQuery::create()->find();
            foreach( $rarities as $rarity ) {
                $cut = $this->getRarity();
                $cut->setId( $rarity->getId() );
                $cut->setName( $rarity->getName() );
                $this->add( $cut );
            }

            $aquariums = \W101\HousingitemQuery::create()
                ->filterByCanHoldFish( true )
                ->find();
            foreach( $aquariums as $aquarium ) {
                $cut = $this->getAquarium();
                $cut->setId( $aquarium->getId() );
                $cut->setName( $aquarium->getName() );
                $this->add( $cut );
            }
                
//        }
        
/*        $cut = $this->getFish();
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
*/        
    }
}

