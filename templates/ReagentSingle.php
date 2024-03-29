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
        $cut->setName($reagent->name);
        $cut->setImage(\Duelist101\BASE_URL . 'images/w101_reagents/' . $reagent->image);
        $cut->setClassName($reagent->class->name);
        $cut->setRank($reagent->rank);
        $cut->setDescription($reagent->description);
        $this->add($cut);

        // Areas
        $cut = $this->getSourceList();
        $cut->setHtmlId( 'areas' );
        $cut->setListUrl( \Duelist101\BASE_URL . 'areas.json' );
        $cut->setAddUrl( \Duelist101\BASE_URL . 'areareagents' );
        $cut->setReagentId( $reagent->id );
        
        $areareagents = $reagent->ownAreareagentList;
        usort( $areareagents, function($a, $b) {
            return strcmp($a->area->name, $b->area->name);
        } );
        if ( empty($areareagents) ) {
            // TODO: this probably should be a stamp wrapped in a <p>
            $cut->setDefaultText('None.');
        } else {
            foreach ($areareagents as $areareagent) {
                $cutSource = $this->get('sourceList.source');
                $cutSource->setHtmlId( 'areas' );
                $cutSource->setUrl( \Duelist101\BASE_URL . 'areas/' . urlencode( $areareagent->area->name ) );
                $cutSource->setName( $areareagent->area->name );
                $cutSource->setVotesUp($areareagent->votesUp);
                $cutSource->setVotesDown($areareagent->votesDown);
                $cutSource->setVoteUpUrl( \Duelist101\BASE_URL . 'areareagents/' . urlencode($areareagent->id) . '/vote-up' );
                $cutSource->setVoteDownUrl( \Duelist101\BASE_URL . 'areareagents/' . urlencode($areareagent->id) . '/vote-down' );
                $cut->add($cutSource);
            }
        }
        $this->add( $cut );

        // Creatures
        $cut = $this->getSourceList();
            $cut->setHtmlId( 'creatures' );
            $cut->setReagentId( $reagent->id );
            $cutMessage = $this->get('sourceList.message');
            $cutMessage->setMessage('To come.  Muhahahaha!');
            $cut->add($cutMessage);
        $this->add( $cut );

        // Crown Shop
        $cut = $this->getSourceList();
            $cut->setHtmlId( 'crowns' );
            $cut->setReagentId( $reagent->id );
            $cutMessage = $this->get('sourceList.message');
            $cutMessage->setMessage('To come.  Muhahahaha!');
            $cut->add($cutMessage);
        $this->add( $cut );

        // Plants
        $cut = $this->getSourceList();
            $cut->setHtmlId( 'plants' );
            $cut->setReagentId( $reagent->id );
            $cutMessage = $this->get('sourceList.message');
            $cutMessage->setMessage('To come.  Muhahahaha!');
            $cut->add($cutMessage);
        $this->add( $cut );

        // Vendors
        $cut = $this->getSourceList();
            $cut->setHtmlId( 'vendors' );
            $cut->setReagentId( $reagent->id );
            $cutMessage = $this->get('sourceList.message');
            $cutMessage->setMessage('To come.  Muhahahaha!');
            $cut->add($cutMessage);
        $this->add( $cut );
        
    }
}

