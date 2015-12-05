<h2>Detail Jenis Delivery</h2>
<p><?php echo $attributes['katdel']['nama_katdel']; ?></p>
<p><?php echo $attributes['katdel']['keterangan_katdel']; ?></p>
<hr />
<p><a href="<?php echo admin_url('admin.php?page=onex-distributor-page'); ?>">Distributor</a>
	<ul>
		<?php if( !is_null($attributes['katdel_rel']['distributor']) && sizeof($attributes['katdel_rel']['distributor'])>0 ): ?>
			<?php foreach( $attributes['katdel_rel']['distributor'] as $distributor ): ?>
				<li><?php echo $distributor->nama_dist; ?></li>
			<?php endforeach; ?>
		<?php else: ?>
		<li>Belum ada distributor yang termasuk dalam jenis delivery ini</li>
		<?php endif; ?>
	</ul>
</p>