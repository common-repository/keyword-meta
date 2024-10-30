var jQ=jQuery.noConflict();

jQ(document).ready(function() {
	jQ('#ckm-tabs').tabs();
    
    showQuality('keywords_edit',300,1000);
    showQuality('description_edit',150,300);

    jQ(document).on('click', '.ckm_media_edit', function(e) {

        var image_id = jQ(this).attr('rel');
        
        e.preventDefault();
        var image_frame;
        if(image_frame){
            image_frame.open();
        }
        
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Select Media',
            multiple : false,
            library : {
                type : 'image',
            }
        });
        
        image_frame.on('close',function() {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection =  image_frame.state().get('selection');
            var gallery_ids = new Array();
            var my_index = 0;
            selection.each(function(attachment) {
                gallery_ids[my_index] = attachment['id'];
                my_index++;
            });
            var ids = gallery_ids.join(",");
            jQ('input#' + image_id + '_edit').val(ids);
            refreshImage(ids, image_id);
        });

        image_frame.on('open',function() {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            var selection =  image_frame.state().get('selection');
            var ids = jQ('input#' + image_id + '_edit').val().split(',');
            ids.forEach(function(id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });
        });
        image_frame.open();
    });
});

function refreshImage(the_id, image_id){
    var data = {
        action: 'ckm_get_image',
        id: the_id,
        rel: image_id
    };
    jQ.get(ajaxurl, data, function(response) {
        if(response.success === true) {
            jQ('#' + image_id + '_preview').replaceWith( response.data.image );
        }
    });
}

function showQuality(fieldname, bestlength, maxlength) {
    if (fieldname!=='undefined' && fieldname!==undefined) {
        var stringlength = 0;
        var qualstring = jQ('#' + fieldname).val();
        if (jQ.trim(qualstring)!='') {
            stringlength = qualstring.length;
        }
        var qualstat = 1;
        jQ('#show_' + fieldname + '_length').text(stringlength);
        if (stringlength <= (bestlength/4)) {
            jQ('#'+fieldname).css('background-color', 'rgb(253,177,152)');
        }
        else if (stringlength <= (bestlength / 4 * 3)) {
            jQ('#'+fieldname).css('background-color', 'rgb(254,230,170)');
        }
        else if (stringlength <= bestlength) {
            jQ('#'+fieldname).css('background-color', 'rgb(160,215,180)');
        }
        else if (stringlength <= bestlength + ((maxlength - bestlength) / 3)) {
            jQ('#'+fieldname).css('background-color', 'rgb(160,215,180)');
        }
        else if (stringlength <= bestlength + ((maxlength - bestlength) / 3 * 2)) {
            jQ('#'+fieldname).css('background-color', 'rgb(254,230,170)');
        }
        else {
            jQ('#'+fieldname).css('background-color', 'rgb(253,177,152)');
        }
    }
}

function changeDesc(fieldID,checkStat) {
    if (checkStat) {
        jQ('#'+fieldID+'_checked').each(function() { jQ(this).show(); })
        jQ('#'+fieldID+'_unchecked').each(function() { jQ(this).hide();})
        }
    else {
        jQ('#'+fieldID+'_checked').each(function() { jQ(this).hide(); })
        jQ('#'+fieldID+'_unchecked').each(function() { jQ(this).show();})
        }
}

function showHide(className,checkStat) {
	if (checkStat) {
		jQ('.'+className).each(function() {
			jQ(this).removeClass('disabled');
			jQ(this).removeAttr('disabled');
			jQ(this).removeAttr('readonly');
			});
		}
	else {
		jQ('.'+className).each(function() {
			jQ(this).addClass('disabled');
			jQ(this).attr('disabled','disabled');
			jQ(this).attr('readonly','readonly');
			})
		}
	}