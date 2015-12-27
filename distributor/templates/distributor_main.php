<div class="wrap distributor-main-area">
	<h2>Daftar Distributor</h2>
	<div id="list-area"></div>
	<div id="distributor-detail-area"></div>
</div>
<?php //echo plugins_url('/one-express/css/'); ?>
<script type="text/javascript">
	jQuery(document).ready( function($){
		var data = {
			action: 'AjaxRetrieveDistributorList'
		};

		$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#list-area").html(response);
		});
	});
</script>