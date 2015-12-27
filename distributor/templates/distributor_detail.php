<?php $distributor = $attributes['distributor']; ?>
<h2><?php echo $distributor->GetNama(); ?> - Detail</h2>
<p><img src="<?php echo $distributor->GetGambar(); ?>"></p>
<?php $distributor_id = $distributor->GetId(); ?>
<p><?php echo $distributor->GetNama(); ?></p>
<p><?php echo $distributor->GetAlamat(); ?></p>
<p><?php echo $distributor->GetTelp(); ?></p>
<p><?php echo $distributor->GetEmail(); ?></p>
<p><?php echo $distributor->GetKeterangan(); ?></p>
<hr />
<?php if( sizeof($attributes['katmenu'])>0): ?>
<p><h3>Kategori</h3><a href='<?php echo admin_url("admin.php?page=onex-kategori-menu-tambah&distributor={$distributor_id}"); ?>'>Tambah Kategori</a><br />
	<?php for( $i=0; $i < sizeof($attributes['katmenu']); $i++): ?>
	<?php 
		$katmenu = $attributes['katmenu'][$i]; 
		$katmenu_id = $katmenu->GetId();
	?>
	<h4><?php echo $katmenu->GetNama(); ?></h4>
	<?php if( sizeof($attributes[$katmenu_id]) > 0): ?>
	<table class="table table-responsive table-hover">
		<tr>
			<th>No</th>
			<th>Nama Menu</th>
			<th></th>
		</tr>
		<?php $nmr=1; for( $j = 0; $j < sizeof($attributes[$katmenu_id]); $j++ ): ?>
		<?php $menudel = $attributes[$katmenu_id][$j]; $menudel_id = $menudel->GetId(); ?>
		<tr>
			<td><?php echo $nmr; ?></td>
			<td><?php echo $menudel->GetNama(); ?></td>
			<td><a href="<?php echo admin_url('admin.php?page=onex-menu-distributor-hapus&menu='. $menudel_id); ?>">Hapus</a> | 
				<a href="<?php echo admin_url('admin.php?page=onex-menu-distributor-update&menu='. $menudel_id); ?>">Update</a></td>
		</tr>
		<?php $nmr+=1; endfor; ?>
	</table>
	<?php else: ?>
	<p>Belum ada menu dalam kategori ini.</p>
	<?php endif; ?>
	<p><a href='<?php echo admin_url("admin.php?page=onex-menu-distributor-tambah&distributor={$distributor_id}&kategori={$katmenu_id}"); ?>'>Tambah <?php echo $kategori->nama_katmenu; ?></a></p>
</p>
	<?php endfor; ?>
<?php else: ?>
	<?php echo "Belum ada kategori. "; ?><a href='<?php echo admin_url("admin.php?page=onex-kategori-menu-tambah&distributor={$distributor_id}"); ?>'>Tambah</a>?
<?php endif; ?>