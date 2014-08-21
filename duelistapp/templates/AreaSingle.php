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
        $cut->setName($area->name);
        $cut->setImage($area->image);
        $this->add($cut);
		
		$spawns = $this->getSpawnTypes();
		foreach( $spawns as $spawnTable => $spawnDisplayName ){
			// Create Spawn Tabs
			$cut = $this->getSpawnTabs();
			$cut->setSpawnDisplayName( $spawnDisplayName );
			$this->add($cut);
			
			// Create Spawn Add Form
			$cut = $this->getSpawnContent();
			$cut->setAreaMapImage( $area->image );
			$cut->setAreaId( $area->id );
			$cut->setSpawnTable( $spawnTable );
			$cut->setSpawnDisplayName( $spawnDisplayName );
			$cut->setSpawnDisplayTable( ucfirst( $spawnTable ) );
			$cut->setSpawnFormSelectUrl( \Duelist101\BASE_URL . strtolower( $spawnDisplayName ) . '.json' );
			$cut->setSpawnFormAction( \Duelist101\BASE_URL . 'area' . $spawnTable . 'spawns' );
			$cut->setSpawnAddLoadingImage( \Duelist101\BASE_URL . 'css/kevin-hop-loading-3.gif');

			// Set Spawn Type
			$spawnTypes = 'ownArea' . $spawnTable . 'List';
			$spawnTypes = $area->$spawnTypes;
			foreach( $spawnTypes as $spawnType ){
				$cutSpawnTypes = $this->get( 'spawnContent.spawnTypes' );
				$cutSpawnTypes->setSpawnType( $spawnType->$spawnTable->name );
				$cutSpawnTypes->setHtmlSpawnType( str_replace( [ ' ', "'", '"' ], '_', $spawnType->$spawnTable->name ) );
				$cutSpawnTypes->setSpawnTypeURL(  );
				$cutSpawnTypes->setVotesUp( $spawnType->votes_up );
				$cutSpawnTypes->setVotesDown( $spawnType->votes_down );
				// Set Spawn Points for this Spawn Type
				$spawnPoints = 'ownArea' . $spawnTable . 'spawnList';
				$spawnPoints = $spawnType->$spawnPoints;
				foreach( $spawnPoints as $spawnPoint ){
					$cutSpawnPoints = $this->get( 'spawnContent.spawnTypes.spawnPoints' );
					$cutSpawnPoints->setHtmlSpawnType( str_replace( [ ' ', "'", '"' ], '_', $spawnType->$spawnTable->name ) );
					$cutSpawnPoints->setSpawnPointID( $spawnPoint->id );
					$cutSpawnPoints->setSpawnPointX( $spawnPoint->x_loc );
					$cutSpawnPoints->setSpawnPointY( $spawnPoint->y_loc );
					$cutSpawnPoints->setVotesUp( $spawnPoint->votes_up );
					$cutSpawnPoints->setVotesDown( $spawnPoint->votes_down );
					$cutSpawnTypes->add($cutSpawnPoints);
				}
				
				$cut->add($cutSpawnTypes);
			}
			
			$this->add($cut);
		}

    }
}
