<!-- <div class="wrap"> 
	<h2>Daftar Distributor</h2>-->
<a href='<?php echo admin_url('admin.php?page=onex-distributor-tambah'); ?>' >Tambah</a>
<!-- <a href="#" class="ajax-link">TEST AJAX</a>
<p class="test-message"></p> -->
<table>
	<tr><th>No</th>
		<th>Distributor</th>
		<!-- <th>Gambar</th> -->
		<th>Jenis Delivery</th>
		<th></th>
		<th>Manage</th>
	</tr>
	<?php 
		$nmr = 1;
		if( count($attributes['distributor']) > 0 ): ?>
		<?php foreach($attributes['distributor'] as $distributor ): ?>
			<tr>
				<td><?php echo $nmr; ?></td>
				<td><?php echo $distributor->nama_dist; ?></td>
				<!-- <td><img src="<?php //if( $distributor->gambar == 'NOIMAGE'){ echo bloginfo('template_url').'/images/no-image.jpg';} else{ echo $distributor->gambar;} ?>" /></td> -->
				<td><?php echo $distributor->nama_katdel; ?></td>
				<td><a id="dist-id_<?php echo $distributor->id_dist; ?>" class="distributor-detail-link" href="#">Detail</a></td>
				<td>
					<a href='<?php echo admin_url('admin.php?page=onex-distributor-hapus&id='. $distributor->id_dist); ?>'>Hapus</a> | 
					<a href='<?php echo admin_url('admin.php?page=onex-distributor-update&id='. $distributor->id_dist); ?>'>Update</a>
				</td>
			</tr>
			<?php $nmr += 1; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</table>
<a href='<?php echo admin_url('admin.php?page=onex-distributor-tambah'); ?>' >Tambah</a>
<script type="text/javascript">
	jQuery(document).ready( function($) {

		$(".distributor-detail-link").click(function(){
			var id_dist = (this.id).split("_").pop();
			//alert(id_dist);

			var data = {
				action: 'AjaxGetDistributorDetail',
				distributor: id_dist
			};

			$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#distributor-detail-area").html(response);
			});

		});

	});
</script>
<!-- </div> -->