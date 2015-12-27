<div class="wrap delivery-main-area">
	<h2>Daftar Jenis Delivery</h2>
	<div id="list-area"></div>
	<div id="katdel-detail-area"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($){
		var data = {
			action: 'AjaxRetrieveJenisDeliveryList'
		};

		$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#list-area").html(response);
		});
	});
</script>