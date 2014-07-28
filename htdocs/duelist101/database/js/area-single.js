function ucfirst(str) {
// From phpjs.org
  str += '';
  var f = str.charAt(0)
    .toUpperCase();
  return f + str.substr(1);
}


jQuery(document).ready( function($) {

    $( '.spawn-add-link' ).one('click', function() {
        $.ajax({
            url: "/duelist101/database/reagents.json",
            type: 'get',
            dataType: "json"
        })
        .done( function(data) {
            json = eval(data);
            $("#spawn-item-select").select2({
                width: 'resolve',
                data: json
            } );
            $( '#spawn-item-select' ).select2('val', null, true);
        } );
    } );

	// Initialize Area Spawn Form.
    $( '.spawn-add-link' ).click( function() {
		// Spawn Type is set by the ID of its Spawn Add Link.
		spawnItemType = $( this ).attr( 'id' ).replace( 'spawn-add-', '' );
		$( '#spawn-item-type' ).val( spawnItemType );
		$( '#area-spawn-item-instructions' ).text( 'Select A '
			+ ucfirst( spawnItemType ) +
			'!' 
		);
        $( '#spawn-item-select' ).select2('val', 'null', true );
		$( '#area-spawn-add-form' ).show();
		$( this ).hide();
        return false;
    } );

	// Update when an Item is selected, and fire Spawn Point Picker.
	$( '#spawn-item-select' )
	.on("change", function( e ) {
		if( this.value != '' ){
			$( '#spawn-select-circle-title' ).text( e.added.text );
			$( '#area-spawn-item-instructions' ).text( 'Click the map to add a Spawn for:' );
			$( '#area-spawn-create-button' ).show();
			$( '#area-spawn-create-instructions' ).text( 'Just save the '
				+ ucfirst( $( '#spawn-item-type' ).val() )
			);
			$( "#spawn-mouse-capture" ).trigger( 'areaSpawnStartMouse' );
		}
	} );
	
	// Update when a Spawn Point is selected.
	$( '#spawn-select-circle' ).click( function( e ) {
			$( '#spawn-mouse-capture' ).trigger( 'areaSpawnSelected' );
		} );
		
	$( '#spawn-mouse-capture' ).bind( 'areaSpawnSelected', function() {
		$( "#spawn-mouse-capture" ).trigger( 'areaSpawnStopMouse' );
		$( '#area-spawn-item-instructions' ).text( 'Select a different '
			+ ucfirst( $( '#spawn-item-type' ).val() )
		);
		$( '#area-spawn-create-instructions' ).text( 'Save this Spawn Point' );
	} );

	// Shut down Area Spawn Form.
    $( '#reagent-add-cancel-link' ).click( function() {
        $( '#area-spawn-add-form' ).hide();
		$( '#area-spawn-create-button' ).hide();
		$( '#spawn-select-circle' ).hide();
        $( '.spawn-add-link' ).show();
		$( '#spawn-mouse-capture' ).trigger( 'areaSpawnStopMouse' );
        return false;
    } );
	
	$( '#spawn-mouse-capture' ).mousemove(function( e ) {
		if( this.captureMouse == true ){
			$( this ).css( 'width', function(){
				mapWidth = $( '#area-map' ).width();
				mapWidth = mapWidth + 4;
				return mapWidth;
			} );
			var parentOffset = $(this).offset();
			var relX = e.pageX - parentOffset.left - 12;
			var relY = e.pageY - parentOffset.top - 12;
			$('#spawn-select-circle').css({
			   left:  relX,
			   top:   relY
			} );
		}
	} );
	
	// Functions to turn Mouse Capture on and off.
	// Important because setting captureMouse attribute requires 'this'
	// Fire using .trigger()
	$( '#spawn-mouse-capture' ).bind( 'areaSpawnStartMouse', function() {
		$( '#spawn-select-circle' ).show();
		this.captureMouse = true;
	} );
	
	$( '#spawn-mouse-capture' ).bind( 'areaSpawnStopMouse', function() {
		this.captureMouse = false;
	} );
    
	// Form Submit
    $( '#areas-add-form' ).submit( function ( event ) {
        var template;
        
        $.ajax( {
            url: '/duelist101/database/areaspawns',
            type: 'post',
            dataType: 'json',
            data: $(this).serialize()
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

} );