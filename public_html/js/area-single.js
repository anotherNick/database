function updateSpawnInstructions( step, element ){
	spawnTable = getSpawnTable( element );
    jQuery( '.spawn-add-instructions' ).hide();
    jQuery( '.instruction-step-title' ).hide();
    
    switch( step ){
        case 1:
            jQuery( '#'+spawnTable+'-circle-container .spawn-circle-wrapper' ).remove();
            jQuery( '#'+spawnTable+'-spawn-item-select' ).select2("val", null, true);
            jQuery( '.spawn-select-circle' ).hide();
            jQuery( '#'+spawnTable+'-spawn-add-form' ).show();
            jQuery( '#'+spawnTable+'-spawn-modal-wrapper' ).dialog({ 
                modal: true, 
                width: 385,
                title: 'Add A '+ucfirst( spawnTable )+' Spawn Point!'
            });
            jQuery( '#'+spawnTable+'-instruction-step-title-1' ).show( 'bounce' );
            jQuery( '#'+spawnTable+'-add-step-1' ).show();
            break;
        case 2:
            unDarkenSpawnSelectCircle( spawnTable );
            jQuery( '#'+spawnTable+'-instruction-step-title-2' ).show( 'bounce' );
            jQuery( '#'+spawnTable+'-add-step-2' ).show();
            areaSpawnStartMouse( spawnTable, jQuery( '#'+spawnTable+'-spawn-mouse-capture' ) );
            break;
        case 3:
            darkenSpawnSelectCircle( spawnTable );
            jQuery( '#'+spawnTable+'-instruction-step-title-3' ).show( 'bounce' );
            jQuery( '#'+spawnTable+'-add-step-3' ).show();
            areaSpawnStopMouse( spawnTable, jQuery( '#'+spawnTable+'-spawn-mouse-capture' ) );
            break;
        case 3.5:
            jQuery( '#'+spawnTable+'-add-step-35' ).show();
            break;
        case 4:
            jQuery( '#'+spawnTable+'-instruction-step-title-4' ).show( 'bounce' );
            jQuery( '#'+spawnTable+'-add-step-4' ).show();
            break;
        case 'ajaxError':
            jQuery( '#'+spawnTable+'-instruction-step-title-error' ).show( 'bounce' );
            jQuery( '#'+spawnTable+'-add-step-error' ).show();
            break;
        default:
            jQuery( '#'+spawnTable+'-spawn-modal-wrapper' ).dialog( 'close' );
            jQuery( '#'+spawnTable+'-spawn-add-form' ).hide();
            jQuery( '.spawn-select-circle' ).hide();
            areaSpawnStopMouse( spawnTable, jQuery( '#'+spawnTable+'-spawn-mouse-capture' ) );
    }
}

