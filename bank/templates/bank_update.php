<?php
	$bank_id = $attributes->GetId();

	$isPosted = isset($_POST['bank-update-submit']);
	if($isPosted){

		$bank_nama = sanitize_text_field( $_POST['bank-nama']);
		$bank_pemilik = sanitize_text_field( $_POST['bank-pemilik-rekening']);
		$bank_no_rekening = sanitize_text_field( $_POST['bank-no-rekening']);

		if( $bank_nama!="" && $bank_pemilik!="" && $bank_no_rekening!="" ){
			if( $bank_nama==$attributes->GetNama() && $bank_pemilik==$attributes->GetPemilik()
				&& $bank_no_rekening==$attributes->GetNoRekening()){
				$result['status'] = true;
				$result['message'] = 'Tidak ada perubahan';
			}else{
				$attributes->SetNama($bank_nama);
				$attributes->SetPemilik($bank_pemilik);
				$attributes->SetNoRekening($bank_no_rekening);
				$result = $attributes->UpdateBank();
			}
		}else{
			$result['status'] = false;
			$result['message'] = "Masih ada kolom yang belum diisi.";
		}
	}
?>
<div class="wrap">
<?php if($isPosted && $result['status'] ) { ?>
	<div class="updated">
		<p><?php echo $result['message']; ?></p>
	</div>
<?php } else { ?>
	<?php if($isPosted && !$result['status'] ): ?>
	<div class="updated">
		<p><?php echo $result['message']; ?></p>
	</div>
	<?php endif; ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama Bank<strong>*</strong><br />
			<input type="text" name="bank-nama" value="<?php if(! $isPosted) echo $attributes->GetNama(); else echo $bank_nama; ?>" />
		</p>
		<p>Atas Nama<strong>*</strong><br />
			<input type="text" name="bank-pemilik-rekening" value="<?php if(! $isPosted) echo $attributes->GetPemilik(); else echo $bank_pemilik; ?>" />
		</p>
		<p>No. Rekening<strong>*</strong><br />
			<input type="text" name="bank-no-rekening" value="<?php if(! $isPosted) echo $attributes->GetNoRekening(); else echo $bank_no_rekening; ?>" />
		</p>
		<p>
			<input type="submit" name="bank-update-submit" value="Simpan" />
		</p>
	</form>
<?php } ?>
	<a href="<?php echo admin_url('admin.php?page=onex-bank-page'); ?>">kembali ke Daftar Bank</a>
</div>