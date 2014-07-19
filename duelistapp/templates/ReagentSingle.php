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

    public function parse( $reagent, $areas )
    {

        $cut = $this->getReagent();
        $cut->setName($reagent->name);
        $cut->setImage($reagent->image);
        $cut->setClassName($reagent->class->name);
        $cut->setRank($reagent->rank);
        $cut->setDescription($reagent->description);
        $this->add($cut);

        // Areas
        $cut = $this->getSourceList();
        $cut->setHtmlId( 'areas' );
        $cut->setReagentId( $reagent->id );
        
        // TODO: this needs to be sorted
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
                $cutSource->setUrl( \Duelist101\BASE_URL . '/areas/' . urlencode( $areareagent->area->name ) );
                $cutSource->setName( $areareagent->area->name );
                $cutSource->setVoteUpCount($areareagent->voteUp);
                $cutSource->setVoteDownCount($areareagent->voteDown);
                // TODO: implement voteup and votedown link logic, assuming post URL similar to below:
                // in vote up/down, probably make sure only one vote per IP / user 
                $cutSource->setVoteUpUrl( \Duelist101\BASE_URL . '/areareagent/vote-up/' . urlencode($areareagent->id) );
                $cutSource->setVoteDownUrl( \Duelist101\BASE_URL . '/areareagent/vote-down/' . urlencode($areareagent->id) );
                $cut->add($cutSource);
            }
        }
        if ( !empty($areas) ) {
            foreach ($areas as $area) {
                $cutAddItem = $this->get('sourceList.addItem');
                $cutAddItem->setItemId( $area->id );
                $cutAddItem->setItemName( $area->name );
                $cut->add($cutAddItem);
            }
        }
        $this->add( $cut );

        // Creatures
        $cut = $this->getSourceList();
            $cutMessage = $this->get('sourceList.message');
            $cutMessage->setMessage('To come.  Muhahahaha!');
            $cut->add($cutMessage);
        $this->add( $cut );

        // Crown Shop
        $cut = $this->getSourceList();
            $cutMessage = $this->get('sourceList.message');
            $cutMessage->setMessage('To come.  Muhahahaha!');
            $cut->add($cutMessage);
        $this->add( $cut );

        // Plants
        $cut = $this->getSourceList();
            $cutMessage = $this->get('sourceList.message');
            $cutMessage->setMessage('To come.  Muhahahaha!');
            $cut->add($cutMessage);
        $this->add( $cut );

        // Vendors
        $cut = $this->getSourceList();
            $cutMessage = $this->get('sourceList.message');
            $cutMessage->setMessage('To come.  Muhahahaha!');
            $cut->add($cutMessage);
        $this->add( $cut );
        
    }
}

