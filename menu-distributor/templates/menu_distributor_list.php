<div class="wrap">
	<h2>Daftar Kategori Menu</h2>
	<a href='<?php echo admin_url('admin.php?page=onex-menu-distributor-tambah'); ?>' >Tambah</a>

	<?php 
	$nmr = 1;
	if( count($attributes['menudist']) > 0 ): ?>
	<table>
		<tr><th>No</th>
			<th>Nama Menu</th>
			<th>Harga</th>
			<th>Keterangan</th>
			<th>Distributor</th>
			<th>Kategori</th>
			<th></th>
		</tr>
				<?php foreach($attributes['menudist'] as $menudist ): ?>
					<tr>
						<td><?php echo $nmr; ?></td>
						<td><?php echo $menudist->nama_menudel; ?></td>
						<td><?php echo $menudist->harga_menudel; ?></td>
						<td><?php echo $menudist->keterangan_menudel; ?></td>
						<td><?php echo $menudist->distributor_id; ?></td>
						<td><?php echo $menudist->katmenu_id; ?></td>
						<td>
							<a href='<?php echo admin_url('admin.php?page=onex-distributor-hapus&id='. $distributor->id_dist); ?>'>Hapus</a> | 
							<a href='<?php echo admin_url('admin.php?page=onex-distributor-update&id='. $distributor->id_dist); ?>'>Update</a>
						</td>
					</tr>
					<?php $nmr += 1; ?>
				<?php endforeach; ?>
	</table>
	<?php else: ?>
	<p>Belum ada data.</p>
	<?php endif; ?>
	<a href='<?php echo admin_url('admin.php?page=onex-menu-distributor-tambah'); ?>' >Tambah</a>
</div>