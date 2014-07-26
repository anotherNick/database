<?php
namespace Duelist101\Db\View;

Class AreaList extends \Duelist101\Stamp
{
    public function parse( $worlds ) {
        
        $i = 0;
		
		foreach ($worlds as $world) {
			$i = $i + 1;
			$worldCut = $this->getWorld();
			$worldCut->setWorldImage($world->image);
			$worldCut->setWorldName($world->name);
			if ( $i == 3 ) {
				$i = 0;
				$worldCut->setLastOption('last');
				$worldCutLastFooter = $this->get('world.lastFooter');
				$worldCut->add($worldCutLastFooter);
			}
			
            $areas = $world->with( 'ORDER BY name' )->ownAreaList;
			foreach ($areas as $area) {
				$areasCut = $this->get( 'world.areas' );
				$areasCut->setAreaName( $area->name );
				$areasCut->setLinkName( \Duelist101\BASE_URL . 'areas/' . urlencode($area->name) );
				$worldCut->add($areasCut);
			}
			
			$this->add($worldCut);
		}
    }
}
