<?php
namespace Duelist101\Db\View;

Class ReagentSingle extends \Duelist101\Stamp
{
    public function parse( $reagent, $baseUrl ) {

        $cut = $this->getReagent();
        $cut->setName($reagent->name);
        $cut->setImage($reagent->image);
        $cut->setClassName($reagent->class->name);
        $cut->setRank($reagent->rank);
        $cut->setDescription($reagent->description);
        $this->add($cut);

        // Areas
        $cut = $this->getSourceList();
        $areareagents = $reagent->ownAreareagentList;
        if ( empty($areareagents) ) {
            // TODO: this probably should be a stamp wrapped in a <p>
            $cut->setDefaultText('None.');
        } else {
            foreach ($areareagents as $areareagent) {
                $cutSource = $this->get('sourceList.source');
                $cutSource->setUrl( $baseUrl . '/areas/' . urlencode( $areareagent->area->name ) );
                $cutSource->setName( $areareagent->area->name);
                $cutSource->setVoteUpCount($areareagent->voteUp);
                $cutSource->setVoteDownCount($areareagent->voteDown);
                // TODO: implement voteup and votedown link logic, assuming post URL similar to below:
                // in vote up/down, probably make sure only one vote per IP / user 
                $cutSource->setVoteUpUrl( urlencode( 'vote/up/areareagent/' . $areareagent->id ) );
                $cutSource->setVoteDownUrl( urlencode( 'vote/down/areareagent/' . $areareagent->id ) );
                $cut->add($cutSource);
            }
        }
        // TODO: add button here
        $this->add( $cut );

        // Creatures
        $cut = $this->getSourceList();
            $cut->setDefaultText('To come.  Muhahahaha!');
        $this->add( $cut );

        // Crown Shop
        $cut = $this->getSourceList();
            $cut->setDefaultText('To come.  Muhahahaha!');
        $this->add( $cut );

        // Plants
        $cut = $this->getSourceList();
            $cut->setDefaultText('To come.  Muhahahaha!');
        $this->add( $cut );

        // Vendors
        $cut = $this->getSourceList();
            $cut->setDefaultText('To come.  Muhahahaha!');
        $this->add( $cut );
        
    }
}

