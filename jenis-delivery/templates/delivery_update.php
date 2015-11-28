<?php
	
	$katdel_id = $_GET['id'];
	$katdel_nama = sanitize_text_field($_POST['katdel-nama']);
	$katdel_keterangan = sanitize_text_field($_POST['katdel-keterangan']);
	$success = false;

	if(isset($_POST['katdel-update-submit'])){

		if($katdel_nama == $attributes['kat_del']['kategori'] && $katdel_keterangan == $attributes['kat_del']['keterangan'] && $katdel_id == $attributes['kat_del']['id_kat_del']){
			$success = true;
			$message = "Tidak ada pembaharuan yang dilakukan.";
		}else{
			if(!is_null($katdel_nama) && ! empty($katdel_nama) && $katdel_nama!="" ){
				$onex_jenis_delivery_obj = new Onex_Jenis_Delivery();
				if($katdel_keterangan=="") $katdel_keterangan = $katdel_nama;
				$data = array(
					'katdel_id' => $katdel_id,
					'katdel_nama' => $katdel_nama,
					'katdel_keterangan' => $katdel_keterangan
				);
				$message = $onex_jenis_delivery_obj->UpdateDelivery($data);
				$success = true;
			}else{
				$message = "Kolom Nama harus diisi";
			}
		}
	}
?>
<div class="wrap">

<?php if($_POST['katdel-update-submit'] && $success ) { ?>
	<div class="updated">
		<p><?php echo $message; ?></p>
	</div>
<?php } else { ?>
	<?php if($_POST['katdel-update-submit'] && !$success ): ?>
	<div class="updated">
		<p><?php echo $message; ?></p>
	</div>
	<?php endif; ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama<br />
			<input type="text" name="katdel-nama" value="<?php 
				if($_POST['katdel-update-submit'] && !$success )
					echo $katdel_nama;
				else
					echo $attributes['kat_del']['kategori']; 
			?>"
			/>
		</p>
		<p>Keterangan<br />
			<textarea name="katdel-keterangan"><?php
					if($_POST['katdel-update-submit'] && !$success )
						echo $katdel_keterangan;
					else
						echo $attributes['kat_del']['keterangan']; 
				?>
			</textarea>
		</p>
		<p>
			<input type="submit" name="katdel-update-submit" value="Update" />
		</p>
	</form>
<?php } ?>
	<a href="<?php echo admin_url('admin.php?page=onex-jenis-delivery-page'); ?>">kembali ke Daftar Jenis Delivery </a>
</div>