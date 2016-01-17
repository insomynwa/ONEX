<a href='<?php echo admin_url('admin.php?page=onex-kategori-menu-tambah'); ?>' >Tambah</a>

<?php 
$nmr = 1;
if( sizeof($attributes['katmenu']) > 0 ): ?>
<table class="table table-hover table-responsive">
	<tr><th>NO</th>
		<th>KATEGORI</th>
		<th>DISTRIBUTOR</th>
		<th></th>
	</tr>
			<?php for( $i=0; $i<sizeof($attributes['katmenu']); $i++ ): ?>
				<?php $katmenu = $attributes['katmenu'][$i]; ?>
				<tr>
					<td><?php echo $nmr; ?></td>
					<td><?php echo $katmenu->GetNama(); ?></td>
					<td><?php echo $attributes['distributor'][$i]->GetNama(); ?></td>
					<td>
						<a href='<?php echo admin_url('admin.php?page=onex-kategori-menu-hapus&id='. $katmenu->GetId()); ?>'>Hapus</a> | 
						<a href='<?php echo admin_url('admin.php?page=onex-kategori-menu-update&id='. $katmenu->GetId()); ?>'>Update</a>
					</td>
				</tr>
				<?php $nmr += 1; ?>
			<?php endfor ?>
</table>
<?php else: ?>
<p>Belum ada data.</p>
<?php endif; ?>
<a href='<?php echo admin_url('admin.php?page=onex-kategori-menu-tambah'); ?>' >Tambah</a>