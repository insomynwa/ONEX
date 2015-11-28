<?php
	$katdel_nama = $_POST['katdel-nama'];
	$katdel_keterangan = $_POST['katdel-keterangan'];
	$success = false;

	if(isset($_POST['katdel-tambah-save'])){
		if(!is_null($katdel_nama) && ! empty($katdel_nama) && $katdel_nama!="" ){
			$onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
			if($katdel_keterangan=="") $katdel_keterangan = $katdel_nama;
			$data = array(
				'katdel_nama' => sanitize_text_field($_POST['katdel-nama']),
				'katdel_keterangan' => sanitize_text_field($_POST['katdel-keterangan'])
			);
			$message = $onex_jenis_delivery_obj->AddDelivery($data);
			$success = true;
		}else{
			$message = "Kolom Nama harus diisi";
		}
	}
?>

<div class="wrap">
	<?php if(isset($message)): ?>
	<div class="updated"><p><?php echo $message; ?></p></div>
	<?php endif; ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama<br />
			<input type="text" name="katdel-nama" />
		</p>
		<p>Keterangan<br />
			<textarea name="katdel-keterangan">
				<?php if( !$success && isset($_POST['katdel-tambah-save']) && $katdel_keterangan!="") echo $katdel_keterangan; ?>
			</textarea>
		</p>
		<p>
			<!-- <input type="submit" name="distributor-tambah-cancel" value="Batal" /> -->
			<input type="submit" name="katdel-tambah-save" value="Tambah" />
		</p>
	</form>
	<a href="<?php echo admin_url('admin.php?page=onex-jenis-delivery-page'); ?>">kembali ke Daftar Jenis Delivery</a>
</div>