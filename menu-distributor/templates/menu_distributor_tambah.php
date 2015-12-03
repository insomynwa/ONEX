<?php
	wp_enqueue_script('jquery');

	wp_enqueue_media();


	if( isset($_POST['menudist-tambah-submit']) ){

		$menudist_nama = sanitize_text_field($_POST['menudist-nama']);
		$menudist_alamat = sanitize_text_field($_POST['menudist-alamat']);
		$menudist_telp = sanitize_text_field($_POST['menudist-telp']);
		$menudist_email = sanitize_text_field($_POST['menudist-email']);
		$menudist_keterangan = sanitize_text_field($_POST['menudist-keterangan']);
		$menudist_jenis_delivery = sanitize_text_field($_POST['menudist-jenis-delivery']);
		$menudist_gambar = sanitize_text_field($_POST['menudist-gambar-url']);

		if( $menudist_nama != "" && $menudist_alamat != "" && !is_null($menudist_jenis_delivery) ){
			$data = array(
					'menudist_nama' => $menudist_nama,
					'menudist_alamat' => $menudist_alamat,
					'menudist_telp' => $menudist_telp,
					'menudist_email' => $menudist_email,
					'menudist_keterangan' => $menudist_keterangan,
					'menudist_jenis_delivery' => $menudist_jenis_delivery,
					'menudist_gambar' => $menudist_gambar
				);
			if( $menudist_gambar!='' ) $data['menudist_gambar'] = $menudist_gambar;

			$onex_menu_distributor_obj = new Onex_Menu_Distributor();
			$result = $onex_menu_distributor_obj->AddDistributor($data);
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
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" >
		<p>Nama Menu <strong>*</strong><br />
			<input type="text" name="menudist-nama" value="<?php if(isset($result) && !$result['status'] ) echo $menudist_nama; ?>" />
		</p>
		<p>Keterangan<br />
			<textarea name="menudist-keterangan"></textarea>
		</p>
		<p>Harga <strong>*</strong><br />
			<input type="text" name="menudist-harga" value="<?php if(isset($result) && !$result['status'] ) echo $menudist_harga; ?>" />
		</p>
		<p>Gambar<br />
			<input type="text" name="menudist-gambar-url" id="image_url" class="regular-text">
    		<input type="button" name="menudist-gambar-button" id="upload-btn" class="button-secondary" value="Upload Image">
		</p>
		<p>Distributor<br />
		<?php
			$content = get_distributor();
			// var_dump($content['kat_del'][1]->id_kat_del);
			if( ! is_null($content) ):
		?>
			<select name="menudist-jenis-delivery">
				<?php foreach ($content['distributor'] as $distributor ): ?>
				<option value="<?php echo $distributor->id_dist; ?>" <?php if(isset($result) && !$result['status'] && $menudist_jenis_delivery==$distributor->id_dist ) echo "selected='selected'"; ?> ><?php echo $distributor->nama; ?></option>
				<?php endforeach; ?>
			</select>
		<?php else: ?>
			<strong>Belum ada Distributor. <a href="">Buat sekarang</a>.</strong>
		<?php endif; ?>
		</p>
		<p>Kategori<br />
		<?php
			$content = get_kategori_menu();
			// var_dump($content['kat_del'][1]->id_kat_del);
			if( ! is_null($content) ):
		?>
			<select name="menudist-jenis-delivery">
				<?php foreach ( $content['katmenu'] as $katmenu ): ?>
				<option value="<?php echo $katmenu->id_katmenu; ?>" <?php if(isset($result) && !$result['status'] && $menudist_jenis_delivery==$katmenu->id_katmenu ) echo "selected='selected'"; ?> ><?php echo $katmenu->nama_katmenu; ?></option>
				<?php endforeach; ?>
			</select>
		<?php else: ?>
			<strong>Belum ada jenis delivery. <a href="">Buat sekarang</a>.</strong>
		<?php endif; ?>
		</p>
		<p>
			<input type="submit" name="menudist-tambah-submit" value="Tambah" />
		</p>
	</form>
	<a href="<?php echo admin_url('admin.php?page=onex-menu-distributor-page'); ?>">kembali ke Daftar Menu</a>
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