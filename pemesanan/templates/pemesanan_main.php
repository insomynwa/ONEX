<div class="wrap pemesanan-main-area">
	<h2>Daftar Pemesanan</h2>
	<div>
		<h3>Pemesanan - <span id="pemesanan-subtitle"></span></h3>
		<div>
			<label for="pemesanan-status">Status</label>
			<select id="pemesanan-filter-status" name="pemesanan-status">
				<option value="pemesanan-0">Semua</option>
				<?php if(sizeof($attributes['status']) > 0): ?>
				<?php for( $i=0; $i < sizeof($attributes['status']); $i++): ?>
				<?php $status= $attributes['status'][$i]; ?>
				<option value="pemesanan-<?php echo $status->GetId(); ?>"><?php echo $status->GetStatus(); ?></option>
				<?php endfor; ?>
				<?php endif; ?>
				<!-- <option value="pemesanan-waiting">Baru</option>
				<option value="pemesanan-pengiriman">Dalam Pengiriman</option>
				<option value="pemesanan-terkirim">Terkirim</option>
				<option value="pemesanan-batal">Batal</option> -->
			</select>
			<label>Jumlah list:</label>
			<select id="pemesanan-filter-limit" name="pemesanan-limit">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
		</div>
		<div id="list-area"></div>
		<div id="new-invoice-pagination-area"></div>
	</div>
	<hr>
</div>
<?php //echo plugins_url('/one-express/css/'); ?>
<script type="text/javascript">
	jQuery(document).ready( function($){

		$("select#pemesanan-filter-status option:first-child").prop("selected", "selected");
		var select_pemesanan = $("select#pemesanan-filter-status").val();
		var forlist = select_pemesanan.substr( 0, select_pemesanan.indexOf('-'));
		var status = select_pemesanan.split('-').pop();
		var limit = $("select#pemesanan-filter-limit").val();
		window.doCreatePagination( forlist, limit, status, "div#new-invoice-pagination-area");
		$("span#pemesanan-subtitle").html($("select#pemesanan-filter-status option:first-child").text());

		$("select#pemesanan-filter-status").on("change", function(){

			var forlist = (this.value).substr( 0, select_pemesanan.indexOf('-'));
			var status = (this.value).split('-').pop();
			var limit = $("select#pemesanan-filter-limit").val();

			window.doCreatePagination(forlist, limit, status, "div#new-invoice-pagination-area");
			$("span#pemesanan-subtitle").html($("select#pemesanan-filter-status option:selected").text());
			
		});

		$("select#pemesanan-filter-limit").on("change", function(){
			var select_pemesanan = $("select#pemesanan-filter-status").val();
			var forlist = select_pemesanan.substr( 0, select_pemesanan.indexOf('-'));
			var status = select_pemesanan.split('-').pop();
			var limit = this.value;

			window.doCreatePagination(forlist, limit, status, "div#new-invoice-pagination-area");
			$("span#pemesanan-subtitle").html($("select#pemesanan-filter-status option:selected").text());
			
		});
	});
</script>