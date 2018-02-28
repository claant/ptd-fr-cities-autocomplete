jQuery(document).ready(function($) {	
	
	$('#company_works_at').autoComplete({
		source: function(name, response) {
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: my_ajax_object.ajax_url,
				data: 'action=get_listing_names&name='+name,
				success: function(data) {
					response(data);
				}
			});
		}

	});
	

	$('#my_popup').popup({
	  color: 'rgba(0, 0, 0, 0.8)',
	  opacity: 1,
	  transition: '0.3s',
	  scrolllock: true
	});
	 
});
