<div class="wrap distributor-main-area">
	<h2>Daftar Distributor</h2>
	<div id="list-area"></div>
	<div id="distributor-detail-area"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($){
		var data = {
			action: 'AjaxGetDistributorList'
		};

		$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#list-area").html(response);
		});
	});
</script>
<!-- <div class="wrap distributor-main-area"></div>-->
<script type="text/javascript">
	// jQuery(document).ready( function($){
	// 	var data = {
	// 		action: 'get_list_distributor'
	// 	};

	// 	$.get(ajax_one_express.ajaxurl, data, function(response){
	// 			$("div.distributor-main-area").html(response);
	// 	});
	// });
</script>
