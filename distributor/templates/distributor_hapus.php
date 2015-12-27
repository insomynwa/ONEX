<?php
	$dist_id = $attributes->GetId();
	$dist_nama = "";

	if(isset($_POST['dist_hapus_submit'])){
		//$distributor = $attributes;//new Onex_Distributor();
		$katmenu = new Onex_Kategori_Menu();
		$menudel = new Onex_Menu_Distributor();
		$invoice = new Onex_Invoice();

		$katmenu->DeleteKategori_Distributor( $dist_id);
		$menudel->DeleteMenu_Distributor( $dist_id);
		$deleted_invoice = $invoice->GetAll_Id_ActiveInvoice_Distributor( $dist_id);
		if( !is_null($deleted_invoice) && !empty($deleted_invoice)){
			$pemesanan = new Onex_Pemesanan_Menu();
			foreach( $deleted_invoice as $delin){
				$delin_id = $delin->id_invoice;
				$pemesanan->DeletePesananMenu_Invoice( $delin_id);
			}
			$invoice->DeleteInvoice_Distributor( $dist_id);
		}
		//$distributor->SetADistributor( $dist_id);
		$result = $attributes->DeleteDistributor();
		$message = $result['message'];

	}
	
?>
	<div class="wrap">
		<h2>Hapus Distributor</h2>
	<?php if($_POST['dist_hapus_submit']) { ?>
		<div class="updated"><?php echo $message; ?></div>
	<?php } else { ?>
		<p>Apa anda yakin akan menghapus <strong><?php echo $attributes->GetNama(); ?></strong>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p>
				<input type="submit" name="dist_hapus_submit" value="Ya" /><br />
				<span>(PERINGATAN) proses ini akan menghapus semua kategori, menu, dan pemesanan yang berkaitan dengan distributor ini!</span>
			</p>
		</form>
	<?php } ?>
		<a href="<?php echo admin_url('admin.php?page=onex-distributor-page') ?>">kembali ke Daftar Distributor</a>
	</div>