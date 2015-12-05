<?php
	$katdel_id = $_GET['id'];
	$katdel_nama = "";

	if(isset($_POST['katdel-hapus-submit'])){
		$onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
		$message = $onex_jenis_delivery_obj->DeleteDelivery($katdel_id);

	}
	
?>
	<div class="wrap">
		<h2>Hapus Jenis Delivery</h2>
	<?php if($_POST['katdel-hapus-submit']) { ?>
		<div class="updated"><?php echo $message; ?></div>
	<?php } else { ?>
		<p>Apa anda yakin akan menghapus <strong><?php echo $attributes['katdel']['nama_katdel']; ?></strong>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p>
				<input type="submit" name="katdel-hapus-submit" value="Ya" />
			</p>
		</form>
	<?php } ?>
		<a href="<?php echo admin_url('admin.php?page=onex-jenis-delivery-page') ?>">kembali ke Daftar Jenis Delivery</a>
	</div>