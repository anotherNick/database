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
			
			$areas = \R::find( 'area', ' world_id = ? ', array( $world->id ) );
		
			foreach ($areas as $area) {
				$areasCut = $this->get( 'world.areas' );
				$areasCut->setAreaName( $area->name );
				$areasCut->setLinkName( urlencode($area->name) );
				$worldCut->add($areasCut);
			}
			
			$this->add($worldCut);
		}
    }
}