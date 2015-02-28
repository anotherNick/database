<?php
namespace Duelist101\Db\View;

Class FishNewOrEdit extends \Duelist101\Stamp
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
                'handle' => 'fish-new-or-edit'
                , 'src' => \Duelist101\BASE_URL . 'js/fish-new-or-edit.js'
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
                'handle' => 'fish-new-or-edit'
                , 'src' => \Duelist101\BASE_URL . 'css/fish-new-or-edit.css'
            )
        );
    }

    public function parse( $fish )
    {
        $cut = $this->getForm();
        $cut->setAddUrl( \Duelist101\BASE_URL . 'fish' );
        if ( $fish !== null ) {
            $cut->setTitle( 'Edit ' . $fish->getName() . ' Fish' );
            $cut->setId( $fish->getId() );
            $cut->setName( $fish->getName() );
            $cut->setInitialXp( $fish->getInitialXp() );
            $cut->setRank( $fish->getRank() );
            $cut->setDescription( $fish->getDescription() );
            $cut->setImage( \Duelist101\BASE_URL . 'images/w101_fish/' . $fish->getImage() );
            $cut->setClass( 'current' );
        } else {
            $cut->setTitle( 'Add a Fish' );
            $cut->injectRaw('blankOption', '<option selected="selected" value=""></option>');
            $cut->setImage( \Duelist101\BASE_URL . 'images/kevinator.jpg' );
            $cut->setClass( 'default' );
        }
        
        $classNames = \W101\ClassnameQuery::create()->find();
        foreach( $classNames as $className ) {
            $cutClassName = $this->get( 'form.className' );
            $cutClassName->setId( $className->getId() );
            $cutClassName->setName( $className->getName() );
            if ( $fish !== null ) {
                if ( $className->getId() == $fish->getClassId() ) {
                    $cutClassName->injectAttr('selectedClassname', 'selected="selected"', true);
                }
            }
            $cut->add( $cutClassName );
        }
        
        $rarities = \W101\RarityQuery::create()->find();
        foreach( $rarities as $rarity ) {
            $cutRarity = $this->get( 'form.rarity' );
            $cutRarity->setId( $rarity->getId() );
            $cutRarity->setName( $rarity->getName() );
            if ( $fish !== null ) {
                if ( $rarity->getId() == $fish->getRarityId() ) {
                    $cutRarity->injectAttr('selectedRarity', 'selected="selected"', true);
                }
            }
            $cut->add( $cutRarity );
        }

        if ( $fish !== null ) {
            $selectedAquariums = \W101\FishHousingitemQuery::create()
                ->filterByFishId( $fish->getId() )
                ->select('HousingitemId')
                ->find()
                ->toArray();
        } else {
            $selectedAquariums = array();
        }
            
        $aquariums = \W101\HousingitemQuery::create()
            ->filterByCanHoldFish( true )
            ->find();
        foreach( $aquariums as $aquarium ) {
            $cutAquarium = $this->get( 'form.aquarium' );
            $cutAquarium->setId( $aquarium->getId() );
            $cutAquarium->setName( $aquarium->getName() );
            if ( in_array( $aquarium->getId(), $selectedAquariums ) ) {
                $cutAquarium->injectAttr('selectedAquarium', 'selected="selected"', true);
            }
            $cut->add( $cutAquarium );
        }

        $this->add( $cut );
    }
}
