	<a href='<?php echo admin_url('admin.php?page=onex-menu-distributor-tambah'); ?>' >Tambah</a>

	<?php 
	$nmr = $attributes['nomor'];
	if( sizeof($attributes['menu']) > 0 ): ?>
	<table class="table table-hover table-responsive">
		<tr><th>No</th>
			<th>Nama Menu</th>
			<th>Harga</th>
			<th>Kategori</th>
			<th>Distributor</th>
			<th></th>
		</tr>
				<?php for( $i=0; $i< sizeof($attributes['menu']) ; $i++): ?>
					<?php 
					$menu = $attributes['menu'][$i];
					$katmenu = $attributes['katmenu'][$i];
					$distributor = $attributes['distributor'][$i];
					?>
					<tr>
						<td><?php echo $nmr; ?></td>
						<td><?php echo $menu->GetNama(); ?></td>
						<td><?php echo $menu->GetHarga(); ?></td>
						<td><?php echo $katmenu->GetNama(); ?></td>
						<td><?php echo $distributor->GetNama(); ?></td>
						<td>
							<a href="<?php echo admin_url('admin.php?page=onex-menu-distributor-hapus&menu='. $menu->GetId()); ?>">Hapus</a> | 
							<a href="<?php echo admin_url('admin.php?page=onex-menu-distributor-update&menu='. $menu->GetId()); ?>">Update</a>
						</td>
					</tr>
					<?php $nmr += 1; ?>
				<?php endfor; ?>
	</table>
	<?php else: ?>
	<p>Belum ada data.</p>
	<?php endif; ?>
	<a href='<?php echo admin_url('admin.php?page=onex-menu-distributor-tambah'); ?>' >Tambah</a>