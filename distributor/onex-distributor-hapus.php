<?php

function onex_distributor_hapus(){
	$dist_id = $_GET['id'];
	$dist_nama = "";

	global $wpdb;
	$tbl_onex_distributor = "onex_distributor";

	if(isset($_POST['distributor-hapus-yes'])){
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $tbl_onex_distributor WHERE id_dist = %d",
				$dist_id
			)
		);

	}else{
		$rows = 
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT nama FROM $tbl_onex_distributor WHERE id_dist = %d",
					$dist_id
				)
			);

		foreach($rows as $row){
			$dist_nama .= $row->nama;
		}
	}
	
?>
	<div class="wrap">
		<h2>Hapus Distributor</h2>
	<?php if($_POST['distributor-hapus-yes']) { ?>
		<div class="updated"><p>Distributor berhasil dihapus</p></div>
	<?php } else { ?>
		<p>Apa anda yakin akan menghapus <?php echo $dist_nama; ?>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p>
				<input type="submit" name="distributor-hapus-yes" value="Ya" />
			</p>
		</form>
	<?php } ?>
		<a href="<?php echo admin_url('admin.php?page=onex-distributor-page') ?>">kembali ke Daftar Distributor</a>
	</div>
<?php
}
?>