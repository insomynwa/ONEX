<?php
	$dist_id = $_GET['id'];
	$dist_nama = "";

	if(isset($_POST['dist_hapus_submit'])){
		$onex_distributor_obj = new Onex_Distributor();
		$message = $onex_distributor_obj->DeleteDistributor($dist_id);

	}
	
?>
	<div class="wrap">
		<h2>Hapus Distributor</h2>
	<?php if($_POST['dist_hapus_submit']) { ?>
		<div class="updated"><?php echo $message; ?></div>
	<?php } else { ?>
		<p>Apa anda yakin akan menghapus <strong><?php echo $attributes['distributor']['nama_dist']; ?></strong>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p>
				<input type="submit" name="dist_hapus_submit" value="Ya" />
			</p>
		</form>
	<?php } ?>
		<a href="<?php echo admin_url('admin.php?page=onex-distributor-page') ?>">kembali ke Daftar Distributor</a>
	</div>