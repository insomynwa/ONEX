<?php
	wp_enqueue_script('jquery');

	wp_enqueue_media();

	$menudist_id = $_GET['menu'];

	if( isset($_POST['menudist-update-submit']) ){

		$menudist_distributor = sanitize_text_field($_POST['menudist-distributor']);
		$menudist_kategori = sanitize_text_field($_POST['menudist-kategori']);
		$menudist_nama = sanitize_text_field($_POST['menudist-nama']);
		$menudist_harga = sanitize_text_field($_POST['menudist-harga']);
		$menudist_keterangan = sanitize_text_field($_POST['menudist-keterangan']);
		$menudist_gambar = sanitize_text_field($_POST['menudist-gambar-url']);

		if( $menudist_nama != "" && $menudist_harga != "" && ($menudist_distributor > 0) && ($menudist_kategori>0) && $menudist_gambar != "" ){
			$data = array(
					'menudist_nama' => $menudist_nama,
					'menudist_harga' => $menudist_harga,
					'menudist_keterangan' => $menudist_keterangan,
					'menudist_distributor' => $menudist_distributor,
					'menudist_kategori' => $menudist_kategori,
					'menudist_gambar' => $menudist_gambar
				);

			$onex_menu_distributor_obj = new Onex_Menu_Distributor();
			$result = $onex_menu_distributor_obj->UpdateMenuDistributor( $menudist_id, $data);
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
		<p>Distributor<br />
		<?php if( isset( $attributes['distributor'] ) ): ?>
			<?php echo $attributes['distributor']['nama_dist']; ?>
			<input type="hidden" name="menudist-distributor" value="<?php echo $attributes['distributor']['id_dist']; ?>" />
		<?php endif; ?>
		</p>
		<p>Kategori<br />
		<?php if( isset( $attributes['katmenu'] ) ): ?>
			<?php echo $attributes['katmenu']['nama_katmenu']; ?>
			<input type="hidden" name="menudist-kategori" value="<?php echo $attributes['katmenu']['id_katmenu']; ?>" />
		<?php endif; ?>
		</p>
		<p>Nama Menu <strong>*</strong><br />
			<input type="text" name="menudist-nama" value="<?php if(isset($_POST['menudist-update-submit']) ) echo $menudist_nama; else echo $attributes['menudel']['nama_menudel']; ?>" />
		</p>
		<p>Keterangan<br />
			<textarea name="menudist-keterangan"><?php if(isset($_POST['menudist-update-submit']) ) echo $menudist_keterangan; else echo $attributes['menudel']['keterangan_menudel']; ?></textarea>
		</p>
		<p>Harga <strong>*</strong><br />
			<input type="text" name="menudist-harga" value="<?php if(isset($_POST['menudist-update-submit']) ) echo $menudist_harga; else echo $attributes['menudel']['harga_menudel']; ?>" />
		</p>
		<p>Gambar <strong>*</strong><br />
			<input type="text" name="menudist-gambar-url" id="image_url" class="regular-text" value="<?php if(isset($_POST['menudist-update-submit']) ) echo $menudist_gambar; else echo $attributes['menudel']['gambar_menudel']; ?>">
    		<input type="button" name="menudist-gambar-button" id="upload-btn" class="button-secondary" value="Upload Image">
		</p>
		<p>
			<input type="submit" name="menudist-update-submit" value="Tambah" />
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