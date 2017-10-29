jQuery(document).ready(function() {
 
jQuery('.upload_button').click(function() {
	
	var id = jQuery(this).attr('id');
 formfield = jQuery('#upload_input_'+id).attr('name');
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

 window.send_to_editor = function(html) {
	imgurl = jQuery("<div>" + html + "</div>").find('img').attr('src');
	jQuery('#upload_input_'+id).val(imgurl);
	jQuery('#fs-preview_'+id).css('background','url("'+imgurl+'")');	
 tb_remove();
}

 return false;

});

jQuery('.upload_input').change(function() {
	update_preview(jQuery(this));
	console.log("blur")
});


});

function update_preview(object)
{

	var id = object.attr('id');
	var imgurl = object.val();

	id=id.substring(id.length-1,id.length);

	jQuery('#fs-preview_'+id).css('background','url('+imgurl+')');

}