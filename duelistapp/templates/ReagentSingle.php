<?php
namespace Duelist101\Db\View;

Class ReagentSingle extends \StampTemplateEngine\StampTE
{
    public function parse( $reagent ) {

        $cut = $this->getReagent();
        $cut->setName($reagent->name);
        $cut->setImage($reagent->image);
        $cut->setClassName($reagent->class->name);
        $cut->setRank($reagent->rank);
        $cut->setDescription($reagent->description);
        $this->add($cut);
/*
        if ( true ) {	// TODO: change to any source != NULL

            $cut = $this->getAllSourcesHeader();
            $cut->setReagentName($reagent->name);
            $this->add($cut);

            if ( $reagent->ownAreaReagentSourceList[] != NULL ) {

                $cut = $this->getSourceHeader();
                $cut->setSourceType('Spawns In');
                $this->add($cut);

                foreach ($reagent->ownAreaReagentSourceList[] as $areaSource) {

                    $cut = $this->getSource();
                    $cut->setAreaUrl(urlencode( 'areas/' . $areaSource->area->name ) );
                    $cut->setSource($areaSource->area->name);
                    $cut->setVoteUpCount($areaSource->area->voteUp);
                    $cut->setVoteDownCount($areaSource->area->voteDown);
                    // TODO: implement voteup and votedown link logic, assuming post URL similar to below:
                    // in vote up/down, probably make sure only one vote per IP / user 
                    // $cut->setVoteUpUrl( urlencode( 'area-reagent-source/add-vote-up/' . $areaSource->id ) );
                    // $cut->setVoteDownUrl( urlencode( 'area-reagent-source/add-vote-down/' . $areaSource->id ) );
                    $this->add($cut);
                }

                $cut = $this->getSourceFooter();
                $this->add($cut);
            }
        }
*/

    }
}

