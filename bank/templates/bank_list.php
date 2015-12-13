<a href='<?php echo admin_url('admin.php?page=onex-bank-tambah'); ?>' >Tambah</a>
<table>
	<tr><th>No</th>
		<th>Bank</th>
		<th>Atas Nama</th>
		<th>No Rekening</th>
		<th>Manage</th>
	</tr>
	<?php 
		$nmr = 1;
		if( count($attributes['bank']) > 0 ): ?>
		<?php foreach($attributes['bank'] as $bank ): ?>
			<tr>
				<td><?php echo $nmr; ?></td>
				<td><?php echo $bank->nama_bank; ?></td>
				<td><?php echo $bank->pemilik_rekening; ?></td>
				<td><?php echo $bank->no_rekening; ?></td>
				<td>
					<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-hapus&id='. $bank->id_bank); ?>'>Hapus</a> | 
					<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-update&id='. $bank->id_bank); ?>'>Update</a>
				</td>
			</tr>
			<?php $nmr += 1; ?>
		<?php endforeach; ?>
	<?php endif; ?>
</table>
<a href='<?php echo admin_url('admin.php?page=onex-bank-tambah'); ?>' >Tambah</a>
<script type="text/javascript">
	jQuery(document).ready( function($) {

		$(".katdel-detail-link").click(function(){
			var id_katdel = (this.id).split("_").pop();
			//alert(id_dist);

			var data = {
				action: 'AjaxGetBankDetail',
				kategori_delivery: id_katdel
			};

			$.get(ajax_one_express.ajaxurl, data, function(response){
				$("div#katdel-detail-area").html(response);
			});

		});

	});
</script>