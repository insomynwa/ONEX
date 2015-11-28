<div class="wrap">
	<a href='<?php echo admin_url('admin.php?page=onex-distributor-tambah'); ?>' >Tambah</a>
	<table>
		<tr><th>No</th>
			<th>Distributor</th>
			<th>Alamat</th>
			<th>Telp</th>
			<th>Email</th>
			<th>Keterangan</th>
			<th>Gambar</th>
			<th>Jenis Delivery</th>
			<th></th>
		</tr>
		<?php 
			$nmr = 1;
			if( count($attributes['distributor']) > 0 ): ?>
			<?php foreach($attributes['distributor'] as $distributor ): ?>
				<tr>
					<td><?php echo $nmr; ?></td>
					<td><?php echo $distributor->nama; ?></td>
					<td><?php echo $distributor->alamat; ?></td>
					<td><?php echo $distributor->telp; ?></td>
					<td><?php echo $distributor->email; ?></td>
					<td><?php echo $distributor->keterangan; ?></td>
					<td><img src="<?php if( $distributor->gambar == 'NOIMAGE'){ echo bloginfo('template_url').'/images/no-image.jpg';} else{ echo $distributor->gambar;} ?>" /></td>
					<td><?php echo $distributor->kategori; ?></td>
					<td>
						<td><a href='<?php echo admin_url('admin.php?page=onex-distributor-hapus&id='. $distributor->id_dist); ?>'>Hapus</a> | 
							<a href='<?php echo admin_url('admin.php?page=onex-distributor-update&id='. $distributor->id_dist); ?>'>Update</a>
					</td>
				</tr>
				<?php $nmr += 1; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</table>
	<a href='<?php echo admin_url('admin.php?page=onex-distributor-tambah'); ?>' >Tambah</a>
</div>