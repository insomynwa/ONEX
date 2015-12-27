<?php
	$katmenu_id = $attributes->GetId();
	$katmenu_nama = "";

	if(isset($_POST['katmenu-hapus-submit'])){
		$menudel = new Onex_Menu_Distributor();

		$deleted_menudel = $menudel->GetAllMenu_Kategori($katmenu_id);
		if( !is_null($deleted_menudel) && !empty($deleted_menudel)){
			$pemesanan = new Onex_Pemesanan_Menu();
			foreach($deleted_menudel as $delmenu){
				$delmenu_id = $delmenu->id_menudel;
				$pemesanan->DeletePesananMenu_MenuDelivery( $delmenu_id);
			}
			$menudel->DeleteMenu_Kategori( $katmenu_id);
		}
		$result = $attributes->DeleteKategoriMenu();
		$message = $result['message'];

	}
	
?>
	<div class="wrap">
		<h2>Hapus Kategori Menu</h2>
	<?php if($_POST['katmenu-hapus-submit']) { ?>
		<div class="updated"><?php echo $message; ?></div>
	<?php } else { ?>
		<p>Apa anda yakin akan menghapus <strong><?php echo $attributes->GetNama(); ?></strong>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p>
				<input type="submit" name="katmenu-hapus-submit" value="Ya" /><br />
				<span>(PERINGATAN) proses ini akan menghapus semua menu, dan pemesanan yang berkaitan dengan kategori ini!</span>
			</p>
		</form>
	<?php } ?>
		<a href="<?php echo admin_url('admin.php?page=onex-kategori-menu-page') ?>">kembali ke Daftar Kategori Menu</a>
	</div>