<h2>Detail Distributor</h2>
<p><img src="<?php echo $attributes['distributor']['gambar']; ?>"></p>
<p><?php echo $attributes['distributor']['nama']; ?></p>
<p><?php echo $attributes['distributor']['alamat']; ?></p>
<p><?php echo $attributes['distributor']['telp']; ?></p>
<p><?php echo $attributes['distributor']['email']; ?></p>
<p><?php echo $attributes['distributor']['keterangan']; ?></p>
<hr />
<p><a href="<?php echo admin_url('admin.php?page=onex-kategori-menu-page'); ?>">Kategori Menu</a>
	<ul>
		<?php if( !is_null($attributes['katdel_rel']['distributor']) && sizeof($attributes['katdel_rel']['distributor'])>0 ): ?>
			<?php foreach( $attributes['katdel_rel']['distributor'] as $distributor ): ?>
				<li><?php echo $distributor->nama; ?></li>
			<?php endforeach; ?>
		<?php else: ?>
		<li>Belum ada kategori menu yang termasuk dalam distributor ini</li>
		<?php endif; ?>
	</ul>
</p>