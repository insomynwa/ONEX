<?php

	if(isset($_POST['bank-tambah-save'])){

		$bank_nama = sanitize_text_field( $_POST['bank-nama']);
		$bank_pemilik_rekening = sanitize_text_field( $_POST['bank-pemilik-rekening']);
		$bank_no_rekening = sanitize_text_field( $_POST['bank-no-rekening']);

		if(!is_null($bank_nama) && !empty($bank_nama) && $bank_nama!="" &&
		!is_null($bank_pemilik_rekening) && !empty($bank_pemilik_rekening) && $bank_pemilik_rekening!="" &&
		!is_null($bank_no_rekening) && !empty($bank_no_rekening) && $bank_no_rekening!=""){
			$onex_bank_obj = new Onex_Bank();
			if($bank_pemilik_rekening=="") $bank_pemilik_rekening = $bank_nama;
			$data = array(
				'katdel_nama' => sanitize_text_field($_POST['bank-nama']),
				'katdel_keterangan' => sanitize_text_field($_POST['bank-pemilik-rekening'])
			);
			$message = $onex_bank_obj->AddDelivery($data);
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
			<input type="text" name="bank-nama" />
		</p>
		<p>Atas Nama<br />
			<input type="text" name="bank-pemilik-rekening" />
		</p>
		<p>No. Rekening<br />
			<input type="text" name="bank-no-rekening" />
		</p>
		<p>
			<!-- <input type="submit" name="distributor-tambah-cancel" value="Batal" /> -->
			<input type="submit" name="bank-tambah-save" value="Tambah" />
		</p>
	</form>
	<a href="<?php echo admin_url('admin.php?page=onex-bank-page'); ?>">kembali ke Daftar Bank</a>
</div>