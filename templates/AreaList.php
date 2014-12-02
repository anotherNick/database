<?php
namespace Duelist101\Db\View;

Class AreaList extends \Duelist101\Stamp
{
    public function parse( $worlds ) {
        
        $i = 0;
		
		foreach ($worlds as $world) {
			$i = $i + 1;
			$worldCut = $this->getWorld();
			$worldCut->setWorldImage( \Duelist101\BASE_URL . 'images/w101_world_maps/' . $world->getImage() );
			$worldCut->setWorldName( $world->getName() );
			if ( $i == 3 ) {
				$i = 0;
				$worldCut->setLastOption( 'last' );
				$worldCutLastFooter = $this->get( 'world.lastFooter' );
				$worldCut->add( $worldCutLastFooter );
			}
			
            $areas = $world->getAreas();
			foreach ($areas as $area) {
				$areasCut = $this->get( 'world.areas' );
				$areasCut->setAreaName( $area->getName() );
				$areasCut->setLinkName( \Duelist101\BASE_URL . 'areas/' . urlencode( $area->getName() ) );
				$worldCut->add($areasCut);
			}
			
			$this->add($worldCut);
		}
    }
}
