<?php

require 'tools-config.php';
require APP_DIR . 'vendor/redbean/rb.php';

R::setup('sqlite:' . DB_FILE );


$area = R::load ( 'area', 1);
if ( $area == NULL ) {
	$area = R::dispense('area');
	$area->name = 'Unicorn Way';
	$area->image = 'wc_unicorn_way.jpg';
	$id = R::store ( $area );
}

$class = R::load ( 'class', 1);
if ( $class == NULL ) {
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
}

$class = R::load ( 'class', 1);
$reagent = R::load( 'reagent', 1 );
if ( $reagent == NULL ) {
	$reagent = R::dispense( 'reagent' );
	$reagent->name = 'Acorn';
	$reagent->rank = 3;
	$reagent->image = 'Acorn.gif';
	$reagent->description = 'A small Acorn';
	$reagent->class = $class;
	$reagent->can_auction = true;
	$reagent->is_crowns_only = false;
	$reagent->is_retired = false;
	$id = R::store( $reagent );
}

$reagent_spawn = R::load ( 'reagentspawn', 1);
if ( $reagent_spawn == NULL ) {
	$reagent = R::load( 'reagent', 1 );
	$area = R::load( 'area', 1);
	$reagent_spawn = R::dispense( 'reagentspawn' );
	$reagent_spawn->x_loc = '50';
	$reagent_spawn->y_loc = '50';
	// Note: Because I've created these relationships manually,
	// we may have to maintain them manually.
	$reagent_spawn->reagent_id = $reagent->id;
	$reagent_spawn->area_id = $area->id;
	$id = R::store ( $reagent_spawn );

}

echo "DB stuff done";
R::close();