jQuery(document).ready( function($) {

	// STEP 1: 
	// User Action - Add Link Clicked
	// Goal - Initialize Form and get Spawn Item ID
	
    $( '.spawn-add-step-1' ).click( function() {
		updateSpawnInstructions( 1, this );
        return false;
    } );
	
    $( '.spawn-add-link' ).one('click', function() {
		spawnTable = getSpawnTable( this );
		spawnFormSelectUrl = $( '#'+spawnTable+'-form-select-url' ).attr( 'href' );
		
		selectTwoAjax( spawnTable, spawnFormSelectUrl );
    } );
	
	// STEP 2: 
	// User Action - Spawn Item Selected.
	// Goal - Allow user to pick Spawn Location.
	
	$( '.spawn-add-step-2' ).on("change", function( select ) {
		if( this.value != '' ){
			setAreaSpawnTypeId( spawnTable, select.added.id );
			setSpawnSelectCircleTitle( spawnTable, select.added.text );
			cloneCircles( spawnTable, select.added.text );
			updateSpawnInstructions( 2, this );
		}
	} );
	
	$( 'a.spawn-add-step-2' ).click( function( select ) {
			updateSpawnInstructions( 2, this );
		return false;
	} );
	
	$( '.spawn-mouse-capture' ).mousemove(function( e ) {
		spawnTable = getSpawnTable( this );
		
		if( $( this ).data( 'captureMouse' ) == true ){
			// Place the circle
			var parentOffset = $( this ).offset();
			var relX = e.pageX - parentOffset.left - 12;
			var relY = e.pageY - parentOffset.top - 12;
			$( '#'+spawnTable+'-spawn-select-circle' ).css({
			   left:  relX,
			   top:   relY
			} );
			// Convert coordinates to % and update Form.
			mapWidth = Math.round( 100 * (relX / $( '#'+spawnTable+'-area-map' ).width()) );
			mapHeight = Math.round( 100 * (relY / $( '#'+spawnTable+'-area-map' ).height()) );
			$( '#'+spawnTable+'-x' ).val( mapWidth );
			$( '#'+spawnTable+'-y' ).val( mapHeight );
		}
	} );
	
	// STEP 3: 
	// User Action - Spawn Point Selected
	// Goal - Show Create option, or allow retry.
	
	$( '.spawn-add-step-3' ).click( function() {
        // Ignore click if nothing selected
        if ( jQuery( '.select2-chosen' ).text().length > 0 ) {
            updateSpawnInstructions( 3, this );
        }
	} );
	
	// STEP 4: 
	// User Action - Form Submitted.
	// Goal - Submit form data and display success to User.
	
    $( '.spawn-add-form' ).submit( function ( event ) {
		spawnTable = getSpawnTable( this );
		spawnFormSubmitUrl = $( '#'+spawnTable+'-form-action' ).attr( 'href' );
		updateSpawnInstructions( 3.5, this );
        var template;
        
        $.ajax( {
            url: $( '#'+spawnTable+'-form-action' ).attr( 'href' ),
            type: 'post',
            dataType: 'json',
            data: $( this ).serialize(),
			error: function( e ){ spawnAjaxError( spawnTable, e ); },
			success: function(){ updateSpawnInstructions( 4, $( '#'+spawnTable+'-spawn-add-form' ) ); }
        } )
        .done( function( data ) {
			spawnName = replaceClassChars( data[ spawnTable+'Name' ] );
			spawnPointID = spawnName+'-'+data.id+'-circle';
			if( $( '.'+spawnName ).length ){
			// There are already SpawnPoints for this SpawnItem
				// Append to spawn-add map.
				spawnPointClasses = $( '.'+spawnName ).filter(":first").attr( 'class' );
				spawnAddDupe = $( '#'+spawnTable+'-spawn-select-circle' )
					.clone()
					.attr( 'id', spawnPointID )
					.removeClass()
					.addClass( spawnPointClasses )
					.css( { 'left': data.areaSpawnX+'%', 'top': data.areaSpawnY+'%' } )
					.appendTo( '#'+spawnTable+'-circle-container' );
				$( spawnAddDupe ).children( '.spawn-select-circle-title' )
					.attr( 'id', '' )
					.removeClass()
					.addClass( 'spawn-circle-title' );
				$( '#'+spawnTable+'-spawn-select-circle' ).hide();
				
				// Append to Area Map
				$( spawnAddDupe )
					.clone()
					.attr( 'id', spawnPointID )
					.hide()
					.appendTo( '#area-circle-container' );
					
				// Append to Spawn Point List
				spawnPointListID = spawnName+'-'+data.id;
				$( '#spawn-point-list-template' )
					.clone()
					.attr( 'id', spawnPointListID )
					.appendTo( '#'+spawnName+'-spawns .spawn-points' )
					.on({
						mouseenter: function () {
							circleID = $( this ).attr( 'id' ) + '-circle';
							$( '#'+circleID ).addClass( 'spawn-circle-highlight' );
						},
						mouseleave: function () {
							circleID = $( this ).attr( 'id' ) + '-circle';
							$( '#'+circleID ).removeClass( 'spawn-circle-highlight' );
						}
					} )
					.show();
					
			}else if( $( '#'+spawnName+'-spawns' ).length ){
			// There is a Spawn Item, but no Spawn Points
				spawnPointClasses = 'spawn-circle-wrapper '+spawnName;
				spawnAddDupe = $( '#'+spawnTable+'-spawn-select-circle' )
					.clone()
					.attr( 'id', spawnPointID )
					.removeClass()
					.addClass( spawnPointClasses )
					.css( { 'left': data.areaSpawnX+'%', 'top': data.areaSpawnY+'%' } )
					.appendTo( '#'+spawnTable+'-circle-container' );
				setSpawnColor( spawnAddDupe );
				$( spawnAddDupe ).children( '.spawn-select-circle-title' )
					.attr( 'id', '' )
					.removeClass()
					.addClass( 'spawn-circle-title' );
				$( '#'+spawnTable+'-spawn-select-circle' ).hide();
				
				// Append to Area Map
				$( spawnAddDupe )
					.clone()
					.attr( 'id', spawnPointID )
					.hide()
					.appendTo( '#area-circle-container' );
					
				// Append to Spawn Point List
				spawnPointListID = spawnName+'-'+data.id;
				$( '#spawn-point-list-template' )
					.clone()
					.attr( 'id', spawnPointListID )
					.appendTo( '#'+spawnName+'-spawns .spawn-points' )
					.on({
						mouseenter: function () {
							circleID = $( this ).attr( 'id' ) + '-circle';
							$( '#'+circleID ).addClass( 'spawn-circle-highlight' );
						},
						mouseleave: function () {
							circleID = $( this ).attr( 'id' ) + '-circle';
							$( '#'+circleID ).removeClass( 'spawn-circle-highlight' );
						}
					} )
					.show();
				$( '#'+spawnName+'-spawns .spawn-visibility' ).addClass( 'spawn-viewable' );
				
			}else{
			// There is no Spawn Item or Spawn Points
				spawnPointClasses = 'spawn-circle-wrapper '+spawnName;
				spawnAddDupe = $( '#'+spawnTable+'-spawn-select-circle' )
					.clone()
					.attr( 'id', spawnPointID )
					.removeClass()
					.addClass( spawnPointClasses )
					.css( { 'left': data.areaSpawnX+'%', 'top': data.areaSpawnY+'%' } )
					.appendTo( '#'+spawnTable+'-circle-container' );
				setSpawnColor( spawnAddDupe );
				$( spawnAddDupe ).children( '.spawn-select-circle-title' )
					.attr( 'id', '' )
					.removeClass()
					.addClass( 'spawn-circle-title' );
				$( '#'+spawnTable+'-spawn-select-circle' ).hide();
				
				// Append to Area Map
				$( spawnAddDupe )
					.clone()
					.attr( 'id', spawnPointID )
					.hide()
					.appendTo( '#area-circle-container' );
					
				// Create Spawn Item and Spawn Point List
				spawnItemID = spawnName+'-spawns';
				spawnItemDupe = $( '#spawn-item-template' )
					.clone()
					.attr( 'id', spawnItemID )
					.appendTo( '#'+spawnTable+'-spawns' )
					.show();
				$( '#'+spawnItemID+' .spawn-link-title' ).text( data[ spawnTable+'Name' ] );
				
				// Append to Spawn Point List
				spawnPointListID = spawnName+'-'+data.id;
				$( '#spawn-point-list-template' )
					.clone()
					.attr( 'id', spawnPointListID )
					.appendTo( '#'+spawnName+'-spawns .spawn-points' )
					.on({
						mouseenter: function () {
							circleID = $( this ).attr( 'id' ) + '-circle';
							$( '#'+circleID ).addClass( 'spawn-circle-highlight' );
						},
						mouseleave: function () {
							circleID = $( this ).attr( 'id' ) + '-circle';
							$( '#'+circleID ).removeClass( 'spawn-circle-highlight' );
						}
					} )
					.show();
				$( '#'+spawnName+'-spawns .spawn-visibility' ).addClass( 'spawn-viewable' );
			}
        } );
        return false;
    } );

	// STEP 0: 
	// User Action - Clicked Cancel.
	// Goal - Shut down Spawn Form and Modal.
	
    $( '.spawn-add-cancel-link' ).click( function() {
		updateSpawnInstructions( 0, this );
        return false;
    } );
	
    $( '.spawn-add-form' ).on( 'click', '.spawn-add-cancel-button', function( e ) {
		updateSpawnInstructions( 0, $( e.target ) );
        return false;
    } );
	
	// BEGIN SPAWN DISPLAY INTERFACE
	$( document ).on('click', '.spawn-parent.spawn-viewable', function() {
		spawnParent = $( this ).parents( '.spawn-links' );
		spawnType = getSpawnTable( $( spawnParent ) );
		$( '#' + $( spawnParent ).attr( 'id' ) + ' .spawn-points' ).show( 'clip' );
		$( '.' + spawnType ).show( 'clip' );
		
		 $( this ).addClass( 'spawn-viewing' );
		 $( spawnParent ).find( '.spawn-child' ).each( function(){
			$( this ).addClass( 'spawn-viewing' );
		} );
	} );
	
	$( document ).on('click', '.spawn-parent.spawn-viewing', function() {
		spawnParent = $( this ).parents( '.spawn-links' );
		spawnType = getSpawnTable( $( spawnParent ) );
		$( '#' + $( spawnParent ).attr( 'id' ) + ' .spawn-points' ).hide( 'clip' );
		$( '.' + spawnType ).hide( 'explode' );
		
		 $( this ).removeClass( 'spawn-viewing' );
		 $( spawnParent ).find( '.spawn-child' ).each( function(){
			$( this ).removeClass( 'spawn-viewing' );
		} );
	} );
	
	$( document ).on('click', '.spawn-child', function() {
		spawnParent = $( this ).parent( 'li' );
		spawnCircle = '#' + $( spawnParent ).attr( 'id' ) + '-circle';
		if( $( this ).hasClass( 'spawn-viewing' ) ){
			$( spawnCircle ).hide( 'explode' );
			$( this ).removeClass( 'spawn-viewing' );
		}else{
			$( spawnCircle ).show( 'clip' );
			$( this ).addClass( 'spawn-viewing' );
		}
	} );
	
	$( '.spawn-point' ).on({
		mouseenter: function () {
			circleID = $( this ).attr( 'id' ) + '-circle';
			$( '#'+circleID ).addClass( 'spawn-circle-highlight' );
		},
		mouseleave: function () {
			circleID = $( this ).attr( 'id' ) + '-circle';
			$( '#'+circleID ).removeClass( 'spawn-circle-highlight' );
		}
	});
	
	// Initialize Spawn Points
	$( '.spawn-point' ).each( function(){
		buildSpawnCircles( this, '#area-circle-container' );
	} );
	
	$( '.spawn-parent.spawn-viewable' ).each( function(){
		spawnLink = $( this ).parents( '.spawn-links' );
		setSpawnColor( spawnLink );
	} );
	
	// Create Tooltips to help users.
	$( '.spawn-viewable' ).tooltip();
    
} );

