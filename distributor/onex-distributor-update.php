<?php
function onex_distributor_update(){
	global $wpdb;
	$tbl_onex_kategori_delivery = "onex_kategori_delivery";
	$tbl_onex_distributor = "onex_distributor";

	$dist_id = $_GET['id'];
	$dist_nama = $_POST['distributor-nama'];
	$dist_alamat = $_POST['distributor-alamat'];
	$dist_telp = $_POST['distributor-telp'];
	$dist_email = $_POST['distributor-email'];
	$dist_keterangan = $_POST['distributor-keterangan'];
	$dist_jenis_delivery = $_POST['distributor-jenis-delivery'];

	if(isset($_POST['distributor-update-save'])){
		$wpdb->update(
			$tbl_onex_distributor,
			array(
				'nama' => $dist_nama,
				'alamat' => $dist_alamat,
				'kategori_delivery' => $dist_jenis_delivery,
				'telp' => $dist_telp,
				'email' => $dist_email,
				'keterangan' => $dist_keterangan
			),
			array('id_dist' => $dist_id),
			array('%s','%s','%d','%s','%s','%s'),
			array('%d')
		);

	} else{
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT d.*, kd.id_kat_del FROM $tbl_onex_distributor d
				 LEFT JOIN $tbl_onex_kategori_delivery kd
				 ON d.kategori_delivery=kd.id_kat_del
				 WHERE d.id_dist = %d
				",$dist_id
				)
		);
		foreach($rows as $row){
			$dist_nama = $row->nama;
			$dist_alamat = $row->alamat;
			$dist_telp = $row->telp;
			$dist_email = $row->email;
			$dist_keterangan = $row->keterangan;
			$dist_jenis_delivery = $row->id_kat_del;
		}
	}
?>
<div class="wrap">

<?php if($_POST['distributor-update-save']) { ?>
	<div class="updated">
		<p>Distributor telah berhasil di-update</p>
	</div>
<?php } else { ?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<p>Nama<br />
			<input type="text" name="distributor-nama" value="<?php echo $dist_nama; ?>" />
		</p>
		<p>Jenis Delivery<br />
<?php
if($wpdb->get_var("SELECT COUNT(*) FROM $tbl_onex_kategori_delivery") > 0){ ?>
			<select name="distributor-jenis-delivery">
	<?php
		$rows_kat_del = $wpdb->get_results("SELECT id_kat_del, kategori FROM $tbl_onex_kategori_delivery");
		foreach($rows_kat_del as $kat){
			if($kat->id_kat_del == $dist_jenis_delivery){
				echo "<option value='$kat->id_kat_del' selected='selected'> $kat->kategori </option>";
			}else{
				echo "<option value='$kat->id_kat_del'> $kat->kategori </option>";
			}
			
		}
	?>
			</select>
<?php }
?>
		</p>
		<p>Alamat<br />
			<textarea name="distributor-alamat"><?php echo $dist_alamat; ?></textarea>
		</p>
		<p>No. Telp<br />
			<input type="text" name="distributor-telp" value="<?php echo $dist_telp; ?>" />
		</p>
		<p>Email<br />
			<input type="text" name="distributor-email" value="<?php echo $dist_email; ?>" />
		</p>
		<p>Keterangan<br />
			<textarea name="distributor-keterangan"><?php echo $dist_keterangan; ?></textarea>
		</p>
		<p>
			<input type="submit" name="distributor-update-save" value="Update" />
		</p>
	</form>
<?php } ?>
	
	<a href="<?php echo admin_url('admin.php?page=onex-distributor-page'); ?>"> kembali ke Daftar Distributor </a>
</div>
<?php
}
?>