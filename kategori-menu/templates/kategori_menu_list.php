<a href='<?php echo admin_url('admin.php?page=onex-kategori-menu-tambah'); ?>' >Tambah</a>

<?php 
$nmr = 1;
if( sizeof($attributes['katmenu']) > 0 ): ?>
<table class="table table-hover table-responsive">
	<tr><th>No</th>
		<th>Kategori</th>
		<th>Distributor</th>
		<th></th>
	</tr>
			<?php foreach($attributes['katmenu'] as $katmenu ): ?>
				<tr>
					<td><?php echo $nmr; ?></td>
					<td><?php echo $katmenu->nama_katmenu; ?></td>
					<td><?php echo $katmenu->nama_dist; ?></td>
					<td>
						<a href='<?php echo admin_url('admin.php?page=onex-kategori-menu-hapus&id='. $katmenu->id_katmenu); ?>'>Hapus</a> | 
						<a href='<?php echo admin_url('admin.php?page=onex-kategori-menu-update&id='. $katmenu->id_katmenu); ?>'>Update</a>
					</td>
				</tr>
				<?php $nmr += 1; ?>
			<?php endforeach; ?>
</table>
<?php else: ?>
<p>Belum ada data.</p>
<?php endif; ?>
<a href='<?php echo admin_url('admin.php?page=onex-kategori-menu-tambah'); ?>' >Tambah</a>