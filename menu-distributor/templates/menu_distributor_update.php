<?php
	wp_enqueue_script('jquery');

	wp_enqueue_media();

	$menudist_id = $_GET['menu'];
	$menudist = $attributes['menudel'];

	$isPosted = isset($_POST['menudist-update-submit']);

	if( $isPosted ){

		$menudist_distributor = sanitize_text_field($_POST['menudist-distributor']);
		$menudist_kategori = sanitize_text_field($_POST['menudist-kategori']);
		$menudist_nama = sanitize_text_field($_POST['menudist-nama']);
		$menudist_harga = sanitize_text_field($_POST['menudist-harga']);
		$menudist_keterangan = sanitize_text_field($_POST['menudist-keterangan']);
		$menudist_gambar = sanitize_text_field($_POST['menudist-gambar-url']);

		if( $menudist_nama != "" && $menudist_harga != "" && is_numeric($menudist_harga) && ($menudist_distributor > 0) && ($menudist_kategori>0) && $menudist_gambar != "" ){
			
			$data = array(
					'menudist_nama' => $menudist_nama,
					'menudist_harga' => $menudist_harga,
					'menudist_keterangan' => $menudist_keterangan,
					'menudist_distributor' => $menudist_distributor,
					'menudist_kategori' => $menudist_kategori,
					'menudist_gambar' => $menudist_gambar
				);

			if( $menudist_harga != $attributes['menudel']->GetHarga() ){
				$invoice_obj = new Onex_Invoice();
				$all_invoice = $invoice_obj->GetAllActiveInvoice();
				if( ! is_null($all_invoice) ){ 
					foreach( $all_invoice as $i) {
						$invoice_id = $i->id_invoice;
						$pemesanan = new Onex_Pemesanan_Menu();
						$pemesanan->SetPesananMenu_InvoiceMenuDistributor( $invoice_id, $menudist_id);
						$pemesanan->SetHargaSatuan( $menudist_harga);
						$nilai_pesanan = $pemesanan->GetJumlahPesanan() * $menudist_harga;
						$pemesanan->SetNilaiPesanan( $nilai_pesanan);
						$result['status'] = $pemesanan->UpdateHargaAndNilai();
						$result['message'] = "gagal update pesanan menu";
						//var_dump($invoice_id, $pemesanan->GetId(), $menudist_id, $pemesanan->GetJumlahPesanan(), $menudist_harga, $nilai_pesanan);
					}
				}
			}
			//var_dump($result);die;
			if((isset($result) && $result['status']) || !isset($result) ){
				$menudist->SetNama($menudist_nama);
				$menudist->SetHarga($menudist_harga);
				$menudist->SetGambar($menudist_gambar);
				$menudist->SetKeterangan($menudist_keterangan);
				$menudist->SetDistributor($menudist_distributor);
				$menudist->SetKatmenu($menudist_kategori);
				$result = $menudist->UpdateMenuDistributor();	
			}

			//$onex_menu_distributor_obj = new Onex_Menu_Distributor();
			//$result = $onex_menu_distributor_obj->UpdateMenuDistributor( $menudist_id, $data);
		}else{
			$result['status'] = false;
			$result['message'] = 'Masih ada kolom yang belum diisikan.';
		}
	}
?>
<div class="wrap">
	<h2>Update Menu</h2>
<?php if($isPosted): ?>
	<?php if($result['status']): ?>
	<div class="updated">
		<p><?php echo $result['message']; ?></p>
	</div>
	<?php endif; ?>
<?php endif; ?>
	<?php if(!$isPosted || ($isPosted && !$result['status'])): ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data" >
		<p>Distributor<br />
		<?php if( sizeof( $attributes['all-distributor'] )>0 ): ?>
			<select name="menudist-distributor">
				<?php for ( $i=0; $i<sizeof($attributes['all-distributor']); $i++): ?>
				<?php $distributor = $attributes['all-distributor'][$i]; ?>
				<option value="<?php echo $distributor->GetId(); ?>" <?php if( $distributor->GetId()==$attributes['menu-distributor']->GetId() ) echo "selected='selected'"; ?> ><?php echo $distributor->GetNama(); ?></option>
				<?php endfor; ?>
			</select>
			<?php else: ?>
				<strong>Belum ada data distributor.</strong>
		<?php endif; ?>
		</p>
		<p>Kategori<br />
				<select name="menudist-kategori">
				</select>
		</p>
		<p>Nama Menu <strong>*</strong><br />
			<input required type="text" name="menudist-nama" value="<?php if(! $isPosted ) echo $attributes['menudel']->GetNama(); else echo $menudist_nama; ?>" />
		</p>
		<p>Keterangan<br />
			<textarea name="menudist-keterangan"><?php if(! $isPosted) echo $attributes['menudel']->GetKeterangan(); else echo $menudist_keterangan; ?></textarea>
		</p>
		<p>Harga <strong>*</strong><br />
			<input required type="number" name="menudist-harga" value="<?php if(! $isPosted ) echo $attributes['menudel']->GetHarga(); else echo $menudist_harga; ?>" />
		</p>
		<p>Gambar <strong>*</strong><br />
			<input required type="text" name="menudist-gambar-url" id="image_url" class="regular-text" value="<?php if(! $isPosted) echo $attributes['menudel']->GetGambar(); else echo $menudist_gambar; ?>">
    		<input type="button" name="menudist-gambar-button" id="upload-btn" class="button-secondary" value="Upload Image">
		</p>
		<p>
			<input type="submit" name="menudist-update-submit" value="Update" />
		</p>
	</form>
	<?php endif; ?>
	<a href="<?php echo admin_url('admin.php?page=onex-distributor-page'); ?>">kembali ke Daftar Distributor</a>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){

	var default_dist = $("select[name='menudist-distributor']").val();
	
	loadKategori(default_dist);

	$("select[name='menudist-distributor']").on("change", function(){
		loadKategori(this.value);
		
	});

	function loadKategori(distributor) {
		$.getJSON(ajax_one_express.ajaxurl, { action:'AjaxRetrieveKategoriOnDistributor', distributor:distributor }, function (response) {
			$("select[name='menudist-kategori']").empty();
			var katmenu_old = <?php if(!$isPosted) echo $attributes['menu-katmenu']->GetId(); else echo $menudist_kategori; ?>;
			for(var i=0; i< response['nama'].length; i++) {
				$("select[name='menudist-kategori']")
					.append( $("<option></option>").attr("value", response['id'][i]).text(response['nama'][i]));
				if(response['id'][i] == katmenu_old){
					$("select[name='menudist-kategori'] option").prop("selected", true);
				}
			}
		});
	}

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