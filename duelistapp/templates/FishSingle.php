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
        $cut->setName($fish->name);
        $cut->setImage(\Duelist101\BASE_URL . 'images/w101_fish/' . $fish->image);
        $cut->setRank($fish->rank);
        $cut->setDescription($fish->description);
        $cut->setInitialXp($fish->initialXp);
        $cut->setRarityName($fish->rarity->name);
        $cut->setClassName($fish->class->name);
        $aquariums = $fish->sharedHousingitemList;
        if ( $aquariums == NULL ) {
            $cutAquarium = $this->get('fish.noAquariums');
            $cut->add($cutAquarium);
        } else {
            usort( $aquariums, function($a, $b) {
                return strcmp($a->name, $b->name);
            } );
            foreach ($aquariums as $aquarium) {
                $cutAquarium = $this->get('fish.aquarium');
                $cutAquarium->setAquariumUrl(\Duelist101\BASE_URL . 'housingitems/' . urlencode($aquarium->name));
                $cutAquarium->setAquariumName($aquarium->name);
                $cut->add($cutAquarium);
            }
        }
        $this->add($cut);

        // Areas
        $cut = $this->getSourceList();
        $cut->setHtmlId( 'areas' );
        $cut->setFishId( $fish->id );

        $areafishList = $fish->ownAreafishList;
        usort( $areafishList, function($a, $b) {
            return strcmp($a->area->name, $b->area->name);
        } );
        if ( empty($areafishList) ) {
            // TODO: this probably should be a stamp wrapped in a <p>
            $cut->setDefaultText('None.');
        } else {
            foreach ($areafishList as $areafish) {
                $cutSource = $this->get('sourceList.source');
                $cutSource->setHtmlId( 'areas' );
                $cutSource->setUrl( \Duelist101\BASE_URL . 'areas/' . urlencode( $areafish->area->name ) );
                $cutSource->setName( $areafish->area->name );
                $cutSource->setVotesUp($areafish->votesUp);
                $cutSource->setVotesDown($areafish->votesDown);
                $cutSource->setVoteUpUrl( \Duelist101\BASE_URL . 'areafish/' . urlencode($areafish->id) . '/vote-up' );
                $cutSource->setVoteDownUrl( \Duelist101\BASE_URL . 'areafish/' . urlencode($areafish->id) . '/vote-down' );
                $cut->add($cutSource);
            }
        }
        $this->add( $cut );
        
    }
}

