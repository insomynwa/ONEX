<div class="wrap kategori-menu-main-area">
	<h2>Daftar Kategori Menu</h2>
	<div>
		<?php if(sizeof( $attributes['distributor'])>0 ): ?>
		<label>Distributor:</label>
		<select id="kategori-menu-filter-distributor" name="kategori-menu-distributor">
			<option value="distributor-0" >Semua</option>
			<?php for($i=0; $i< sizeof( $attributes['distributor']); $i++): ?>
			<?php $distributor = $attributes['distributor'][$i]; ?>
			<option value="distributor-<?php _e($distributor->GetId()); ?>" ><?php _e($distributor->GetNama()); ?></option>
			<?php endfor; ?>
		</select>
		<?php endif; ?>
		<label>Jumlah list:</label>
		<select id="kategori-menu-filter-limit" name="kategori-menu-limit">
			<option value="5" <?php if(get_option('limit_katmenu') == 5) echo "selected='selected'"; ?> >5</option>
			<option value="10" <?php if(get_option('limit_katmenu') == 10) echo "selected='selected'"; ?> >10</option>
			<option value="20" <?php if(get_option('limit_katmenu') == 20) echo "selected='selected'"; ?> >20</option>
		</select>
	</div>
	<div id="list-area"></div>
	<div id="new-katmenu-pagination-area"></div>
</div>
<script type="text/javascript">
	jQuery(document).ready( function($){
		var filter = $("select#kategori-menu-filter-distributor").val();
		var limit = $("select#kategori-menu-filter-limit").val();
		window.doCreatePagination("kategorimenu", limit, filter, "div#new-katmenu-pagination-area");


		$("select#kategori-menu-filter-limit").on("change", function(){
			var limit = this.value;
			var filter = $("select#kategori-menu-filter-distributor").val();

			window.doCreatePagination("kategorimenu", limit, filter, "div#new-katmenu-pagination-area");
			
		});

		$("select#kategori-menu-filter-distributor").on("change", function(){
			var limit = $("select#kategori-menu-filter-limit").val();
			var filter = this.value;

			window.doCreatePagination("kategorimenu", limit, filter, "div#new-katmenu-pagination-area");
			
		});

		/*var data = {
			action: 'AjaxGetKategoriMenuList'
		};

		$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#list-area").html(response);
		});*/
	});
</script>