function selectTwoAjax( spawnTable, spawnFormSelectUrl ){
	jQuery( '#'+spawnTable+'-loading-image' ).show();
	jQuery.ajax({
		url: spawnFormSelectUrl,
		type: 'get',
		dataType: "json",
		success: function(){ jQuery( '#'+spawnTable+'-loading-image' ).hide(); }
	})
	.done( function(data) {
		json = eval(data);
		jQuery( '#'+spawnTable+'-spawn-item-select' ).select2({
			width: 'resolve',
			data: json
		} );
		jQuery( '#'+spawnTable+'-spawn-item-select' ).select2('val', null, true);
	} );
}

function setAreaSpawnTypeId( spawnTable, id ){
	jQuery( '#'+spawnTable+'-area-spawn-type-id' ).val( id );
}

function setSpawnSelectCircleTitle( spawnTable, name ){
	jQuery( '#'+spawnTable+'-spawn-select-circle-title' ).text( name );
}

function replaceClassChars( className ){
	className = className.split(' ').join('_');
	className = className.split('"').join('_');
	className = className.split("'").join('_');
	className = className.split('-').join('_');
	return className;
}

function cloneCircles( spawnTable, spawnName ){
	spawnName = replaceClassChars( spawnName );
	jQuery( '.'+spawnName ).clone().show().appendTo( '#'+spawnTable+'-circle-container' );
}

