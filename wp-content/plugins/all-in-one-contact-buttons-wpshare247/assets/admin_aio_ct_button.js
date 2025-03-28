jQuery(document).ready(function(e) {
	jQuery( '.colorpicker' ).wpColorPicker();

	ws247_aio_ct_normal_sortable();
	ws247_aio_ct_search_icon();
	ws247_aio_ct_add_new_icon();
	ws247_aio_ct_add_new_icon_btn();
});

function ws247_aio_ct_normal_sortable(){
	jQuery( "#ws247-aio-ct-normal-table-sortcontainer" ).sortable({
		update: function(e, ui) {
			jQuery("#ws247-aio-pro-add-icon").click();
		}
	}).disableSelection();
}

function ws247_aio_ct_search_icon(){
	jQuery("#ws247-aio-pro-icon-search").keyup(function() {
	 	var keywords = jQuery(this).val();
		if(keywords == ''){
			jQuery("#dialog-ws247-aio-pro-icons .icon-add-new").show();
		}else{
			keywords = keywords.toLowerCase();
			jQuery("#dialog-ws247-aio-pro-icons .icon-add-new").hide();
			jQuery("#dialog-ws247-aio-pro-icons .icon-add-new[data-find*="+keywords+"]").show();
		}
		console.log( keywords );
	});
}

function ws247_aio_ct_add_new_icon(){
	jQuery("#dialog-ws247-aio-pro-icons .icon-add-new").click(function(event) {
		jQuery('.icon-add-new.current').removeClass('current');

		jQuery(".ws247-aio-pro-form").show();
		jQuery(this).addClass('current');
		return false;
	});
}

function ws247_aio_ct_add_new_icon_btn(){
	jQuery("#dialog-ws247-aio-pro-icons #js-ajx-add-icon").click(function(event) {
		var active_len = jQuery("#dialog-ws247-aio-pro-icons .icon-add-new.current").length;
		if(active_len){
			jQuery("#ws247-aio-pro-add-icon").click();
		}else{
			alert('Chọn 1 icon để thêm');
		}
		return false;
	});
}