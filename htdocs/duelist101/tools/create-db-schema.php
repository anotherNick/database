<?php

require 'tools-config.php';
require APP_DIR . 'vendor/redbean/rb.php';

R::setup('sqlite:' . APP_DIR . DB_FILE );

$reagent = R::load( 'reagent', 1 );
if ( $reagent == NULL ) {
	$reagent = R::dispense( 'reagent' );
	$reagent->name = 'Acorn';
	$reagent->rank = 3;
	$reagent->image = 'Acorn.gif';
	$reagent->description = 'A small Acorn';
	$reagent->class = 5;
	$reagent->can_auction = true;
	$reagent->is_crowns_only = false;
	$reagent->is_retired = false;
	$id = R::store( $reagent );
}

$area = R::load ( 'area', 1);
if ( $area == NULL ) {
	$area = R::dispense('area');
	$area->name = 'Unicorn Way';
	$area->image = 'wc_unicorn_way.jpg';
	$id = R::store ( $area );
}

/*
$class = R::dispense( 'class' );
$class->name = 'Fire';
$id = R::store( $class );
$class = R::dispense( 'class' );
$class->name = 'Ice';
$id = R::store( $class );
$class = R::dispense( 'class' );
$class->name = 'Storm';
$id = R::store( $class );
$class = R::dispense( 'class' );
$class->name = 'Balance';
$id = R::store( $class );
$class = R::dispense( 'class' );
$class->name = 'Life';
$id = R::store( $class );
$class = R::dispense( 'class' );
$class->name = 'Death';
$id = R::store( $class );
$class = R::dispense( 'class' );
$class->name = 'Myth';
$id = R::store( $class );


$reagent = R::load( 'reagent', 1 );

$area = R::load( 'area', 1 );
$area->sharedReagentList[] = $reagent;
$id = R::store ( $area );
*/

echo "DB stuff done";
R::close();