function areaSpawnStartMouse( spawnTable, element ){
		jQuery( '#'+spawnTable+'-spawn-select-circle' ).show();
		element.data( 'captureMouse', true);
}

function areaSpawnStopMouse( spawnTable, element ){
		element.data( 'captureMouse', false);
		// Position spawnSelectCircle with percentages so the
		// User sees actual placement after calculation/rounding.
		jQuery( '#'+spawnTable+'-spawn-select-circle' ).css({
		   left:  jQuery( '#'+spawnTable+'-x' ).val()+'%',
		   top:   jQuery( '#'+spawnTable+'-y' ).val()+'%'
		} );
}

function darkenSpawnSelectCircle( spawnTable ){
	jQuery( '#'+spawnTable+'-spawn-select-circle' ).addClass( 'spawn-select-darken-text' );
	jQuery( '#'+spawnTable+'-spawn-select-circle .spawn-circle' ).addClass( 'spawn-select-darken-circle' );
}

function unDarkenSpawnSelectCircle( spawnTable ){
	jQuery( '#'+spawnTable+'-spawn-select-circle' ).removeClass( 'spawn-select-darken-text' );
	jQuery( '#'+spawnTable+'-spawn-select-circle .spawn-circle' ).removeClass( 'spawn-select-darken-circle' );
}

