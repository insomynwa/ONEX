<?php
	$menudel_id = $_GET['menu'];

	if(isset($_POST['menu-hapus-submit'])){
		$menu_distributor_obj = new Onex_Menu_Distributor();
		$message = $menu_distributor_obj->DeleteMenuDistributor( $menudel_id);
	}
	
?>
<div class="wrap">
	<h2>Hapus Menu</h2>
<?php if($_POST['menu-hapus-submit']) { ?>
	<div class="updated"><?php echo $message; ?></div>
<?php } else { ?>
	<p>Apa anda yakin akan menghapus <strong><?php echo $attributes['menudel']['nama_menudel']; ?></strong>?</p>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<p>
			<input type="submit" name="menu-hapus-submit" value="Ya" />
		</p>
	</form>
<?php } ?>
	<a href="<?php echo admin_url('admin.php?page=onex-distributor-page') ?>">kembali ke Daftar Distributor</a>
</div>