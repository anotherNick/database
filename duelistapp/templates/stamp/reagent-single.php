<?php
# Expects $reagent, $spawns from Redbean single reagent query.

	$cut = $template->getReagent();
	$cut->setName($reagent->name);
	$cut->setImage($reagent->image);
	$cut->setClassName($reagent->class->name);
	$cut->setRank($reagent->rank);
	$cut->setDescription($reagent->description);
	$template->add($cut);

	if ( true ) {	// change to any source != NULL

		$cut = $template->getAllSourcesHeader();
		$cut->setReagentName($reagent->name);
		$template->add($cut);

//		if ( $spawns != NULL ) {

			$cut = $template->getSourceHeader();
			$cut->setSourceType('Spawns In');
			$template->add($cut);

//			foreach ($spawns as $spawn) {

				$cut = $template->getSource();
				$cut->setUrl('blah');
				$cut->setSource('Unicorn Way');
				$template->add($cut);
//			}

			$cut = $template->getSourceFooter();
			$template->add($cut);
//		}
	}

