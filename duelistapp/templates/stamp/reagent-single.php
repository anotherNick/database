<?php
		$cutReagent = $template->getReagent();
		if ( $i % 3 == 0 ) {
			$cutReagent->setLastOption('last');
			$cutReagent->injectRaw('lastFooter', '<div style="clear: both;"></div>');
		}
		$cutReagent->setName($reagent->name);
		$cutReagent->setIdAndName( urlencode( $reagent->name ) );
		$cutReagent->setImage($reagent->image);
		$cutReagent->setClassName($reagent->class->name);
		$cutReagent->setRank($reagent->rank);
		$cutReagent->setDescription($reagent->description);
		$template->add($cutReagent);

