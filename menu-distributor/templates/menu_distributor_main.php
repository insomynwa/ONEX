<div class="wrap menudel-main-area">
	<h2>Daftar Menu</h2>
	<div>
		<?php if(sizeof( $attributes['distributor'])>0 ): ?>
		<label>Distributor:</label>
		<select id="menu-filter-distributor" name="menu-distributor">
			<option value="distributor-0" >Semua</option>
			<?php for($i=0; $i< sizeof( $attributes['distributor']); $i++): ?>
			<?php $distributor = $attributes['distributor'][$i]; ?>
			<option value="distributor-<?php _e($distributor->GetId()); ?>" ><?php _e($distributor->GetNama()); ?></option>
			<?php endfor; ?>
		</select>
		<?php endif; ?>
		<label>Jumlah list:</label>
		<select id="menu-filter-limit" name="menu-limit">
			<option value="5" <?php if(get_option('limit_menudel') == 5) echo "selected='selected'"; ?> >5</option>
			<option value="10" <?php if(get_option('limit_menudel') == 10) echo "selected='selected'"; ?> >10</option>
			<option value="20" <?php if(get_option('limit_menudel') == 20) echo "selected='selected'"; ?> >20</option>
		</select>
	</div>
	<div id="list-area"></div>
	<div id="new-menudel-pagination-area"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($){

		var filter = $("select#menu-filter-distributor").val();
		var limit = $("select#menu-filter-limit").val();
		window.doCreatePagination("menudel", limit, filter, "div#new-menudel-pagination-area");


		$("select#menu-filter-limit").on("change", function(){
			var limit = this.value;
			var filter = $("select#menu-filter-distributor").val();

			window.doCreatePagination("menudel", limit, filter, "div#new-menudel-pagination-area");
			
		});

		$("select#menu-filter-distributor").on("change", function(){
			var limit = $("select#menu-filter-limit").val();
			var filter = this.value;

			window.doCreatePagination("menudel", limit, filter, "div#new-menudel-pagination-area");
			
		});

	});
</script>