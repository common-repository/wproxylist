jQuery(document).ready(function($) { 
	$('#submit_new_proxy').submit(function() {
	  $.post($('#submit_new_proxy').attr('action'), { 
		   url: $('#proxy_url').val()},
		   function(data){
			 $('#submit_proxy_form').html(data);
		   });
	  return false;
	});
});