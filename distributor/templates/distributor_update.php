<?php
	wp_enqueue_script('jquery');

	wp_enqueue_media();
	//var_dump($attributes);
	$dist_id = $_GET['id'];
	if( isset($_POST['distributor-update-submit']) ){
		$dist_nama = sanitize_text_field($_POST['distributor-nama']);
		$dist_alamat = sanitize_text_field($_POST['distributor-alamat']);
		$dist_telp = sanitize_text_field($_POST['distributor-telp']);
		$dist_email = sanitize_text_field($_POST['distributor-email']);
		$dist_keterangan = sanitize_text_field($_POST['distributor-keterangan']);
		$dist_jenis_delivery = sanitize_text_field($_POST['distributor-jenis-delivery']);
		$dist_gambar = sanitize_text_field($_POST['distributor-gambar-url']);

		if( $dist_nama != "" && $dist_alamat != "" && !is_null($dist_jenis_delivery) ){
			$data = array(
					'dist_nama' => $dist_nama,
					'dist_alamat' => $dist_alamat,
					'dist_telp' => $dist_telp,
					'dist_email' => $dist_email,
					'dist_keterangan' => $dist_keterangan,
					'dist_jenis_delivery' => $dist_jenis_delivery,
					'dist_gambar' => $dist_gambar
				);
			if( $dist_gambar!='' ) $data['dist_gambar'] = $dist_gambar;

			$onex_distributor_obj = new Onex_Distributor();
			$result = $onex_distributor_obj->UpdateDistributor($dist_id, $data);
		}else{
			$result['status'] = false;
			$result['message'] = 'Masih ada kolom yang belum diisikan.';
		}
	}
?>
<div class="wrap">
	<?php if(isset($result)): ?>
	<div class="updated">
		<p><?php echo $result['message']; ?></p>
	</div>
	<?php endif; ?>
	<div>
		<img src="<?php if( $attributes['distributor']['gambar_dist'] == 'NOIMAGE') echo bloginfo('template_url').'/images/no-image.jpg'; ?>" />
	</div>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama <strong>*</strong><br />
			<input type="text" name="distributor-nama" value="<?php if(isset($result)) { if( !$result['status'] ) echo $dist_nama; }else{ echo $attributes['distributor']['nama_dist']; } ?>" />
		</p>
		<p>Gambar<br />
			<input type="text" name="distributor-gambar-url" id="image_url" class="regular-text" value="<?php if(isset($result)) { if( !$result['status'] ) echo $dist_gambar; }else{ echo $attributes['distributor']['gambar_dist']; } ?>" >
    		<input type="button" name="distributor-gambar-button" id="upload-btn" class="button-secondary" value="Upload Image">
		</p>
		<p>Jenis Delivery<br />
		<?php
			//$content = get_jenis_delivery();
			// var_dump($content['kat_del'][1]->id_kat_del);
			if( sizeof($attributes['katdel'])>0 ):
		?>
			<select name="distributor-jenis-delivery">
				<?php foreach ( $attributes['katdel'] as $katdel ): ?>
				<option value="<?php echo $katdel->id_katdel; ?>" <?php if( isset($result) ) { if( !$result['status'] && $dist_jenis_delivery==$katdel->id_katdel ) echo "selected='selected'"; }else{ if( $attributes['distributor']['katdel_id']==$katdel->id_katdel ) echo "selected='selected'";} ?> ><?php echo $katdel->nama_katdel; ?></option>
				<?php endforeach; ?>
			</select>
		<?php else: ?>
			<strong>Belum ada jenis delivery. <a href="">Buat sekarang</a>.</strong>
		<?php endif; ?>
		</p>
		<p>Alamat <strong>*</strong><br />
			<textarea name="distributor-alamat"><?php if( isset($result) ) { if( !$result['status'] ) echo $dist_alamat; }else{ echo $attributes['distributor']['alamat_dist']; } ?></textarea>
		</p>
		<p>No. Telp<br />
			<input type="text" name="distributor-telp" value="<?php if( isset($result) ) { if( !$result['status'] ) echo $dist_telp; }else{ echo $attributes['distributor']['telp_dist']; } ?>"/>
		</p>
		<p>Email<br />
			<input type="text" name="distributor-email" value="<?php if( isset($result) ) { if( !$result['status'] ) echo $dist_email; }else{ echo $attributes['distributor']['email_dist']; } ?>" />
		</p>
		<p>Keterangan<br />
			<textarea name="distributor-keterangan"><?php if( isset($result) ) { if( !$result['status'] ) echo $dist_keterangan; }else{ echo $attributes['distributor']['keterangan_dist']; } ?></textarea>
		</p>
		<p>
			<input type="submit" name="distributor-update-submit" value="Simpan" />
		</p>
	</form>
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