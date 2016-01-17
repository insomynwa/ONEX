<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>' >Tambah</a>
<table class="table table-hover table-responsive">
	<tr><th>NO</th>
		<th>JENIS DELIVERY</th>
		<!-- <th>Keterangan</th> -->
		<th></th>
		<th>MANAGE</th>
	</tr>
	<?php 
		$nmr = 1;
		if( sizeof($attributes['katdel']) > 0 && !is_null($attributes) && !empty($attributes) ): ?>
		<?php for($i=0; $i< sizeof($attributes['katdel']); $i++): ?>
		<?php $katdel = $attributes['katdel'][$i]; ?>
			<tr>
				<td><?php echo $nmr; ?></td>
				<td><?php echo $katdel->GetNama(); ?></td>
				<!-- <td><?php //echo $katdel->keterangan; ?></td> -->
				<td><a id="katdel-id_<?php echo $katdel->GetId(); ?>" class="katdel-detail-link" href="#">Detail</a></td>
				<td>
					<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-hapus&id='. $katdel->GetId()); ?>'>Hapus</a> | 
					<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-update&id='. $katdel->GetId()); ?>'>Update</a>
				</td>
			</tr>
			<?php $nmr += 1; ?>
		<?php endfor; ?>
	<?php endif; ?>
</table>
<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>' >Tambah</a>
<script type="text/javascript">
	jQuery(document).ready( function($) {

		$(".katdel-detail-link").click(function(){
			var id_katdel = (this.id).split("_").pop();
			//alert(id_dist);

			var data = {
				action: 'AjaxRetrieveJenisDeliveryDetail',
				kategori_delivery: id_katdel
			};

			$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#katdel-detail-area").html(response);
			});

		});

	});
</script>