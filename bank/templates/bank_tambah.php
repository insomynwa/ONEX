<?php
	
	$isPosted = isset($_POST['bank-tambah-save']);
	if( $isPosted){

		$bank_nama = sanitize_text_field( $_POST['bank-nama']);
		$bank_pemilik_rekening = sanitize_text_field( $_POST['bank-pemilik-rekening']);
		$bank_no_rekening = sanitize_text_field( $_POST['bank-no-rekening']);

		if($bank_nama!="" && $bank_pemilik_rekening!="" && $bank_no_rekening!=""){
			$bank = new Onex_Bank();
			
			$bank->SetNama( $bank_nama);
			$bank->SetPemilik( $bank_pemilik_rekening);
			$bank->SetNoRekening( $bank_no_rekening);
			$result = $bank->AddNewBank();
			
			$message = $result['message'];
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
		<p>Nama Bank<strong>*</strong><br />
			<input type="text" name="bank-nama" value="<?php if($isPosted && !$result['status']) echo $bank_nama; ?>" />
		</p>
		<p>Atas Nama<strong>*</strong><br />
			<input type="text" name="bank-pemilik-rekening" value="<?php if($isPosted && !$result['status']) echo $bank_pemilik_rekening; ?>" />
		</p>
		<p>No. Rekening<strong>*</strong><br />
			<input type="number" name="bank-no-rekening" value="<?php if($isPosted && !$result['status']) echo $bank_no_rekening; ?>" />
		</p>
		<p>
			<!-- <input type="submit" name="distributor-tambah-cancel" value="Batal" /> -->
			<input type="submit" name="bank-tambah-save" value="Tambah" />
		</p>
	</form>
	<a href="<?php echo admin_url('admin.php?page=onex-bank-page'); ?>">kembali ke Daftar Bank</a>
</div>