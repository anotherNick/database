jQuery(document).ready( function($) {

    $( '#areas-add-cancel-link' ).click( function() {
        $( '#areas-add-div-form' ).hide();
        $( '#areas-add-div-link' ).show();
        return false;
    } );
    
    $( '#areas-add-form' ).submit( function ( event ) {
        $.ajax( {
            url: $(this).data('url'),
            type: 'post',
            dataType: 'json',
            data: $(this).serialize()
        })
        .done( function(data) {
            var template = $('#areas-add-li-template').clone();
            template.attr('id', null);
            template.find('a.name')
                .attr('href', data.areaUrl)
                .text(data.areaName);
            template.find('a.areas-vote-up').attr('data-url', data.voteUpUrl);
            template.find('a.areas-vote-down').attr('data-url', data.voteDownUrl);
            template.show();
            template.appendTo( $('#areas-ul') );
            var li = $('#areas-ul').children("li");
            li.detach().sort( function(a, b) {
                var compA = $(a).find('a.name').text().toUpperCase();
                var compB = $(b).find('a.name').text().toUpperCase();
                return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
            });
            $('#areas-ul').append(li);
        });
        $( '#areas-add-div-form' ).hide();
        $( '#areas-add-div-link' ).show();
        return false;
    });

    $( '#areas-add-link' ).click( function() {
        $( '#areas-add-select' ).select2('val', null, true);
        $( '#areas-add-div-link' ).hide();
        $( '#areas-add-div-form' ).show();
        return false;
    } );

    $( '#areas-add-link' ).one('click', function() {
        $.ajax({
            url: $(this).data('url'),
            type: 'get',
            dataType: 'json'
        })
        .done( function(data) {
            var json = eval(data);
            $('#areas-add-select').select2({
                width: 'resolve',
                data: json
            });
            $( '#areas-add-select' ).select2('val', null, true);
        });
    } );

    $(document).on('click', '.areas-vote-down', function() {
        var caller = $(this);
        
        $.ajax( {
            url: $(this).data('url'),
            type: 'post',
            dataType: 'json',
        })
        .done( function(data) {
            caller.prev().text(' | '+data.votesDown+' ');
        });
        return false;
    });
    
    $(document).on('click', '.areas-vote-up', function() {
        var caller = $(this);
        
        $.ajax( {
            url: $(this).data('url'),
            type: 'post',
            dataType: 'json',
        })
        .done( function(data) {
            caller.prev().text(' ( '+data.votesUp+' ');
        });
        return false;
    });

} );