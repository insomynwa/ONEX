<?php

function onex_distributor_tambah(){

	$dist_nama = $_POST['distributor-nama'];
	$dist_alamat = $_POST['distributor-alamat'];
	$dist_telp = $_POST['distributor-telp'];
	$dist_email = $_POST['distributor-email'];
	$dist_keterangan = $_POST['distributor-keterangan'];
	$dist_jenis_delivery = $_POST['distributor-jenis-delivery'];

	global $wpdb;
	$tbl_onex_kategori_delivery = "onex_kategori_delivery";
	if(isset($_POST['distributor-tambah-save'])){
		$tbl_onex_distributor = "onex_distributor";
		$wpdb->insert(
			$tbl_onex_distributor,
			array(
				'nama' => $dist_nama,
				'alamat' => $dist_alamat,
				'kategori_delivery' => $dist_jenis_delivery,
				'telp' => $dist_telp,
				'email' => $dist_email,
				'keterangan' => $dist_keterangan
			),
			array('%s','%s','%d','%s','%s','%s')
		);
		$message .= "Berhasil menambah distributor baru.";
	}
?>
<div class="wrap">
	<?php if(isset($message)): ?>
	<div class="updated"><p><?php echo $message; ?></p></div>
	<?php endif; ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama<br />
			<input type="text" name="distributor-nama" />
		</p>
		<p>Jenis Delivery<br />
<?php
if($wpdb->get_var("SELECT COUNT(*) FROM $tbl_onex_kategori_delivery") > 0){ ?>
			<select name="distributor-jenis-delivery">
	<?php
		$rows_kat_del = $wpdb->get_results("SELECT id_kat_del, kategori FROM $tbl_onex_kategori_delivery");
		foreach($rows_kat_del as $kat){
			echo "<option value='$kat->id_kat_del'> $kat->kategori </option>";
		}
	?>
			</select>
<?php } ?>
		</p>
		<p>Alamat<br />
			<textarea name="distributor-alamat"></textarea>
		</p>
		<p>No. Telp<br />
			<input type="text" name="distributor-telp" />
		</p>
		<p>Email<br />
			<input type="text" name="distributor-email" />
		</p>
		<p>Keterangan<br />
			<textarea name="distributor-keterangan"></textarea>
		</p>
		<p>
			<input type="submit" name="distributor-tambah-save" value="Tambah" />
		</p>
	</form>
	<a href="<?php echo admin_url('admin.php?page=onex-distributor-page'); ?>">kembali ke Daftar Distributor</a>
</div>

<?php
}
?>