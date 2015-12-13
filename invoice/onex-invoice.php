<?php
class Onex_Invoice{

	private $table_name;

	public function __construct(){
		$this->table_name = "onex_invoice";
	}

	public function CreateInvoice($user_id, $menudel_id){
		global $wpdb;

		$no_invoice = $this->generateNomorInvoice($menudel_id, $user_id);
		if($wpdb->insert(
				$this->table_name,
				array(
					'no_invoice' => $no_invoice,
					'user_id' => $user_id
					),
				array( '%s', '%s')
			)){
			return $wpdb->insert_id;
		}
		return 0;
	}

	public function UpdateSubtotalInvoice( $invoice_id, $nilai_menu){
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
					"UPDATE $this->table_name 
					SET nilai_invoice = (nilai_invoice + %d ) 
					WHERE id_invoice = %d",
					$nilai_menu,
					$invoice_id
				)
			);
		//var_dump($nilai_menu);
	}

	public function GetIdInvoice( $user_id, $menudel_id){
		global $wpdb;

		$menudel_table = "onex_menu_delivery";
		$distributor_table = "onex_distributor";

		$row =
		$wpdb->get_row(
				$wpdb->prepare(
						"SELECT i.id_invoice FROM $this->table_name i
						WHERE i.user_id = %d AND i.status_aktif_invoice =1 AND 
						MID(i.no_invoice, 1, 2) =
						(
							SELECT d.kode_dist FROM $menudel_table md
							LEFT JOIN $distributor_table d 
							ON md.distributor_id=d.id_dist
							WHERE md.id_menudel= %d
						)",
						$user_id,
						$menudel_id
					),
				ARRAY_A
			);
		return $row['id_invoice'];
	}

	private function generateNomorInvoice($menudel_id, $user_id){
		$tahun = date('Y');
		$tahun %= 2000;
		$bulan = date('n');
		$tgl = date('j');
		$jam = date('G');
		$nomor = $tahun+$bulan+$tgl+$jam;
		//var_dump($tahun,$bulan,$tgl, $jam);

		$kode_katdel = $this->GetKodeJenisDelivery($menudel_id);

		$kode = "$kode_katdel$user_id$nomor";
		return $kode;
	}

	private function GetKodeJenisDelivery($menudel_id){
		$menudel_table = "onex_menu_delivery";
		$distributor_table = "onex_distributor";

		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT d.kode_dist FROM $menudel_table md
						LEFT JOIN $distributor_table d
						ON md.distributor_id = d.id_dist
						WHERE md.id_menudel = %d",
					$menudel_id
				),
				ARRAY_A
			);
		$kode_katdel = $row['kode_dist'];
		//var_dump($row);
		return $kode_katdel;
	}
}

?>