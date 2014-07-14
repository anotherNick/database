jQuery(document).ready( function($) {

    $( '#areas-select' ).select2({
        placeholder: "Type or select an area"
    });

    $( '#add-area-link' ).click( function() {
        $("#areas-select").val($("#areas-select option:first").val());
        $( '#add-area-div-link' ).hide();
        $( '#add-area-div-form' ).show();
        return false;
    } );

/*
    $( '#areas-form' ).submit( function ( event ) {
        $.ajax( {
            url: '/duelist101/database/areareagent',
            type: 'post',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(data) {
                // do something with the data
                var ul = $("#areas-ul");
                ul.append(li);
                $('ul').append($('<li/>', {    //here appending `<li>`
    'data-role': "list-divider"
}).append($('<a/>', {    //here appending `<a>` into `<li>`
    'href': 'test.html',
    'data-transition': 'slide',
    'text': 'hello'
})));
var grab = $('.grab-me')
    .clone()
    .removeClass('grab-me')
    .appendTo('#register');
              /*
                ul.append('
                    <li style="text-align:center;">
                        <a href="' + $baseUrl + '/areas/' + urlencode( $areareagent->area->name ) + '">
                            ' + $cutSource->setName( $areareagent->area->name) + '
                        </a>
                        ( 0 <a href="/">â–³</a> |
                        0 <a href="/">â–½</a> )
                    </li>');
                var li = ul.children("li");
                li.detach().sort();
                $("ul").listview("refresh");
                $( '#add-area-div-form' ).hide();
                $( '#add-area-div-link' ).show();
             }
         } );
    } );
        event.preventDefault();
});
    } );
*/

    $( '#redlink' ).click( function() {
        $( '#add-area-div-form' ).hide();
        $( '#add-area-div-link' ).show();
        return false;
    } );
    
} );