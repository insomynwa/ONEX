<h2>Detail Distributor</h2>
<p><img src="<?php echo $attributes['distributor']['gambar_dist']; ?>"></p>
<p><?php echo $attributes['distributor']['nama_dist']; ?></p>
<p><?php echo $attributes['distributor']['alamat_dist']; ?></p>
<p><?php echo $attributes['distributor']['telp_dist']; ?></p>
<p><?php echo $attributes['distributor']['email_dist']; ?></p>
<p><?php echo $attributes['distributor']['keterangan_dist']; ?></p>
<hr />
<?php if( !is_null($attributes['distributor_rel']['katmenu']) && sizeof($attributes['distributor_rel']['katmenu'])>0): ?>
<p><h3>Kategori</h3><a href='<?php echo admin_url("admin.php?page=onex-kategori-menu-tambah&distributor={$attributes['distributor']['id_dist']}"); ?>'>Tambah Kategori</a><br />
	<?php foreach( $attributes['distributor_rel']['katmenu'] as $kategori): ?>
	<h4><?php echo $kategori->nama_katmenu; ?></h4>
	<?php if( sizeof($attributes['distributor_rel']['menudist']["$kategori->nama_katmenu"]) > 0): ?>
	<table>
		<tr>
			<th>No</th>
			<th>Nama Menu</th>
		</tr>
		<?php $nmr=1; foreach( $attributes['distributor_rel']['menudist'][$kategori->nama_katmenu] as $menu ): ?>
		<tr>
			<td><?php echo $nmr; ?></td>
			<td><?php echo $menu->nama_menudel; ?></td>
		</tr>
		<?php $nmr+=1; endforeach; ?>
	</table>
	<?php else: ?>
	<p>Belum ada menu dalam kategori ini.</p>
	<?php endif; ?>
	<p><a href='<?php echo admin_url("admin.php?page=onex-menu-distributor-tambah&distributor={$attributes['distributor']['id_dist']}&kategori={$kategori->id_katmenu}"); ?>'>Tambah <?php echo $kategori->nama_katmenu; ?></a></p>
</p>
	<?php endforeach; ?>
<?php else: ?>
	<?php echo "Belum ada kategori."; ?>
<?php endif; ?>
<!-- <p>Menu
	<?php //$nmr=1; if( !is_null($attributes['distributor_rel']['menudist']) && sizeof($attributes['distributor_rel']['menudist'])>0 ): ?>
	<table>
		<tr>
			<th>No</th>
			<th>Kategori</th>
			<th>Nama Menu</th>
		</tr>
		<?php //foreach( $attributes['distributor_rel']['menudist'] as $menudist ): ?>
		<tr>
			<td><?php //echo $nmr; ?></td>
			<td><?php //echo $menudist->nama_katmenu; ?></td>
			<td><?php //echo $menudist->nama_menudel; ?></td>
		</tr>
		<?php //$nmr += 1; endforeach; ?>
	</table>
	<?php //else: ?>
	<p>Belum ada kategori menu yang termasuk dalam distributor ini</p>
	<?php //endif; ?>
</p> -->