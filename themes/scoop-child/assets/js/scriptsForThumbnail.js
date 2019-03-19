jQuery(document).ready(function($) {
    $('.favorite').on('click', '#add_to_sidur', function(event) {
       var a=event.target.offsetParent.offsetParent.offsetParent.offsetParent.id;
       var b= a.slice(5);
        var data=  {
            action: 'change_sidur',
            user: ajaxdata.user_id,
            post:b,
            security: ajaxdata.ajax_nonce
        };
        jQuery.post(ajaxdata.ajaxurl, data, function(response) {
            $('#add_to_sidur').html("<i class='fa fa-heart' aria-hidden='true'></i>").attr("id", 'remove_from_sidur');
            if(response === "0"){
                location.reload();
            }
            else {
                alert(response);
            }
    
        });
        return false;
    });
    
    $('.favorite').on('click', '#remove_from_sidur', function(event) {
        var a=event.target.offsetParent.offsetParent.offsetParent.offsetParent.id;
       var b= a.slice(5);
        var data=  {
            action: 'remove_sidur',
            user: ajaxdata.user_id,
            post:b,
            fromFolder:location.search.split('folder=')[1],
            security: ajaxdata.ajax_nonce
        };
        jQuery.post(ajaxdata.ajaxurl, data, function(response) {
            $('#remove_from_sidur').html("<i class='fa fa-heart' aria-hidden='true'></i>").attr("id", 'add_to_sidur');
            if(response === "0"){
                location.reload();
            }
            else {
                // $('.loader').hide();
                alert(response);
            }
        });
        return false;
    })
    });