<?php
	$katmenu_nama = $_POST['katmenu-nama'];
	$katmenu_keterangan = $_POST['katmenu-keterangan'];
	$katmenu_distributor = $_POST['katmenu-distributor'];
	$success = false;

	if(isset($_POST['katmenu-tambah-save'])){
		if(!is_null($katmenu_nama) && !empty($katmenu_nama) && $katmenu_nama!="" && $katmenu_distributor>0 ){
			$onex_kategori_menu_obj = new Onex_Kategori_Menu();
			if($katmenu_keterangan=="") $katmenu_keterangan = $katmenu_nama;
			$data = array(
				'katmenu_nama' => sanitize_text_field($katmenu_nama),
				'katmenu_distributor' => sanitize_text_field($katmenu_distributor),
				'katmenu_keterangan' => sanitize_text_field($katmenu_keterangan)
			);
			$message = $onex_kategori_menu_obj->AddKategoriMenu($data);
			$success = true;
		}else{
			$message = "Kolom Nama harus diisi";
		}
	}
?>

<div class="wrap">
	<h2>Tambah Kategori</h2>
	<?php if( isset($message)): ?>
	<div class="updated"><p><?php echo $message; ?></p></div>
	<?php endif; ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama <strong>*</strong><br />
			<input type="text" name="katmenu-nama" />
		</p>
		<p>Distributor<br />
		<?php if( ! $attributes['single']): ?>
			<?php if( !is_null($attributes['distributor']) && sizeof($attributes['distributor']) > 0): ?>
			<select name="katmenu-distributor">
				<?php foreach ( $attributes['distributor'] as $distributor ): ?>
				<option value="<?php echo $distributor->id_dist; ?>"><?php echo $distributor->nama_dist; ?></option>
				<?php endforeach; ?>
			</select>
			<?php else: ?>
				<strong>Belum ada data distributor.</strong>
			<?php endif; ?>
		<?php else: ?>
			<?php echo $attributes['distributor']['nama_dist']; ?>
			<input type="hidden" name="katmenu-distributor" value="<?php echo $attributes['distributor']['id_dist']; ?>" />
		<?php endif; ?>
		</p>
		<p>Keterangan<br />
			<textarea name="katmenu-keterangan">
				<?php if( !$success && isset($_POST['katmenu-tambah-save']) && $katmenu_keterangan!="") echo $katmenu_keterangan; ?>
			</textarea>
		</p>
		<p>
			<!-- <input type="submit" name="distributor-tambah-cancel" value="Batal" /> -->
			<input type="submit" name="katmenu-tambah-save" value="Tambah" />
		</p>
	</form>
	<a href="<?php echo admin_url('admin.php?page=onex-kategori-menu-page'); ?>">kembali ke Daftar Kategori Menu</a>
</div>