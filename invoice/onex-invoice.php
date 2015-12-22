<?php
class Onex_Invoice{

	private $table_name;

	private $id;
	public function GetId(){ return $this->id; }

	private $tgl;
	public function GetTgl(){ return $this->tgl; }

	private $nomor;
	public function GetNomor() { return $this->nomor; }
	public function SetNomor(){ 
		$this->nomor = $this->generateNomorInvoice(); 
	}

	private $status_active;
	public function GetStatusActive() { return $this->status_active; }
	public function SetStatusActive($status) { $this->status_active = $status; }

	private $user;
	public function GetUser() { return $this->user; }
	public function SetUser($user) { $this->user = $user; }

	private $distributor;
	public function GetDistributor() { return $this->distributor; }
	public function SetDistributor($distributor) { $this->distributor = $distributor; }

	private $status_confirm;
	public function GetStatusConfirm() { return $this->status_confirm; }
	public function SetStatusConfirm($status) { $this->status_confirm = $status; }

	private $jam_kirim;
	public function GetJamKirim() { return $this->jam_kirim; }
	public function SetJamKirim($jam_kirim) { $this->jam_kirim = $jam_kirim; }

	private $jarak_kirim;
	public function GetJarakKirim() { return $this->jarak_kirim; }
	public function SetJarakKirim($jarak_kirim) { $this->jarak_kirim = $jarak_kirim; }

	private $biaya_kirim;
	public function GetBiayaKirim() { return $this->biaya_kirim; }
	public function SetBiayaKirim($biaya_kirim) { $this->biaya_kirim = $biaya_kirim; }


	public function __construct(){
		$this->table_name = "onex_invoice";
	}

	public function DeleteInvoice(){
		global $wpdb;

		if(
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM $this->table_name WHERE id_invoice = %d",
					$this->id
					)
				)
			){
			return true;
		}
		return false;
	}

	public function SetAnInvoice_Id( $id){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name 
					WHERE id_invoice = %d",
					$id
					),
				ARRAY_A
				);

		$this->id = 0;
		if( !is_null($row) ){
			$this->id = $row['id_invoice'];
			$this->tgl = $row['tgl_invoice'];
			$this->nomor = $row['no_invoice'];
			$this->status_active = $row['status_aktif_invoice'];
			$this->user = $row['user_id'];
			$this->distributor = $row['distributor_id'];
			$this->status_confirm = $row['status_user_confirm'];
			$this->jam_kirim = $row['jam_kirim_invoice'];
			$this->jarak_kirim = $row['jarak_kirim_invoice'];
			$this->biaya_kirim = $row['biaya_kirim_invoice']; 
		}
	}

	public function SetAnActiveInvoice_UserDistributor( $user, $distributor){
		global $wpdb;
		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT i.* FROM $this->table_name i
					WHERE i.user_id = %d AND i.status_aktif_invoice = '%d' AND 
						i.distributor_id = %d",
						$user, 1,
						$distributor
					),
				ARRAY_A
				);

		$this->id = 0;
		if( !is_null($row) ){
			$this->id = $row['id_invoice'];
			$this->tgl = $row['tgl_invoice'];
			$this->nomor = $row['no_invoice'];
			$this->status_active = $row['status_aktif_invoice'];
			$this->user = $row['user_id'];
			$this->distributor = $row['distributor_id'];
			$this->status_confirm = $row['status_user_confirm'];
			$this->jam_kirim = $row['jam_kirim_invoice'];
			$this->jarak_kirim = $row['jarak_kirim_invoice'];
			$this->biaya_kirim = $row['biaya_kirim_invoice']; 
		}
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

	public function CreateNewInvoice(){
		global $wpdb;

		//$no_invoice = $this->generateNomorInvoice();
		if($wpdb->insert(
				$this->table_name,
				array(
					'no_invoice' => $this->nomor,
					'user_id' => $this->user,
					'distributor_id' => $this->distributor,
					'jarak_kirim_invoice' => $this->jarak_kirim,
					'biaya_kirim_invoice' => $this->biaya_kirim
					),
				array( '%s', '%d', '%d', '%d', '%d')
			)){
			$this->id = $wpdb->insert_id;
		}else{
			$this->id = 0;
		}
	}

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