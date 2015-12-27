<?php
	$menudel_id = $attributes->GetId();

	if(isset($_POST['menu-hapus-submit'])){
		//$menu_distributor_obj = new Onex_Menu_Distributor();
		$pemesanan = new Onex_Pemesanan_Menu();
		$pemesanan->DeletePesananMenu_MenuDelivery( $menudel_id);
		
		$result = $attributes->DeleteMenu();
		$message = $result['message'];
	}
	
?>
<div class="wrap">
	<h2>Hapus Menu</h2>
<?php if($_POST['menu-hapus-submit']) { ?>
	<div class="updated"><?php echo $message; ?></div>
<?php } else { ?>
	<p>Apa anda yakin akan menghapus <strong><?php echo $attributes->GetNama(); ?></strong>?</p>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<p>
			<input type="submit" name="menu-hapus-submit" value="Ya" /><br />
			<span>(PERINGATAN) proses ini akan menghapus semua pemesanan yang berkaitan dengan menu ini!</span>
		</p>
	</form>
<?php } ?>
	<a href="<?php echo admin_url('admin.php?page=onex-distributor-page') ?>">kembali ke Daftar Distributor</a>
</div>