<?php
	wp_enqueue_script('jquery');

	wp_enqueue_media();

	$isPosted = isset($_POST['menudist-tambah-submit']);
	if( $isPosted ){

		$menudist_distributor = sanitize_text_field($_POST['menudist-distributor']);
		$menudist_kategori = sanitize_text_field($_POST['menudist-kategori']);
		$menudist_nama = sanitize_text_field($_POST['menudist-nama']);
		$menudist_harga = sanitize_text_field($_POST['menudist-harga']);
		$menudist_keterangan = sanitize_text_field($_POST['menudist-keterangan']);
		$menudist_gambar = sanitize_text_field($_POST['menudist-gambar-url']);
		//echo $menudist_kategori.' '.$menudist_nama;

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
			$result = $onex_menu_distributor_obj->AddMenuDistributor($data);
		}else{
			$result['status'] = false;
			$result['message'] = 'Masih ada kolom yang belum diisikan.';
		}
	}
?>
<div class="wrap">
	<h2>Tambah Menu</h2>
	<?php if(isset($result)): ?>
	<div class="updated">
		<p><?php echo $result['message']; ?></p>
	</div>
	<?php endif; ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" >
		<p>Distributor<br />
			<?php if( ! $attributes['single']): ?>
				<?php if( !is_null($attributes['distributor']) && sizeof($attributes['distributor']) > 0): ?>
				<select name="menudist-distributor">
					<?php for ( $i=0; $i<sizeof($attributes['distributor']); $i++): ?>
					<?php $distributor = $attributes['distributor'][$i]; ?>
					<option value="<?php echo $distributor->GetId(); ?>"><?php echo $distributor->GetNama(); ?></option>
					<?php endfor; ?>
				</select>
				<?php else: ?>
					<strong>Belum ada data distributor.</strong>
				<?php endif; ?>
			<?php else: ?>
				<?php echo $attributes['distributor']->GetNama(); ?>
				<input type="hidden" name="menudist-distributor" value="<?php echo $attributes['distributor']->GetId(); ?>" />
			<?php endif; ?>
		</p>
		<p>Kategori<br />
			<?php if( ! $attributes['single']): ?>
				<?php if( !is_null($attributes['katmenu']) && sizeof($attributes['katmenu']) > 0): ?>
				<select name="menudist-kategori">
					<?php for ( $i=0; $i<sizeof($attributes['katmenu']); $i++): ?>
					<?php $katmenu = $attributes['katmenu'][$i]; ?>
					<option value="<?php echo $katmenu->GetId(); ?>"><?php echo $katmenu->GetNama(); ?></option>
					<?php endfor; ?>
				</select>
				<?php else: ?>
					<strong>Belum ada data kategori.</strong>
				<?php endif; ?>
			<?php else: ?>
				<?php echo $attributes['katmenu']->GetNama(); ?>
				<input type="hidden" name="menudist-kategori" value="<?php echo $attributes['katmenu']->GetId(); ?>" />
			<?php endif; ?>
		</p>
		<p>Nama Menu <strong>*</strong><br />
			<input required type="text" name="menudist-nama" value="<?php if(isset($result) && !$result['status'] ) echo $menudist_nama; ?>" />
		</p>
		<p>Keterangan<br />
			<textarea name="menudist-keterangan"></textarea>
		</p>
		<p>Harga <strong>*</strong><br />
			<input required type="number" name="menudist-harga" value="<?php if(isset($result) && !$result['status'] ) echo $menudist_harga; ?>" />
		</p>
		<p>Gambar <strong>*</strong><br />
			<input required type="text" name="menudist-gambar-url" id="image_url" class="regular-text" value="<?php if($isPosted && !$result['status']) echo $menudist_gambar; ?>">
    		<input type="button" name="menudist-gambar-button" id="upload-btn" class="button-secondary" value="Upload Image">
		</p>
		<p>
			<input type="submit" name="menudist-tambah-submit" value="Tambah" />
		</p>
	</form>
	<a href="<?php echo admin_url('admin.php?page=onex-distributor-page'); ?>">kembali ke Daftar Distributor</a>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){

	<?php if( ! $attributes['single']): ?>
	
	var default_dist = $("select[name='menudist-distributor']").val();
	
	loadKategori(default_dist);

	$("select[name='menudist-distributor']").on("change", function(){
		loadKategori(this.value);
		
	});

	function loadKategori(distributor) {
		$.getJSON(ajax_one_express.ajaxurl, { action:'AjaxRetrieveKategoriOnDistributor', distributor:distributor }, function (response) {
			$("select[name='menudist-kategori']").empty();
			for(var i=0; i< response['nama'].length; i++) {
				$("select[name='menudist-kategori']")
					.append( $("<option></option>").attr("value", response['id'][i]).text(response['nama'][i]));
			}
		});
	}

	<?php endif; ?>

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