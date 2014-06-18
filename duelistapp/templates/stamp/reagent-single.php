<?php
# Expects $reagent from Redbean single reagent query.

		$cutReagent = $template->getReagent();
		
		$cutReagent->setName($reagent->name);
		$cutReagent->setImage($reagent->image);
		$cutReagent->setClassName($reagent->class->name);
		$cutReagent->setRank($reagent->rank);
		$cutReagent->setDescription($reagent->description);
		$template->add($cutReagent);

