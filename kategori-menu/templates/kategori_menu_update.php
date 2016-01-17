<?php
	$katmenu_id = $_GET['id'];
	$katmenu_nama = $_POST['katmenu-nama'];
	$katmenu_keterangan = $_POST['katmenu-keterangan'];
	$success = false;

	if(isset($_POST['katmenu-update-save'])){

		if( $katmenu_nama!="" ){

			if( ($katmenu_nama==$attributes['katmenu']['nama_katmenu']) && ($katmenu_keterangan==$attributes['katmenu']['keterangan_katmenu']) ){
			
				$message = "Tidak Ada Perubahan";
				$success = false;
			}else{
				$onex_kategori_menu_obj = new Onex_Kategori_Menu();
				if($katmenu_keterangan=="") $katmenu_keterangan = $katmenu_nama;
				$data = array(
					'katmenu_nama' => sanitize_text_field($katmenu_nama),
					'katmenu_keterangan' => sanitize_text_field($katmenu_keterangan)
				);
				$result = $onex_kategori_menu_obj->UpdateKategoriMenu( $katmenu_id, $data);
				$message = $result['message'];
				$success = true;
			}
		}else{
			$message = "Kolom Nama harus diisi";
		}
	}
?>

<div class="wrap">
	<h2>Update Kategori</h2>
	<?php if( isset($message)): ?>
	<div class="updated"><p><?php echo $message; ?></p></div>
	<?php endif; ?>
<?php if( !$success ): ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama <strong>*</strong><br />
			<input type="text" name="katmenu-nama" value="<?php if( !isset($_POST['katmenu-update-save']) ) echo $attributes['katmenu']['nama_katmenu']; else echo $katmenu_nama; ?>" />
		</p>
		<p>Distributor<br />
			<?php echo $attributes['katmenu']['nama_dist']; ?>
		</p>
		<p>Keterangan<br />
			<textarea name="katmenu-keterangan"><?php if( !isset($_POST['katmenu-update-save']) ) echo $attributes['katmenu']['keterangan_katmenu']; else echo $katmenu_keterangan; ?></textarea>
		</p>
		<p>
			<!-- <input type="submit" name="distributor-tambah-cancel" value="Batal" /> -->
			<input type="submit" name="katmenu-update-save" value="Simpan" />
		</p>
	</form>
<?php endif; ?>
	<a href="<?php echo admin_url('admin.php?page=onex-kategori-menu-page'); ?>">kembali ke Daftar Kategori Menu</a>
</div>