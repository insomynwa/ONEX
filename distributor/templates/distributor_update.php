<?php
	wp_enqueue_script('jquery');

	wp_enqueue_media();
	//var_dump($attributes);
	$dist_id = $_GET['id'];
	$isPosted = isset($_POST['distributor-update-submit']);
	$distributor = $attributes['distributor'];
	//var_dump($distributor);
	if( $isPosted ){
		$dist_id = sanitize_text_field($_POST['distributor-id']);
		$dist_nama = sanitize_text_field($_POST['distributor-nama']);
		$dist_alamat = sanitize_text_field($_POST['distributor-alamat']);
		$dist_telp = sanitize_text_field($_POST['distributor-telp']);
		$dist_email = sanitize_text_field($_POST['distributor-email']);
		$dist_keterangan = sanitize_text_field($_POST['distributor-keterangan']);
		$dist_jenis_delivery = sanitize_text_field($_POST['distributor-jenis-delivery']);
		$dist_gambar = sanitize_text_field($_POST['distributor-gambar-url']);
		$dist_kode = sanitize_text_field($_POST['distributor-kode']);

		if( $dist_nama != "" && $dist_alamat != "" && !is_null($dist_jenis_delivery) && $dist_kode!="" ){
			/*$data = array(
					'dist_nama' => $dist_nama,
					'dist_alamat' => $dist_alamat,
					'dist_telp' => $dist_telp,
					'dist_email' => $dist_email,
					'dist_keterangan' => $dist_keterangan,
					'dist_jenis_delivery' => $dist_jenis_delivery,
					'dist_gambar' => $dist_gambar,
					'dist_kode' => $dist_kode
				);
			if( $dist_gambar!='' ) $data['dist_gambar'] = $dist_gambar;*/
			$hasNewAlamat = false;

			$distributor = new Onex_Distributor();
			$distributor->SetADistributor( $dist_id);
			$distributor->SetNama($dist_nama);

			if( $dist_alamat != $distributor->GetAlamat())
				$hasNewAlamat = true;
			
			$distributor->SetAlamat($dist_alamat);
			$distributor->SetTelp($dist_telp);
			$distributor->SetKeterangan($dist_keterangan);
			$distributor->SetKatdel($dist_jenis_delivery);
			if( $dist_gambar!='') $distributor->SetGambar($dist_gambar);
			$distributor->SetKode($dist_kode);

			//var_dump($hasNewAlamat, $dist_alamat, $distributor->GetAlamat());
			$result = $distributor->UpdateDistributor();
			if($result['status'] && $hasNewAlamat){
				$ongkir = new Onex_Ongkos_Kirim();
				$ongkir->SetATarifKirim();
				$data_pembeli = new Onex_Data_Pembeli();
				$users = $data_pembeli->GetAll_User_DataPembeli();
				foreach( $users as $u){
					$user_id = $u->user_id;
					$data_pembeli->SetDataPembeliUser( $user_id );
					$alamat_customer = $data_pembeli->GetAlamatDetail();
					$alamat_distributor = $distributor->GetAlamat();

					$jarak = $ongkir->GetJarakKm($alamat_distributor, $alamat_customer);
					$biaya = $ongkir->CountBiayaKirim($jarak);

					$invoice = new Onex_Invoice();
					$invoice->SetAnActiveInvoice_UserDistributor( $user_id, $dist_id);
					$invoice->SetJarakKirim($jarak);
					$invoice->SetBiayaKirim($biaya);

					if( $invoice->UpdateJarakAndBiayaKirim()){
						$result['status'] = true;
						$result['message'] = 'Berhasil';
					}
				}
			}


			/*$onex_distributor_obj = new Onex_Distributor();
			$result = $onex_distributor_obj->UpdateDistributor($dist_id, $data);*/
		}else{
			$result['status'] = false;
			$result['message'] = 'Masih ada kolom yang belum diisikan.';
		}
	}
?>
<div class="wrap">
	<h2>Update Distributor</h2>
	<?php if(isset($result)): ?>
	<div class="updated">
		<p><?php echo $result['message']; ?></p>
	</div>
	<?php else: ?>
	<div>
		<img src="<?php if(!$isPosted) echo $distributor->GetGambar(); ?>" />
	</div>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama <strong>*</strong><br />
			<input type="text" name="distributor-nama" value="<?php if( !$isPosted) echo $distributor->GetNama(); else echo $dist_nama; ?>" />
		</p>
		<p>Gambar<br />
			<input type="text" name="distributor-gambar-url" id="image_url" class="regular-text" value="<?php if(!$isPosted) echo $distributor->GetGambar(); else echo $dist_gambar; ?>" >
    		<input type="button" name="distributor-gambar-button" id="upload-btn" class="button-secondary" value="Upload Image">
		</p>
		<p>Jenis Delivery<br />
		<?php if( sizeof($attributes['katdel']) > 0 ): ?>
			<select name="distributor-jenis-delivery">
				<?php for( $i=0; $i < sizeof($attributes['katdel']); $i++ ): ?>
				<?php $katdel = $attributes['katdel'][$i]; $katdel_id = $katdel->GetId(); ?>
				<option value="<?php echo $katdel_id; ?>" <?php if( (!$isPosted && $distributor->GetKatdel() == $katdel_id) || ($isPosted && $distributor->GetKatdel()==$katdel_id)) echo 'selected'; ?> ><?php echo $katdel->GetNama(); ?></option>
				<?php endfor; ?>
			</select>
		<?php else: ?>
			<strong>Belum ada jenis delivery. <a href="<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>">Buat sekarang</a>.</strong>
		<?php endif; ?>
		</p>
		<p>Alamat <strong>*</strong><br />
			<textarea name="distributor-alamat"><?php if(!$isPosted) echo $distributor->GetALamat(); else echo $dist_alamat; ?></textarea>
		</p>
		<p>No. Telp<br />
			<input type="number" name="distributor-telp" value="<?php if(!$isPosted) echo $distributor->GetTelp(); else echo $dist_telp; ?>"/>
		</p>
		<p>Email<br />
			<input type="text" name="distributor-email" value="<?php if(!$isPosted) echo $distributor->GetEmail(); else echo $dist_email; ?>" />
		</p>
		<p>Keterangan<br />
			<textarea name="distributor-keterangan"><?php if(!$isPosted) echo $distributor->GetKeterangan(); else echo $dist_keterangan; ?></textarea>
		</p>
		<p>Kode Invoice <strong>*</strong><br />
			<input type="text" name="distributor-kode" value="<?php if(!$isPosted) echo $distributor->GetKode(); else echo $dist_kode; ?>"/>
			<input type="hidden" name="distributor-id" value="<?php echo $distributor->GetId(); ?>" />
		</p>
		<p>
			<input type="submit" name="distributor-update-submit" value="Simpan" />
		</p>
	</form>
	<?php endif; ?>
	<a href="<?php echo admin_url('admin.php?page=onex-distributor-page'); ?>">kembali ke Daftar Distributor</a>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            $('#image_url').val(image_url);
        });
    });
});
</script>