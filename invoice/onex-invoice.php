<?php
class Onex_Invoice{

	private $table_name;

	public function __construct(){
		$this->table_name = "onex_invoice";
	}

	public function CreateInvoice($user_id, $distributor_id){
		global $wpdb;

		$no_invoice = $this->generateNomorInvoice();
		if($wpdb->insert(
				$this->table_name,
				array(
					'no_invoice' => $no_invoice,
					'user_id' => $user_id,
					'distributor_id' => $distributor_id
					),
				array( '%s', '%s')
			)){
			return $wpdb->insert_id;
		}
		return 0;
	}

	public function UpdateSubtotalInvoice( $invoice_id, $nilai_menu){
		global $wpdb;

		$subtotal_n_ppn = $nilai_menu + ( $nilai_menu * 0.05) ;

		$wpdb->query(
			$wpdb->prepare(
					"UPDATE $this->table_name 
					SET subtotal_invoice = (subtotal_invoice + %d ) 
					WHERE id_invoice = %d",
					$subtotal_n_ppn,
					$invoice_id
				)
			);
		//var_dump($nilai_menu);
	}

	public function GetInvoiceAktifByUser( $customer_id ){
		global $wpdb;

		$menudel_table = "onex_menu_delivery";
		$distributor_table = "onex_distributor";

		$attributes =
			$wpdb->get_results(
					$wpdb->prepare(
						"SELECT i.*, d.* FROM $this->table_name i
						LEFT JOIN $distributor_table d
						ON i.distributor_id = d.id_dist
						WHERE i.user_id = %d AND i.status_aktif_invoice = %d AND i.status_user_confirm = %d",
						$customer_id, 1, 0
						)
				);

		return $attributes;
	}

	public function DeactivateInvoice(){
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE "
				)
			);
	} 

	public function GetIdInvoiceByDistributor( $user_id, $distributor_id){
		global $wpdb;

		$row =
		$wpdb->get_row(
				$wpdb->prepare(
						"SELECT i.id_invoice FROM $this->table_name i
						WHERE i.user_id = %d AND i.status_aktif_invoice =1 AND 
						i.distributor_id = %d",
						$user_id,
						$distributor_id
					),
				ARRAY_A
			);
		return ($row['id_invoice'] > 0? $row['id_invoice'] : 0);
	}

	private function generateNomorInvoice(){
		date_default_timezone_set('Asia/Jakarta');
		$tahun = date('Y');
		$tahun %= 2000;
		$bulan = date('n');
		$tgl = date('j');
		$jam = date('G');
		$minutes = intval(date('i'));
		$seconds = intval(date('s'));
		$nomor = $tahun+$bulan+$tgl+$jam+$minutes+$seconds;

		//var_dump($tahun,$bulan,$tgl, $jam);

		//$kode_katdel = $this->GetKodeJenisDelivery($menudel_id);

		$kode = "$nomor";//"$kode_katdel$user_id$nomor";
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