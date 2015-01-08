<?php
namespace Duelist101\Db\View;

Class ReagentSingle extends \Duelist101\Stamp
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
                'handle' => 'reagent-single'
                , 'src' => \Duelist101\BASE_URL . '/js/reagent-single.js'
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

    public function parse( $reagent )
    {

        $cut = $this->getReagent();
        $cut->setName( $reagent->getName()) ;
        $cut->setImage( \Duelist101\BASE_URL . 'images/w101_reagents/' . $reagent->getImage() );
		$school = $reagent->getSchool();
		// Workaround because Merle's Whisker has no school.
		if(isSet($school)){
			$className = $school->getName();
		}else{
			$className = '';
		}
        $cut->setClassName( $className );
        $cut->setRank( $reagent->getRank() );
        $cut->setDescription( $reagent->getDescription() );
        $this->add( $cut );

        // Areas
        $cut = $this->getSourceList();
        $cut->setHtmlId( 'areas' );
        
        $areareagents = $reagent->getAreareagents();

        if ( empty($areareagents) ) {
            // TODO: this probably should be a stamp wrapped in a <p>
            $cut->setDefaultText('None.');
        } else {
            foreach ( $areareagents as $areareagent ) {
				$area = $areareagent->getArea();
                $cutSource = $this->get( 'sourceList.source' );
                $cutSource->setHtmlId( 'areas' );
                $cutSource->setUrl( \Duelist101\BASE_URL . 'areas/' . urlencode( $area->getName() ) );
                $cutSource->setName( $area->getName() );
                $cut->add( $cutSource );
            }
        }

        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cutAddLink = $this->get( 'sourceList.addLink' );
            $cutAddLink->setHtmlId( 'areas' );
            $cutAddLink->setListUrl( \Duelist101\BASE_URL . 'areas.json' );
            $cutAddLink->setAddUrl( \Duelist101\BASE_URL . 'areareagents' );
            $cutAddLink->setReagentId( $reagent->getId() );
            $cut->add($cutAddLink);
        }
        $this->add( $cut );

        // Creatures
        $cut = $this->getSourceList();
            $cut->setHtmlId( 'creatures' );
            $cut->setReagentId( $reagent->getId() );
            $cutMessage = $this->get( 'sourceList.message' );
            $cutMessage->setMessage( 'To come.  Muhahahaha!' );
            $cut->add( $cutMessage );

        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cutAddLink = $this->get( 'sourceList.addLink' );
            $cutAddLink->setHtmlId( 'creatures' );
            $cutAddLink->setListUrl( \Duelist101\BASE_URL . 'areas.json' );
            $cutAddLink->setAddUrl( \Duelist101\BASE_URL . 'areareagents' );
            $cutAddLink->setReagentId( $reagent->getId() );
            $cut->add($cutAddLink);
        }
        $this->add( $cut );

        // Crown Shop
        $cut = $this->getSourceList();
            $cut->setHtmlId( 'crowns' );
            $cut->setReagentId( $reagent->getId() );
            $cutMessage = $this->get( 'sourceList.message' );
            $cutMessage->setMessage( 'To come.  Muhahahaha!' );
            $cut->add( $cutMessage );

        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cutAddLink = $this->get( 'sourceList.addLink' );
            $cutAddLink->setHtmlId( 'creatures' );
            $cutAddLink->setListUrl( \Duelist101\BASE_URL . 'areas.json' );
            $cutAddLink->setAddUrl( \Duelist101\BASE_URL . 'areareagents' );
            $cutAddLink->setReagentId( $reagent->getId() );
            $cut->add($cutAddLink);
        }
        $this->add( $cut );

        // Plants
        $cut = $this->getSourceList();
            $cut->setHtmlId( 'plants' );
            $cut->setReagentId( $reagent->getId() );
            $cutMessage = $this->get( 'sourceList.message' );
            $cutMessage->setMessage( 'To come.  Muhahahaha!' );
            $cut->add( $cutMessage );

        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cutAddLink = $this->get( 'sourceList.addLink' );
            $cutAddLink->setHtmlId( 'plants' );
            $cutAddLink->setListUrl( \Duelist101\BASE_URL . 'areas.json' );
            $cutAddLink->setAddUrl( \Duelist101\BASE_URL . 'areareagents' );
            $cutAddLink->setReagentId( $reagent->getId() );
            $cut->add($cutAddLink);
        }
        $this->add( $cut );

        // Vendors
        $cut = $this->getSourceList();
            $cut->setHtmlId( 'vendors' );
            $cut->setReagentId( $reagent->getId() );
            $cutMessage = $this->get( 'sourceList.message' );
            $cutMessage->setMessage( 'To come.  Muhahahaha!' );
            $cut->add( $cutMessage );

        if ( is_user_logged_in() && current_user_can('edit_posts') ) {
            $cutAddLink = $this->get( 'sourceList.addLink' );
            $cutAddLink->setHtmlId( 'vendors' );
            $cutAddLink->setListUrl( \Duelist101\BASE_URL . 'areas.json' );
            $cutAddLink->setAddUrl( \Duelist101\BASE_URL . 'areareagents' );
            $cutAddLink->setReagentId( $reagent->getId() );
            $cut->add($cutAddLink);
        }
        $this->add( $cut );
        
    }
}

