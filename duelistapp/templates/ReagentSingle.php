<?php
namespace Duelist101\Db\View;

Class ReagentSingle extends \Duelist101\Stamp
{
    public function parse( $reagent ) {

        $cut = $this->getReagent();
        $cut->setName($reagent->name);
        $cut->setImage($reagent->image);
        $cut->setClassName($reagent->class->name);
        $cut->setRank($reagent->rank);
        $cut->setDescription($reagent->description);
        $this->add($cut);

        // TODO: add other sources to this criteria when added
        $areaReagents = $reagent->ownAreaReagentList;
        if ( $areaReagents != NULL ) {	

            $cut = $this->getAllSourcesHeader();
            $cut->setReagentName($reagent->name);
            $this->add( $cut );

            if ( $areaReagents != NULL ) {

                $cut = $this->getSourceHeader();
                $cut->setSourceType('Spawns In');
                $this->add( $cut );

                foreach ($areaReagents as $areaReagent) {

                    $cut = $this->getSource();
                    $cut->setAreaUrl( 'areas/' . urlencode( $areaReagent->area->name ) );
                    $cut->setSource($areaReagent->area->name);
                    $cut->setVoteUpCount($areaReagent->voteUp);
                    $cut->setVoteDownCount($areaReagent->voteDown);
                    // TODO: implement voteup and votedown link logic, assuming post URL similar to below:
                    // in vote up/down, probably make sure only one vote per IP / user 
                    $cut->setVoteUpUrl( urlencode( 'add-vote-up/areareagent/' . $areaReagent->id ) );
                    $cut->setVoteDownUrl( urlencode( 'add-vote-down/areareagent/' . $areaReagent->id ) );
                    $this->add( $cut );
                }

                $cut = $this->getSourceFooter();
                $this->add( $cut );
            }
        }
    }

}

