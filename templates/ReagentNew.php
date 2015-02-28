<?php
namespace Duelist101\Db\View;

Class ReagentNew extends \Duelist101\Stamp
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
                'handle' => 'reagent-new'
                , 'src' => \Duelist101\BASE_URL . 'js/new-item.js'
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
                , 'src' => \Duelist101\BASE_URL . 'css/new-item.css'
            )
        );
    }

    public function parse( )
    {

//        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            
            $cut = $this->getLink();
            $cut->setAddUrl( \Duelist101\BASE_URL . 'reagents' );
            $this->add( $cut );
            
            $classNames = \W101\ClassnameQuery::create()->find();
            foreach( $classNames as $className ) {
                $cut = $this->getClassName();
                $cut->setId( $className->getId() );
                $cut->setName( $className->getName() );
                $this->add( $cut );
            }
                     
    }
}

