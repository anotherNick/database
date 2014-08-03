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
			$cut = $this->getSpawnTabs();
			$cut->setSpawnDisplayName( $spawnDisplayName );
			$this->add($cut);
		
			$cut = $this->getSpawnContent();
			$cut->setAreaMapImage( $area->image );
			$cut->setAreaId( $area->id );
			$cut->setSpawnTable( $spawnTable );
			$cut->setSpawnDisplayName( $spawnDisplayName );
			$cut->setSpawnDisplayTable( ucfirst( $spawnTable ) );
			$cut->setSpawnFormSelectUrl( '/duelist101/database/reagents.json' );
			$cut->setSpawnFormAction( );
			$cut->setSpawnAddLoadingImage( \Duelist101\BASE_URL . 'css/kevin-hop-loading-3.gif');
			$this->add($cut);
		}

    }
}
