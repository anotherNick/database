jQuery(document).ready( function($) {

    $( '#areas-add-select' ).select2({
        placeholder: "Type or select an area"
    });

    $( '#areas-add-link' ).click( function() {
        $( '#areas-add-select' ).select2('val', null);
        $( '#areas-add-div-link' ).hide();
        $( '#areas-add-div-form' ).show();
        return false;
    } );

    $( '#areas-add-cancel-link' ).click( function() {
        $( '#areas-add-div-form' ).hide();
        $( '#areas-add-div-link' ).show();
        return false;
    } );
    
    $( '#areas-add-form' ).submit( function ( event ) {
        var template;
        
        $.ajax( {
            url: '/duelist101/database/areareagent',
            type: 'post',
            dataType: 'json',
            data: $(this).serialize()
        } )
        .done( function(data) {
            template = $('.areas-add-li-template').clone();
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
            });
            $('#areas-ul').append(li);
        } );
        $( '#areas-add-div-form' ).hide();
        $( '#areas-add-div-link' ).show();
        return false;
    } );

} );