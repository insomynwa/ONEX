<?php
	$katdel = $attributes['katdel'];
?>
<h2><?php echo $katdel->GetNama(); ?> - Detail</h2>
<p><?php echo $katdel->GetNama(); ?></p>
<p><?php echo $katdel->GetKeterangan(); ?></p>
<hr />
<p><a href="<?php echo admin_url('admin.php?page=onex-distributor-page'); ?>">Distributor</a>
	<ul>
		<?php if( sizeof($attributes['distributor'])>0 ): ?>
			<?php for($i = 0; $i < sizeof($attributes['distributor']); $i++ ): ?>
				<?php $distributor = $attributes['distributor'][$i]; ?>
				<li><?php echo $distributor->GetNama(); ?></li>
			<?php endfor; ?>
		<?php else: ?>
		<li>Belum ada distributor yang termasuk dalam jenis delivery ini. <a href="<?php echo admin_url('admin.php?page=onex-distributor-tambah') ?>">Tambah</a>?</li>
		<?php endif; ?>
	</ul>
</p>