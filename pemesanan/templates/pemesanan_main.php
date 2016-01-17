<div class="wrap pemesanan-main-area">
	<h2>Daftar Pemesanan</h2>
	<div>
		<h3>Pemesanan - <span id="pemesanan-subtitle"></span></h3>
		<div>
			<?php if(sizeof( $attributes['distributor'])>0 ): ?>
			<label>Distributor:</label>
			<select id="pemesanan-filter-distributor" name="pemesanan-distributor">
				<option value="distributor-0" >Semua</option>
				<?php for($i=0; $i< sizeof( $attributes['distributor']); $i++): ?>
				<?php $distributor = $attributes['distributor'][$i]; ?>
				<option value="distributor-<?php _e($distributor->GetId()); ?>" ><?php _e($distributor->GetNama()); ?></option>
				<?php endfor; ?>
			</select>
			<?php endif; ?>
			<label for="pemesanan-status">Status</label>
			<select id="pemesanan-filter-status" name="pemesanan-status">
				<option value="status-0">Semua</option>
				<?php if(sizeof($attributes['status']) > 0): ?>
				<?php for( $i=0; $i < sizeof($attributes['status']); $i++): ?>
				<?php $status= $attributes['status'][$i]; ?>
				<option value="status-<?php echo $status->GetId(); ?>"><?php echo $status->GetStatus(); ?></option>
				<?php endfor; ?>
				<?php endif; ?>
			</select>
			<label>Jumlah list:</label>
			<select id="pemesanan-filter-limit" name="pemesanan-limit">
				<option value="5" <?php if(get_option('limit_pemesanan') == 5) echo "selected='selected'"; ?> >5</option>
				<option value="10" <?php if(get_option('limit_pemesanan') == 10) echo "selected='selected'"; ?> >10</option>
				<option value="20" <?php if(get_option('limit_pemesanan') == 20) echo "selected='selected'"; ?> >20</option>
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

		//$("select#pemesanan-filter-status option:first-child").prop("selected", "selected");

		var filter_1 = $("select#pemesanan-filter-distributor").val();
		var filter_2 = $("select#pemesanan-filter-status").val();
		var filter = filter_1+"-"+filter_2;
		//var forlist = select_pemesanan.substr( 0, select_pemesanan.indexOf('-'));
		//var status = select_pemesanan.split('-').pop();
		var limit = $("select#pemesanan-filter-limit").val();

		window.doCreatePagination( "pemesanan", limit, filter, "div#new-invoice-pagination-area");
		$("span#pemesanan-subtitle").html($("select#pemesanan-filter-status option:first-child").text());

		$("select#pemesanan-filter-status").on("change", function(){

			var filter_1 = $("select#pemesanan-filter-distributor").val();
			var filter_2 = this.value;
			var filter = filter_1+"-"+filter_2;
			var limit = $("select#pemesanan-filter-limit").val();

			window.doCreatePagination("pemesanan", limit, filter, "div#new-invoice-pagination-area");
			$("span#pemesanan-subtitle").html($("select#pemesanan-filter-status option:selected").text());
			
		});

		$("select#pemesanan-filter-distributor").on("change", function(){
			var filter_1 = this.value;
			var filter_2 = $("select#pemesanan-filter-status").val();
			var filter = filter_1+"-"+filter_2;
			var limit = $("select#pemesanan-filter-limit").val();

			window.doCreatePagination("pemesanan", limit, filter, "div#new-invoice-pagination-area");
			
		});

		$("select#pemesanan-filter-limit").on("change", function(){
			var filter_1 = $("select#pemesanan-filter-distributor").val();
			var filter_2 = $("select#pemesanan-filter-status").val();
			var filter = filter_1+"-"+filter_2;
			var limit = this.value;

			window.doCreatePagination("pemesanan", limit, filter, "div#new-invoice-pagination-area");
			$("span#pemesanan-subtitle").html($("select#pemesanan-filter-status option:selected").text());
			
		});
	});
</script>