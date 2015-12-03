<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>' >Tambah</a>
<table>
	<tr><th>No</th>
		<th>Jenis Delivery</th>
		<th>Keterangan</th>
		<th></th>
		<th>Manage</th>
	</tr>
	<?php 
		$nmr = 1;
		if( count($attributes['kat_del']) > 0 ): ?>
		<?php foreach($attributes['kat_del'] as $katdel ): ?>
			<tr>
				<td><?php echo $nmr; ?></td>
				<td><?php echo $katdel->kategori; ?></td>
				<td><?php echo $katdel->keterangan; ?></td>
				<td><a id="katdel-id_<?php echo $katdel->id_kat_del; ?>" class="katdel-detail-link" href="#">Detail</a></td>
				<td>
					<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-hapus&id='. $katdel->id_kat_del); ?>'>Hapus</a> | 
					<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-update&id='. $katdel->id_kat_del); ?>'>Update</a>
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
				katdel: id_katdel
			};

			$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#katdel-detail-area").html(response);
			});

		});

	});
</script>