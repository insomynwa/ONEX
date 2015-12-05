<div class="wrap kategori-menu-main-area">
	<h2>Daftar Kategori Menu</h2>
	<div id="list-area"></div>
	<div id="kategori-menu-detail-area"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($){
		var data = {
			action: 'AjaxGetKategoriMenuList'
		};

		$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#list-area").html(response);
		});
	});
</script>