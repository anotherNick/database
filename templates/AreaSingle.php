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
			$cut->setSpawnTable( $spawnTable );
			$cut->setSpawnDisplayName( $spawnDisplayName );

			// Set Spawn Item
			// Workaround for Propel Pluralization until I figure something out.
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
				// Set Spawn Points for this Spawn Type
				$getSpawnPoints = 'getArea' . $spawnTable . 'spawns';
				$spawnPoints = $spawnItem->$getSpawnPoints();
				foreach( $spawnPoints as $spawnPoint ){
					$cutSpawnPoint = $this->get( 'spawnContent.spawnItem.spawnPoint' );
					$cutSpawnPoint->setHtmlSpawnType( str_replace( array( ' ', "'", '"', '-' ), '_', $thisSpawnItem->getName() ) );
					$cutSpawnPoint->setSpawnPointID( $spawnPoint->getId() );
					$cutSpawnPoint->setSpawnPointX( $spawnPoint->getXLoc() );
					$cutSpawnPoint->setSpawnPointY( $spawnPoint->getYLoc() );
					$cutSpawnItem->add($cutSpawnPoint);
				}
				
				$cut->add($cutSpawnItem);
			}

            if ( is_user_logged_in() && current_user_can('edit_posts') ) {
                // if wanted to place user info somewhere
                // global $current_user;
                // get_currentuserinfo();
                //  echo 'Hello ' . $current_user->user_login;
                $cutAddSpawnLink = $this->get( 'spawnContent.addSpawnLink' );
                $cutAddSpawnLink->setSpawnTable( $spawnTable );
                $cutAddSpawnLink->setAreaId( $area->getId() );
                $cutAddSpawnLink->setAreaMapImage( \Duelist101\BASE_URL . 'images/w101_world_maps/' . $area->getImage() );
                $cutAddSpawnLink->setSpawnDisplayName( $spawnDisplayName );
                $cutAddSpawnLink->setSpawnFormSelectUrl( \Duelist101\BASE_URL . strtolower( $spawnDisplayName ) . '.json' );
                $cutAddSpawnLink->setSpawnDisplayName( $spawnDisplayName );
                $cutAddSpawnLink->setSpawnFormAction( \Duelist101\BASE_URL . 'area' . $spawnTable . 'spawns' );
                $cutAddSpawnLink->setSpawnAddLoadingImage( \Duelist101\BASE_URL . 'css/kevin-hop-loading-3.gif');
                $cutAddSpawnLink->setSpawnDisplayTable( ucfirst( $spawnTable ) );
                $cut->add($cutAddSpawnLink);
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