function spawnAjaxError( spawnTable, e ){
	jQuery( '#'+spawnTable+'-ajax-error' ).text( e.responseText );
	updateSpawnInstructions( 'ajaxError', jQuery( '#'+spawnTable+'-add-step-error' ) );
}

function ucfirst(str) {
	// From phpjs.org
	str += '';
	var f = str.charAt(0)
	.toUpperCase();
	return f + str.substr(1);
}

function getSpawnTable( element ) {
	// Expects spawnTable to be first "word" in the ID.
	var id = jQuery( element ).attr( 'id' );
	var spawnTable = id.split( '-' );
	return spawnTable[0];
}

function buildSpawnCircles( element, container ){
	thisID = jQuery( element ).attr( 'id' );
	spawnType = getSpawnTable( jQuery( element ) );
	spawnVisibility = jQuery( element ).find( '.spawn-visibility' );
	parentVisibility = jQuery( '#'+spawnType+'-spawns' ).find( '.spawn-visibility' );
	circleID = thisID + '-circle';
	circleX = jQuery( element ).children( '.x-loc' ).text() + '%';
	circleY = jQuery( element ).children( '.y-loc' ).text() + '%';
	circleText = jQuery( element ).closest( '.spawn-links' );
	circleText = jQuery( circleText ).find( '.spawn-link-title' ).text();

	jQuery( '#spawn-circle-template' )
		.clone()
		.attr( 'id', circleID )
		.addClass( spawnType )
		.css( { 'left': circleX, 'top': circleY } )
		.appendTo( container );
	jQuery( '#' + circleID ).children( '.spawn-circle-title' ).text( circleText );
	
	jQuery( spawnVisibility ).addClass( 'spawn-viewable' );
	jQuery( parentVisibility ).addClass( 'spawn-viewable' );
}

var spawnColor = 1;
function setSpawnColor( element ){
	spawnColorClass = '.' + getSpawnTable( jQuery( element ) );
	if( spawnColor > 10 ){ spawnColor = 1; }
	jQuery( spawnColorClass ).addClass( 'spawn-color-' + spawnColor );
	spawnColor++;
}

