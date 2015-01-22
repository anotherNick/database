<?php
namespace Duelist101;
require_once '/../public_html/config.php';
require_once PROPEL_CONFIG_TEST;

Class W
{

	public static function addArea( $name=null, \W101\World $world=null, $image='image.jpg'	){
		$area = new \W101\Area;
		$area->setName( $name );
		$area->setImage( $image );
		$area->setWorld( $world );
		$area->save();
		return $area;
	}

	public static function addAreafish( \W101\Area $area=null, \W101\Fish $fish=null )
	{
		$areafish = new \W101\Areafish;
		$areafish->setArea( $area );
		$areafish->setFish( $fish );
		$areafish->setVotesUp( 1 );
		$areafish->setVotesDown( 1 );
		$areafish->save();
		return $areafish;
	}

	public static function addAreafishspawn( \W101\Area $area=null, \W101\Fish $fish=null, \W101\Areafish $areafish=null )
	{
		$afs = new \W101\Areafishspawn;
		$afs->setArea( $area );
		$afs->setFish( $fish );
		$afs->setAreafish( $areafish );
		$afs->setXLoc( 1 );
		$afs->setYLoc( 1 );
		$afs->setVotesUp( 0 );
		$afs->setVotesDown( 0 );
		$afs->save();
		return $afs;
	}

	public static function addAreareagent( \W101\Area $area=null, \W101\Reagent $reagent=null )
	{
		$areareagent = new \W101\Areareagent;
		$areareagent->setArea( $area );
		$areareagent->setReagent( $reagent );
		$areareagent->setVotesUp( 1 );
		$areareagent->setVotesDown( 1 );
		$areareagent->save();
		return $areareagent;
	}

	public static function addAreareagentspawn( \W101\Area $area=null, \W101\Reagent $reagent=null, \W101\Areareagent $areareagent=null )
	{
		$ars = new \W101\Areafishspawn;
		$ars->setArea( $area );
		$ars->setReagent( $reagent );
		$ars->setAreareagent( $areareagent );
		$ars->setXLoc( 1 );
		$ars->setYLoc( 1 );
		$ars->setVotesUp( 0 );
		$ars->setVotesDown( 0 );
		$ars->save();
		return $ars;
	}
	
	public static function addClass( $name=null ){
		$class = new \W101\Classname;
		$class->setName( $name );
		$class->save();
		return $class;
	}
	
	 public static function addFish( \W101\Rarity $rarity=null, \W101\Classname $class=null, $name='' ){
		$fish = new \W101\Fish;
		$fish->setName( $name );
		$fish->setImage( 'Image' . $name . '.gif' );
		$fish->setRarity( $rarity );
		$fish->setClassname( $class );
		$fish->setRank( 1 );
		$fish->setDescription( 'Description ' . $name );
		$fish->setInitialXp( 100 );
		$fish->save();
		return $fish;
	}
	
	public static function addFishhousingitem( \W101\Fish $fish, \W101\Housingitem $housingitem ){
		$fhi = new \W101\Fishhousingitem;
		$fhi->addFish( $fish );
		$fhi->addHousingitem( $housingitem );
		$fhi->save();
		return $fhi;
	}
	
	 public static function addHousingitem( \W101\Housingtype $housingtype, $name=''){
		$housingitem = new \W101\Housingitem;
		$housingitem->setName( 'Name ' . $name );
		$housingitem->setHousingtype( $housingtype );
		$housingitem->setCanTrade( true );
		$housingitem->setCanAuction( true );
		$housingitem->save();
		return $housingitem;
	}
	
	public static function addHousingtype( $name='' ){
		$housingtype = new \W101\Housingtype;
		$housingtype->setName( 'Name ' . $name );
		$housingtype->save();
		return $housingtype;
	}
	
	 public static function addRarity( $name='' ){
		$rarity = new \W101\Rarity;
		$rarity->setName( 'Rarity ' . $name );
		$rarity->save();
		return $rarity;
	}
	
	public static function addReagent( $name=null, $class=null, $desc=null, $rank='3', $image='image.jpg' ){
		$reagent = new \W101\Reagent;
		$reagent->setName( $name );
		$reagent->setClassId( $class->getId() );
		$reagent->setRank( $rank );
		$reagent->setImage( $image );
		$reagent->setDescription( $desc );
		$reagent->save();
		return $reagent;
	}

	public static function addWorld( $name=null, $image='image.jpg' ){
		$world = new \W101\World;
		$world->setName( $name );
		$world->setImage( $image );
		$world->save();
		return $world;
	}
	
}