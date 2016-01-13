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

	private $status_admin_confirm;
	public function GetStatusAdminConfirm() { return $this->status_admin_confirm; }
	public function SetStatusAdminConfirm($status) { $this->status_admin_confirm = $status; }

	private $bank;
	public function GetBank() { return $this->bank; }
	public function SetBank($bank) { $this->bank = $bank; }

	private $tipe_bayar;
	public function GetTipeBayar() { return $this->tipe_bayar; }
	public function SetTipeBayar($tipe_bayar) { $this->tipe_bayar = $tipe_bayar; }

	private $tgl_user_confirm;
	public function GetTanggalUserConfirm() { return $this->tgl_user_confirm; }
	public function SetTanggalUserConfirm() { 
		date_default_timezone_set('Asia/Jakarta');
		$dtime = date("Y-m-d H:i:s");
		$this->tgl_user_confirm = $dtime; 
	}

	//private $tgl_admin_confirm;
	//public function GetTanggalAdminConfirm() { return $this->tgl_admin_confirm; }
	//public function SetTanggalAdminConfirm($tgl_admin_confirm) { $this->tgl_admin_confirm = $tgl_admin_confirm; }

	private $status_pemesanan;
	public function GetStatusPemesanan() { return $this->status_pemesanan; }
	public function SetStatusPemesanan($status_pemesanan) { $this->status_pemesanan = $status_pemesanan; }


	public function __construct(){
		$this->table_name = "onex_invoice";
	}

	public function UpdateBiayaKirim(){
		global $wpdb;

		if(
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $this->table_name 
					SET biaya_kirim_invoice = %d
					WHERE id_invoice = %d",
					$this->biaya_kirim,
					$this->id
					)
				)
			){
			return true;
		}
		return false;
	}

	public function KonfirmasiPembayaran(){
		global $wpdb;

		$result = array('status'=>false, 'message' =>'');

		if( $wpdb->update(
			$this->table_name,
			array(
				'status_user_confirm' => $this->status_confirm,
				'jam_kirim_invoice' => $this->jam_kirim,
				'bank_id' => $this->bank,
				'tipe_bayar' => $this->tipe_bayar,
				'tgl_user_confirm' => $this->tgl_user_confirm
			),
			array('id_invoice' => $this->id),
			array('%d','%s', '%d', '%d', '%s'),
			array('%d')
		)){
			$result['status'] = true;
			$result['message'] = "Pembayaran Berhasil.";
		}else{
			$result['message'] = "Gagal.";
		}
		return $result;
	}

	public function GetAll_Id_ActiveInvoice_Distributor( $distributor_id){
		global $wpdb;

		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT id_invoice FROM $this->table_name
					WHERE status_aktif_invoice = %d 
					AND status_user_confirm = %d 
					AND status_admin_confirm = %d
					AND distributor_id = %d",
					1,0,0, $distributor_id
					)
				);
		return $result;
	}

	public function UpdateJarakAndBiayaKirim(){
		global $wpdb;

		if(
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $this->table_name 
					SET jarak_kirim_invoice = %d, biaya_kirim_invoice = %d
					WHERE id_invoice = %d",
					$this->jarak_kirim, $this->biaya_kirim,
					$this->id
					)
				)
			){
			return true;
		}
		return false;
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

	public function DeleteInvoice_Distributor( $distributor_id){
		global $wpdb;
		$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM $this->table_name WHERE distributor_id = %d",
					$distributor_id
					)
				);
	}

	public function SetAnInvoice_Id( $invoice_id){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name 
					WHERE id_invoice = %d",
					$invoice_id
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
			$this->tipe_bayar = $row['tipe_bayar'];
			$this->bank = $row['bank_id'];
			$this->status_admin_confirm = $row['status_admin_confirm'];
			$this->tgl_user_confirm = $row['tgl_user_confirm'];
			//$this->tgl_admin_confirm = $row['tgl_admin_confirm'];
			$this->status_pemesanan = $row['status_pemesanan'];
		}
	}

	public function GetAllActiveInvoice(){
		global $wpdb;

		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT id_invoice FROM $this->table_name
					WHERE status_aktif_invoice = %d 
					AND status_user_confirm = %d 
					AND status_admin_confirm = %d",
					1,0,0
					)
				);
		return $result;
	}

	public function GetAllConfirmedInvoiceUser(){
		global $wpdb;

		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT id_invoice FROM $this->table_name
					WHERE status_aktif_invoice = %d 
					AND status_user_confirm = %d 
					AND status_admin_confirm = %d ORDER BY tgl_invoice ASC",
					1,1,0
					)
				);
		return $result;
	}

	public function GetLimitInvoice_Status( $status = 1, $limit, $offset ){
		/*$user_confirm = 0;
		$admin_confirm = 0;
		if( $status == "unconfirmed" ){
			$user_confirm = 1;
			$admin_confirm = 0;
		}else if( $status == "confirmed") {
			$user_confirm = 1;
			$admin_confirm = 1;
		}*/
		global $wpdb;
		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT id_invoice FROM $this->table_name
					WHERE status_pemesanan = %d  
					ORDER BY jam_kirim_invoice ASC
					LIMIT %d, %d",
					$status,
					$offset, $limit
					)
				);

		return $result;
	}

	public function CountInvoiceStatus($status=1) {
		/*$user_confirm = 0;
		$admin_confirm = 0;
		if( $status == "unconfirmed" ){
			$user_confirm = 1;
			$admin_confirm = 0;
		}else if( $status == "confirmed") {
			$user_confirm = 1;
			$admin_confirm = 1;
		}*/

		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT COUNT(id_invoice) AS jumlah FROM $this->table_name
					WHERE status_pemesanan = %d",
					$status
					),
					ARRAY_A
				);
		//var_dump($status, $user_confirm, $admin_confirm, $row['jumlah']);
		return $row['jumlah'];
	}

	public function UpdateStatusPemesanan(){
		global $wpdb;

		if($wpdb->query(
			$wpdb->prepare(
				"UPDATE $this->table_name 
				SET status_pemesanan = %d
				WHERE id_invoice = %d",
				$this->status_pemesanan,
				$this->id
				)
			)
		) {
			return true;
		}
		return false;
	}

	public function GetListOfActiveInvoices_User( $user_id){
		global $wpdb;
		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT i.id_invoice FROM $this->table_name i
						WHERE i.user_id = %d AND i.status_aktif_invoice = %d AND i.status_user_confirm = %d AND i.status_admin_confirm = %d",
						$user_id, 1, 0, 0
					)
				);
		return $result;
	}

	public function GetListAllInvoice_User( $user_id){
		global $wpdb;
		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT i.id_invoice FROM $this->table_name i
						WHERE i.user_id = %d AND i.status_aktif_invoice = %d",
						$user_id, 1
					)
				);
		return $result;
	}

	public function GetPesanan(){
		global $wpdb;
		$pemesanan_table = "onex_pemesanan_menu";

		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT pm.id_pesanan FROM $pemesanan_table pm
					WHERE pm.invoice_id = %d",
					$this->id
					)
				);
		return $result;
	}

	public function SetAnActiveInvoice_UserDistributor( $user, $distributor){
		global $wpdb;
		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT i.* FROM $this->table_name i
					WHERE i.user_id = %d 
					AND i.status_aktif_invoice = '%d' 
					AND i.status_user_confirm = %d 
					AND i.status_admin_confirm = '%d' 
					AND i.distributor_id = %d",
						$user, 1, 0, 0,
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
			$this->bank = $row['bank_id'];
			$this->tipe_bayar = $row['tipe_bayar'];
			$this->tgl_user_confirm = $row['tgl_user_confirm'];
			//$this->tgl_admin_confirm = $row['tgl_admin_confirm'];
			$this->status_pemesanan = $row['status_pemesanan'];
		}
	}

	/*public function CreateInvoice( $user_id, $invoice_data){
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
	}*/

	public function CreateNewInvoice(){
		global $wpdb;

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

		$expiredInvoice = $this->GetExpiredInvoice();

		if(!empty( $expiredInvoice) && !is_null( $expiredInvoice) ){

			//foreach ($expiredInvoice as $invoice ){
				$wpdb->query(
					$wpdb->prepare(
						/*"UPDATE $this->table_name 
						SET status_aktif_invoice = 0
						WHERE id_invoice = %d",
						$invoice->id_invoice*/
						"DELETE FROM $this->table_name 
						WHERE status_aktif_invoice = 1 AND tgl_invoice < NOW() - INTERVAL 24 HOUR AND status_user_confirm = 0 AND status_admin_confirm = 0",//WHERE id_invoice = %d",
						null//$invoice->id_invoice
						)
					);
			//}
		}
	}

	function GetExpiredInvoice(){
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
		/*$tahun = date('Y');
		$tahun %= 2000;
		$bulan = date('n');*/
		$tgl = date('d');
		/*$jam = date('G');
		$minutes = intval(date('i'));
		$seconds = intval(date('s'));
		$nomor = $tahun+$bulan+$tgl+$jam+$minutes+$seconds;*/
		$nomor = $tgl.''.($this->countInvoiceUser_Distributor() + 1);
		/*if($nomor<10) $nomor = "00". $nomor;
		else if( $nomor < 100) $nomor = "0" . $nomor;*/

		$kode = "$nomor";
		return $kode;
	}

	private function countInvoiceUser_Distributor(){
		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT COUNT(id_invoice) AS jumlah FROM $this->table_name
					WHERE user_id = %d AND distributor_id = %d",
					$this->user, $this->distributor
					),
					ARRAY_A
				);

		return $row['jumlah'];
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