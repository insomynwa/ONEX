<?php
	$katdel_id = $attributes->GetId();
	$katdel_nama = "";

	if(isset($_POST['katdel-hapus-submit'])){
		$jenis_delivery = new Onex_Jenis_Delivery();
		$distributor = new Onex_Distributor();

		$deleted_distributor = $distributor->GetAll_Id_Distributor_JenisDelivery( $katdel_id);
		if(!is_null($deleted_distributor) && !empty($deleted_distributor)){
			$katmenu = new Onex_Kategori_Menu();
			$menudel = new Onex_Menu_Distributor();
			$invoice = new Onex_Invoice();
			foreach($deleted_distributor as $deldist){
				$deldist_id = $deldist->id_dist;
				$katmenu->DeleteKategori_Distributor( $deldist_id);
				$menudel->DeleteMenu_Distributor( $deldist_id);
				$deleted_invoice = $invoice->GetAll_Id_ActiveInvoice_Distributor( $deldist_id);
				if( !is_null($deleted_invoice) && !empty($deleted_invoice)){
					$pemesanan = new Onex_Pemesanan_Menu();
					foreach( $deleted_invoice as $delin){
						$delin_id = $delin->id_invoice;
						$pemesanan->DeletePesananMenu_Invoice( $delin_id);
					}
					$invoice->DeleteInvoice_Distributor( $deldist_id);
				}
			}
			$distributor->DeleteDistributor_JenisDelivery( $katdel_id);
		}
		$jenis_delivery->SetAJenisDelivery( $katdel_id);
		$result = $jenis_delivery->DeleteJenisDelivery();
		$message = $result['message'];

	}
	
?>
	<div class="wrap">
		<h2>Hapus Jenis Delivery</h2>
	<?php if($_POST['katdel-hapus-submit']) { ?>
		<div class="updated"><?php echo $message; ?></div>
	<?php } else { ?>
		<p>Apa anda yakin akan menghapus <strong><?php echo $attributes->GetNama(); ?></strong>?</p>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<p>
				<input type="submit" name="katdel-hapus-submit" value="Ya" /><br />
				<span>(PERINGATAN) proses ini akan menghapus semua distributor, kategori, menu, dan pemesanan yang berkaitan dengan jenis delivery ini!</span>
			</p>
		</form>
	<?php } ?>
		<a href="<?php echo admin_url('admin.php?page=onex-jenis-delivery-page') ?>">kembali ke Daftar Jenis Delivery</a>
	</div>