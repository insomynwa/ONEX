<?php
class Onex_Invoice{

	private $table_name;

	public function __construct(){
		$this->table_name = "onex_invoice";
	}

	public function CreateInvoice( $user_id, $invoice_data){
		global $wpdb;

		$no_invoice = $this->generateNomorInvoice();
		if($wpdb->insert(
				$this->table_name,
				array(
					'no_invoice' => $no_invoice,
					'user_id' => $user_id,
					'distributor_id' => $invoice_data['distributor'],
					'jarak_kirim_invoice' => $invoice_data['jarak'],
					'biaya_kirim_invoice' => $invoice_data['ongkir']
					),
				array( '%s', '%d', '%d', '%d', '%d')
			)){
			return $wpdb->insert_id;
		}
		return 0;
	}

	/*public function UpdateTotalInvoice( $invoice_id, $nilai_menu_ppn, $ongkir){
		global $wpdb;

		//$total_n_ppn = $nilai_menu + ( $nilai_menu * 0.05) ; // harga total menu + ppn(5%)
		$total = $nilai_menu_ppn + $ongkir;

		$wpdb->query(
			$wpdb->prepare(
					"UPDATE $this->table_name 
					SET total_invoice = (total_invoice + %d ) 
					WHERE id_invoice = %d",
					$total,
					$invoice_id
				)
			);
		//var_dump($nilai_menu);
	}*/

	public function GetInvoiceDistributor( $invoice_id){
		global $wpdb;

		$distributor_table = "onex_distributor";

		$attributes =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT d.* FROM $this->table_name i
					LEFT JOIN $distributor_table d
					ON i.distributor_id=d.id_dist
					WHERE i.id_invoice = %d",
					$invoice_id
					),
				ARRAY_A
				);

		return $attributes;
	}

	public function UpdateJarakDanBiayaKirim( $invoice_id, $jarak, $ongkir){
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE $this->table_name 
				SET jarak_kirim_invoice = %d, biaya_kirim_invoice = %d
				WHERE id_invoice = %d",
				$jarak, $ongkir,
				$invoice_id
				)
			);
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

		$expiredInvoice = $this->getExpiredInvoice();

		if(!empty( $expiredInvoice) && !is_null( $expiredInvoice) ){

			foreach ($expiredInvoice as $invoice ){
				$wpdb->query(
					$wpdb->prepare(
						"UPDATE $this->table_name 
						SET status_aktif_invoice = 0
						WHERE id_invoice = %d",
						$invoice->id_invoice
						)
					);
			}
		}
	}

	function getExpiredInvoice(){
		global $wpdb;

		$attributes = null;

		$attributes =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT id_invoice FROM $this->table_name 
					WHERE status_aktif_invoice = 1 AND tgl_invoice < NOW() - INTERVAL 24 HOUR AND status_user_confirm = 0",
					null
					)
				);
		return $attributes;
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