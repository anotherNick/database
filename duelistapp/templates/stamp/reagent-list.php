<?php
# Expects $reagents from redbean reagent list query.

	for ($i = 1; $i <= count($reagents); $i++) {
		$reagent = $reagents[$i];
		$cutReagent = $template->getReagent();
		if ( $i % 3 == 0 ) {
			$cutReagent->setLastOption('last');
			$cutReagent->injectRaw('lastFooter', '<div style="clear: both;"></div>');
		}
		$cutReagent->setName($reagent->name);
		$cutReagent->setLinkName( urlencode( $reagent->name ) );
		$cutReagent->setImage($reagent->image);
		$cutReagent->setClassName($reagent->class->name);
		$cutReagent->setRank($reagent->rank);
		$template->add($cutReagent);
	}

