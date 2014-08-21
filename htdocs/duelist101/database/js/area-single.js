function updateSpawnInstructions( step, element ){
	spawnTable = getSpawnTable( element );
	jQuery( '.spawn-add-instructions' ).hide();
	jQuery( '.instruction-step-title' ).hide();
	
	switch( step ){
		case 1:
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
			unDarkenSpawnSelectCircle( spawnTable )
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
		updateSpawnInstructions( 3, this );
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
            url: '/duelist101/database/areareagentspawns',
            type: 'post',
            dataType: 'json',
            data: $( this ).serialize(),
			error: function( e ){ spawnAjaxError( spawnTable, e ); },
			success: function(){ updateSpawnInstructions( 4, $( '#'+spawnTable+'-spawn-add-form' ) ); }
        } )
        .done( function(data) {
            template = $('.areas-add-li-template').clone();
            template.attr('class', null);
            template.find('a.name')
                .attr('href', data.url)
                .text(data.areaName);
            template.find('a.vote-up').attr('href', data.voteUpUrl);
            template.find('a.vote-down').attr('href', data.voteDownUrl);
            template.show();
            template.appendTo( $('#areas-ul') );
            var li = $('#areas-ul').children("li");
            li.detach().sort( function(a, b) {
                var compA = $(a).text().toUpperCase();
                var compB = $(b).text().toUpperCase();
                return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
            } );
            $('#areas-ul').append(li);
        } );
        $( '#areas-add-div-form' ).hide();
        $( '#areas-add-div-link' ).show();
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
	$( '.spawn-links' ).hover( function() {
		spawnType = getSpawnTable( $( this ) );
		$( '#' + $( this ).attr( 'id' ) + ' .spawn-points' ).show( 'slide' );
		$( '.' + spawnType ).show();
	} );
	
	$( '.spawn-links' ).mouseleave( function() {
		spawnType = getSpawnTable( $( this ) );
		$( '#' + $( this ).attr( 'id' ) + ' .spawn-points' ).hide( 'slide' );
		$( '.' + spawnType ).hide();
	} );
	
	// Initialize Spawn Points
	$( '.spawn-point' ).each( function(){
		thisID = $( this ).attr( 'id' );
		spawnType = getSpawnTable( $( this ) );
		circleID = thisID + '-circle';
		circleX = $( this ).children( '.x-loc' ).text() + '%';
		circleY = $( this ).children( '.y-loc' ).text() + '%';

		$( '#spawn-circle-template' )
			.clone()
			.attr( 'id', circleID )
			.addClass( spawnType )
			.css( { 'left': circleX, 'top': circleY } )
			.appendTo( '#area-circle-container' );
		$( '#' + circleID ).children( '.spawn-circle-title' ).text( spawnType );
	} );
    
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

