<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>' >Tambah</a>
<table>
	<tr><th>No</th>
		<th>Jenis Delivery</th>
		<!-- <th>Keterangan</th> -->
		<th></th>
		<th>Manage</th>
	</tr>
	<?php 
		$nmr = 1;
		if( count($attributes['katdel']) > 0 ): ?>
		<?php foreach($attributes['katdel'] as $katdel ): ?>
			<tr>
				<td><?php echo $nmr; ?></td>
				<td><?php echo $katdel->nama_katdel; ?></td>
				<!-- <td><?php //echo $katdel->keterangan; ?></td> -->
				<td><a id="katdel-id_<?php echo $katdel->id_katdel; ?>" class="katdel-detail-link" href="#">Detail</a></td>
				<td>
					<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-hapus&id='. $katdel->id_katdel); ?>'>Hapus</a> | 
					<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-update&id='. $katdel->id_katdel); ?>'>Update</a>
				</td>
			</tr>
			<?php $nmr += 1; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</table>
<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>' >Tambah</a>
<script type="text/javascript">
	jQuery(document).ready( function($) {

		$(".katdel-detail-link").click(function(){
			var id_katdel = (this.id).split("_").pop();
			//alert(id_dist);

			var data = {
				action: 'AjaxGetJenisDeliveryDetail',
				kategori_delivery: id_katdel
			};

			$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#katdel-detail-area").html(response);
			});

		});

	});
</script>