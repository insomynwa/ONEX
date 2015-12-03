<?php
	$katmenu_nama = $_POST['katmenu-nama'];
	$katmenu_keterangan = $_POST['katmenu-keterangan'];
	$success = false;

	if(isset($_POST['katmenu-tambah-save'])){
		if(!is_null($katmenu_nama) && ! empty($katmenu_nama) && $katmenu_nama!="" ){
			$onex_kategori_menu_obj = new Onex_Kategori_Menu();
			if($katmenu_keterangan=="") $katmenu_keterangan = $katmenu_nama;
			$data = array(
				'katmenu_nama' => sanitize_text_field($_POST['katmenu-nama']),
				'katmenu_keterangan' => sanitize_text_field($_POST['katmenu-keterangan'])
			);
			$message = $onex_kategori_menu_obj->AddKategoriMenu($data);
			$success = true;
		}else{
			$message = "Kolom Nama harus diisi";
		}
	}
?>

<div class="wrap">
	<?php if( isset($message)): ?>
	<div class="updated"><p><?php echo $message; ?></p></div>
	<?php endif; ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama<br />
			<input type="text" name="katmenu-nama" />
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