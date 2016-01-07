<a href='<?php echo admin_url('admin.php?page=onex-bank-tambah'); ?>' >Tambah</a>
<table class="table table-hover table-responsive">
	<tr><th>No</th>
		<th>Bank</th>
		<th>Atas Nama</th>
		<th>No Rekening</th>
		<th>Manage</th>
	</tr>
	<?php 
		$nmr = 1;
		if( sizeof($attributes['bank']) > 0 ): ?>
		<?php for($i=0; $i < sizeof($attributes['bank']); $i++ ): ?>
			<?php $bank = $attributes['bank'][$i]; ?>
			<tr>
				<td><?php echo $nmr; ?></td>
				<td><?php echo $bank->GetNama(); ?></td>
				<td><?php echo $bank->GetPemilik(); ?></td>
				<td><?php echo $bank->GetNoRekening(); ?></td>
				<td>
					<a href='<?php echo admin_url('admin.php?page=onex-bank-hapus&id='. $bank->GetId()); ?>'>Hapus</a> | 
					<a href='<?php echo admin_url('admin.php?page=onex-bank-update&id='. $bank->GetId()); ?>'>Update</a>
				</td>
			</tr>
			<?php $nmr += 1; ?>
		<?php endfor; ?>
	<?php endif; ?>
</table>
<a href='<?php echo admin_url('admin.php?page=onex-bank-tambah'); ?>' >Tambah</a>
<script type="text/javascript">
	/*jQuery(document).ready( function($) {

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

	});*/
</script>