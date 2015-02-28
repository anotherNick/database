jQuery(document).ready( function($) {

    $( '#aquarium-select' ).select2({
        width: 'resolve',
        placeholder: "Click or type here to add each aquarium"
    });

    if ( $( '#id' ).val() ) {
        $('#cropper-preview').cropper({
            dashed: false,
            zoomable: true,
            autoCropArea: 1
        });
    }
    
    function readURL(input) {
        if (input.files && input.files[0]) {
            $('#cropper-container').empty();
            if ( /^image/.test(input.files[0].type) ) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#cropper-container').append($('<img>', {
                        id: 'cropper-preview',
                        src: e.target.result
                    }));
                    $('#cropper-preview').cropper({
                        dashed: false,
                        zoomable: true
                    });
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                $('#cropper-container').text('Please select an image.');
            }
        }
    }

    $("#image-selector").change(function(){
        readURL(this);
    });
    
    $( '#zoom-in' ).click( function() {
        if ( $('#cropper-preview').length ) {
            $('#cropper-preview').cropper('zoom', 0.2);
        }
    } );

    $( '#zoom-out' ).click( function() {
        if ( $('#cropper-preview').length ) {
            $('#cropper-preview').cropper('zoom', -0.2);
        }
    } );
    
    // similar to: https://scotch.io/tutorials/submitting-ajax-forms-with-jquery
    
    $( '#add-fish' ).submit( function ( event ) {
        $( '#not-authorized' ).hide();
        $('.form-group').removeClass('has-error');
        $('.help-block').remove(); // remove the error text
        var formData = $(this).serializeObject();
        if ( !$('#cropper-preview').hasClass('default') ) {
            if ( $('#cropper-preview').hasClass('current') ) {
                formData['imageDataUrl'] = 'current';
            } else {
            // TODO: do we want to control size?
                formData['imageDataUrl'] = $('#cropper-preview').cropper('getDataURL', 'image/jpeg');
            }
        }
        $.ajax( {
            url: $( this ).data( 'url' ),
            type: 'post',
            dataType: 'json',
            /*
            used: http://stackoverflow.com/questions/1184624/convert-form-data-to-js-object-with-jquery
            other options:
                https://github.com/macek/jquery-serialize-object
                https://github.com/marioizquierdo/jquery.serializeJSON
            */
            data: formData
        })
        .done( function(data) {
            if ( data.status == 'success' ) {
                window.location.assign(data.redirect);
            } else if ( data.status == 'failures') {
                for (var i = 0; i < data.failures.length; i++) {
                    var id = '#' + data.failures[i].property + '-group';
                    $( id )
                        .addClass('has-error')
                        .prepend($('<div>', {
                            class: 'help-block',
                            text: data.failures[i].message
                        }));
                }
            // assume not authorized
            } else {
                $( '#not-authorized' ).show();
            }
        });
        return false;
    });

    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

} );
