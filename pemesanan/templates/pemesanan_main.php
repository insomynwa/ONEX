<div class="wrap pemesanan-main-area">
	<h2>Daftar Pemesanan</h2>
	<div>
		<h3>Pesanan Baru</h3>
		<div id="list-area"></div>
		<div id="new-invoice-pagination-area"></div>
	</div>
	<hr>
</div>
<?php //echo plugins_url('/one-express/css/'); ?>
<script type="text/javascript">
	jQuery(document).ready( function($){

		window.doLoadPagination("unconfirmed", "div#new-invoice-pagination-area");

	});
</script>