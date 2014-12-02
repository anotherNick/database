<?php
namespace Duelist101\Db\View;

Class AreaSingle extends \Duelist101\Stamp
{
    public function getScripts()
    {
        return array(
            array( 
                'handle' => 'jquery-core'
                , 'src' => '//code.jquery.com/jquery-1.11.0.js'
            )
            , array(
                'handle' => 'jquery-ui-duelist-core'
                , 'src' => '//code.jquery.com/ui/1.11.0/jquery-ui.js'
                , 'deps' => array( 'jquery-core' )
            )
            , array( 
                'handle' => 'et-shortcodes-js'
                , 'src' => '//cdn8.duelist101.com/wp-content/plugins/et-shortcodes/js/et_shortcodes_frontend.js?ver=3.0'
                , 'deps' => array( 'jquery-core' )
            )
            , array(
                'handle' => 'select2'
                , 'src' => \Duelist101\BASE_URL . 'js/select2.js'
            )
            , array(
                'handle' => 'area-single'
                , 'src' => \Duelist101\BASE_URL . 'js/area-single.js'
                , 'deps' => array ( 'jquery-ui-core', 'select2' )
                , 'in_footer' => true
            )
        );
    }
	
    public function getStyles()
    {
        return array(
            array(
                'handle' => 'jqueryui-css'
                , 'src' => '//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css'
            ),
            array(
                'handle' => 'select2'
                , 'src' => \Duelist101\BASE_URL . 'css/select2.css'
            ),
            array(
                'handle' => 'area-single'
                , 'src' => \Duelist101\BASE_URL . 'css/area-single.css'
            )
        );
    }
	
	public function getSpawnTypes()
	{
		// Define Spawn Items. table => spawnDisplayName
		// Eventually would like this to be more automated.
		return array(
				'reagent' => 'Reagents',
				'fish' => 'Fish'
			);
	}

    public function parse( $area )
    {
        $cut = $this->getArea();
        $cut->setName( $area->getName() );
        $cut->setImage( \Duelist101\BASE_URL . 'images/w101_world_maps/' . $area->getImage() );

        $this->add( $cut );
		
		$spawns = $this->getSpawnTypes();
		foreach( $spawns as $spawnTable => $spawnDisplayName ){
			// Create Spawn Tab
			$cut = $this->getSpawnTab();
			$cut->setSpawnDisplayName( $spawnDisplayName );
			$this->add($cut);
			
			// Create Spawn Add Form
			$cut = $this->getSpawnContent();
			$cut->setAreaMapImage( \Duelist101\BASE_URL . 'images/w101_world_maps/' . $area->getImage() );
			$cut->setAreaId( $area->getId() );
			$cut->setSpawnTable( $spawnTable );
			$cut->setSpawnDisplayName( $spawnDisplayName );
			$cut->setSpawnDisplayTable( ucfirst( $spawnTable ) );
			$cut->setSpawnFormSelectUrl( \Duelist101\BASE_URL . strtolower( $spawnDisplayName ) . '.json' );
			$cut->setSpawnFormAction( \Duelist101\BASE_URL . 'area' . $spawnTable . 'spawns' );
			$cut->setSpawnAddLoadingImage( \Duelist101\BASE_URL . 'css/kevin-hop-loading-3.gif');

			// Set Spawn Item
			// Workaround for Propel Pluralization until I figure out something.
			if( $spawnTable == 'fish' ){
				$pSpawn = 'fishes';
			}else{
				$pSpawn = $spawnTable . 's';
			}
			$spawnItems = 'getArea' . $pSpawn;
			$spawnItems = $area->$spawnItems();
			foreach( $spawnItems as $spawnItem ){
				$getThisSpawnItem = 'get' . ucfirst( $spawnTable );
				$thisSpawnItem = $spawnItem->$getThisSpawnItem();
				$cutSpawnItem = $this->get( 'spawnContent.spawnItem' );
				$cutSpawnItem->setSpawnItem( $thisSpawnItem->getName() );
				$cutSpawnItem->setHtmlSpawnItem( str_replace( array( ' ', "'", '"', '-' ), '_', $thisSpawnItem->getName() ) );
				$cutSpawnItem->setSpawnItemUrl( \Duelist101\BASE_URL . strtolower( $spawnDisplayName ) . '/' . urlencode( $thisSpawnItem->getName() ) );
				$cutSpawnItem->setVotesUp( $spawnItem->getVotesUp() );
				$cutSpawnItem->setVotesDown( $spawnItem->getVotesDown() );
				$cutSpawnItem->setSpawnItemVoteUpUrl( \Duelist101\BASE_URL . 'area' . strtolower( $spawnDisplayName ) . '/' . urlencode( $spawnItem->getId() ) . '/vote-up' );
				$cutSpawnItem->setSpawnItemVoteDownUrl( \Duelist101\BASE_URL . 'area' . strtolower( $spawnDisplayName ) . '/' . urlencode( $spawnItem->getId() ) . '/vote-down' );
				// Set Spawn Points for this Spawn Type
				$getSpawnPoints = 'getArea' . $spawnTable . 'spawns';
				$spawnPoints = $spawnItem->$getSpawnPoints();
				foreach( $spawnPoints as $spawnPoint ){
					$cutSpawnPoint = $this->get( 'spawnContent.spawnItem.spawnPoint' );
					$cutSpawnPoint->setHtmlSpawnType( str_replace( array( ' ', "'", '"', '-' ), '_', $thisSpawnItem->getName() ) );
					$cutSpawnPoint->setSpawnPointID( $spawnPoint->getId() );
					$cutSpawnPoint->setSpawnPointX( $spawnPoint->getXLoc() );
					$cutSpawnPoint->setSpawnPointY( $spawnPoint->getYLoc() );
					$cutSpawnPoint->setVotesUp( $spawnPoint->getVotesUp() );
					$cutSpawnPoint->setVotesDown( $spawnPoint->getVotesDown() );
					$cutSpawnPoint->setSpawnPointVoteUpUrl( \Duelist101\BASE_URL . 'area' . $spawnTable . 'spawns/' . urlencode( $spawnPoint->getId() ) . '/vote-up' );
					$cutSpawnPoint->setSpawnPointVoteDownUrl( \Duelist101\BASE_URL . 'area' . $spawnTable . 'spawns/' . urlencode( $spawnPoint->getId() ) . '/vote-down' );
					$cutSpawnItem->add($cutSpawnPoint);
				}
				
				$cut->add($cutSpawnItem);
			}
			
			$this->add($cut);
		}
		
		// Inject the Eyecon URL.
		$cut = $this->getEyecon();
        $cut->setEyecon( \Duelist101\BASE_URL . 'css/viewable.png' );
		$cut->setEyeconActive( \Duelist101\BASE_URL . 'css/viewing.png' );
		$this->add($cut);

    }
}
