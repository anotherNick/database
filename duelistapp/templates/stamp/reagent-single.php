<?php
# Expects $reagent, $spawns from Redbean single reagent query.

		// Set Reagent Info
		$cutReagent = $template->getReagent();
		
		$cutReagent->setName($reagent->name);
		$cutReagent->setImage($reagent->image);
		$cutReagent->setClassName($reagent->class->name);
		$cutReagent->setRank($reagent->rank);
		$cutReagent->setDescription($reagent->description);
		$template->add($cutReagent);
		
		// Set Reagent Name in Main Sources Title
		$cutSources = $template->getSources();
		
		$cutSources->setReagentName($reagent->name);
			// Set This Specific Source Title
			$cutSource = $template->get('sources.source');
			
			$cutSource->setSourceType('Spawns In');
				// Set This Source Links
				$cutSourceLinks = $template->get('sources.source.sourceLinks');
				
				for ($i = 1; $i <= count($spawns); $i++) {
					$spawn = $spawns[$i];
					$cutSourceLinks->setUrl('blah');
					$cutSourceLinks->setSource('Unicorn Way');
				}
				$cutSource->add($cutSourceLinks);
			$cutSources->add($cutSource);
		$template->add($cutSources);

