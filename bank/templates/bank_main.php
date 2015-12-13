<div class="wrap bank-main-area">
	<h2>Daftar Bank</h2>
	<div id="list-area"></div>
	<!-- <div id="bank-detail-area"></div> -->
</div>
<script type="text/javascript">
	jQuery(document).ready( function($){
		var data = {
			action: 'AjaxGetBankList'
		};

		$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#list-area").html(response);
		});
	});
</script>