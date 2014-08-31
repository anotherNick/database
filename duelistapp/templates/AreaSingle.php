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
        $cut->setName( $area->name );
        $cut->setImage( \Duelist101\BASE_URL . 'images/w101_world_maps/' . $area->image );
        $this->add( $cut );
		
		$spawns = $this->getSpawnTypes();
		foreach( $spawns as $spawnTable => $spawnDisplayName ){
			// Create Spawn Tab
			$cut = $this->getSpawnTab();
			$cut->setSpawnDisplayName( $spawnDisplayName );
			$this->add($cut);
			
			// Create Spawn Add Form
			$cut = $this->getSpawnContent();
			$cut->setAreaMapImage( \Duelist101\BASE_URL . 'images/w101_world_maps/' . $area->image );
			$cut->setAreaId( $area->id );
			$cut->setSpawnTable( $spawnTable );
			$cut->setSpawnDisplayName( $spawnDisplayName );
			$cut->setSpawnDisplayTable( ucfirst( $spawnTable ) );
			$cut->setSpawnFormSelectUrl( \Duelist101\BASE_URL . strtolower( $spawnDisplayName ) . '.json' );
			$cut->setSpawnFormAction( \Duelist101\BASE_URL . 'area' . $spawnTable . 'spawns' );
			$cut->setSpawnAddLoadingImage( \Duelist101\BASE_URL . 'css/kevin-hop-loading-3.gif');

			// Set Spawn Item
			$spawnItems = 'ownArea' . $spawnTable . 'List';
			$spawnItems = $area->$spawnItems;
			foreach( $spawnItems as $spawnItem ){
				$cutSpawnItem = $this->get( 'spawnContent.spawnItem' );
				$cutSpawnItem->setSpawnItem( $spawnItem->$spawnTable->name );
				$cutSpawnItem->setHtmlSpawnItem( str_replace( [ ' ', "'", '"', '-' ], '_', $spawnItem->$spawnTable->name ) );
				$cutSpawnItem->setSpawnItemUrl( \Duelist101\BASE_URL . strtolower( $spawnDisplayName ) . '/' . urlencode( $spawnItem->$spawnTable->name ) );
				$cutSpawnItem->setVotesUp( $spawnItem->votes_up );
				$cutSpawnItem->setVotesDown( $spawnItem->votes_down );
				$cutSpawnItem->setSpawnItemVoteUpUrl( \Duelist101\BASE_URL . 'area' . strtolower( $spawnDisplayName ) . '/' . urlencode($spawnItem->id) . '/vote-up' );
				$cutSpawnItem->setSpawnItemVoteDownUrl( \Duelist101\BASE_URL . 'area' . strtolower( $spawnDisplayName ) . '/' . urlencode($spawnItem->id) . '/vote-down' );
				// Set Spawn Points for this Spawn Type
				$spawnPoints = 'ownArea' . $spawnTable . 'spawnList';
				$spawnPoints = $spawnItem->$spawnPoints;
				foreach( $spawnPoints as $spawnPoint ){
					$cutSpawnPoint = $this->get( 'spawnContent.spawnItem.spawnPoint' );
					$cutSpawnPoint->setHtmlSpawnType( str_replace( [ ' ', "'", '"', '-' ], '_', $spawnItem->$spawnTable->name ) );
					$cutSpawnPoint->setSpawnPointID( $spawnPoint->id );
					$cutSpawnPoint->setSpawnPointX( $spawnPoint->x_loc );
					$cutSpawnPoint->setSpawnPointY( $spawnPoint->y_loc );
					$cutSpawnPoint->setVotesUp( $spawnPoint->votes_up );
					$cutSpawnPoint->setVotesDown( $spawnPoint->votes_down );
					$cutSpawnPoint->setSpawnPointVoteUpUrl( \Duelist101\BASE_URL . 'area' . $spawnTable . 'spawns/' . urlencode($spawnPoint->id) . '/vote-up' );
					$cutSpawnPoint->setSpawnPointVoteDownUrl( \Duelist101\BASE_URL . 'area' . $spawnTable . 'spawns/' . urlencode($spawnPoint->id) . '/vote-down' );
					$cutSpawnItem->add($cutSpawnPoint);
				}
				
				$cut->add($cutSpawnItem);
			}
			
			$this->add($cut);
		}

    }
}
