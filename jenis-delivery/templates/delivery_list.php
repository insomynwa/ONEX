<div class="wrap">
	<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>' >Tambah</a>
	<table>
		<tr><th>No</th>
			<th>Jenis Delivery</th>
			<th>Keterangan</th>
			<th></th>
		</tr>
		<?php 
			$nmr = 1;
			if( count($attributes['kat_del']) > 0 ): ?>
			<?php foreach($attributes['kat_del'] as $katdel ): ?>
				<tr>
					<td><?php echo $nmr; ?></td>
					<td><?php echo $katdel->kategori; ?></td>
					<td><?php echo $katdel->keterangan; ?></td>
					<td>
						<td><a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-hapus&id='. $katdel->id_kat_del); ?>'>Hapus</a> | 
							<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-update&id='. $katdel->id_kat_del); ?>'>Update</a>
					</td>
				</tr>
				<?php $nmr += 1; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</table>
	<a href='<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>' >Tambah</a>
</div>