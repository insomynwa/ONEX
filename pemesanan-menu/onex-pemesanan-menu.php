<?php
include_once(WP_PLUGIN_DIR . '/one-express/menu-distributor/onex-menu-distributor.php');
class Onex_Pemesanan_Menu{
	private $table_name;

	private $id;
	public function GetId(){ return $this->id; }

	private $menudel;
	public function GetMenudel() { return $this->menudel; }
	public function SetMenudel( $menudel ) { $this->menudel = $menudel; }

	private $jumlah_pesanan;
	public function GetJumlahPesanan() { return $this->jumlah_pesanan; }
	public function SetJumlahPesanan( $jumlah_pesanan ) { $this->jumlah_pesanan = $jumlah_pesanan; }
	
	private $harga_satuan;
	public function GetHargaSatuan() { return $this->harga_satuan; }
	public function SetHargaSatuan($harga_satuan) { $this->harga_satuan = $harga_satuan; }
	
	private $nilai_pesanan;
	public function GetNilaiPesanan() { return $this->nilai_pesanan; }
	public function SetNilaiPesanan($nilai_pesanan) { $this->nilai_pesanan = $nilai_pesanan; }

	private $invoice;
	public function GetInvoice() { return $this->invoice; }
	public function SetInvoice($invoice) { $this->invoice = $invoice; }

	function __construct(){
		$this->table_name = "onex_pemesanan_menu";
	}

	public function AddNewPemesanan(){
		global $wpdb;

		$wpdb->insert(
				$this->table_name,
				array(
					'menudel_id' => $this->menudel,
					'jumlah_pesanan' => $this->jumlah_pesanan,
					'nilai_pesanan' => $this->nilai_pesanan,
					'harga_satuan' => $this->harga_satuan,
					'invoice_id' => $this->invoice
					),
				array( '%d', '%d', '%d', '%d', '%d')
			);
	}

	public function HasAnyMenu_Invoice( $invoice_id){
		global $wpdb;

		$row = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $this->table_name 
				WHERE invoice_id = %d",
				$invoice_id
				)
			);
		//var_dump($row);
		if( $row > 0) return true;
		return false;
	}

	public function UpdateJumlahPemesanan(){
		global $wpdb;

		$status = false;
		if($wpdb->query(
			$wpdb->prepare(
				"UPDATE $this->table_name 
				SET jumlah_pesanan = %d, nilai_pesanan = %d
				WHERE id_pesanan = %d",
				$this->jumlah_pesanan, $this->nilai_pesanan,
				$this->id
			)
		)){
			$status = true;
		}
		return $status;
	}

	public function AddPemesananMenu($data){
		global $wpdb;

		$nilai_menu = $data['kuantiti'] * $data['harga'];//$this->getNilaiMenuDistributor( $data['menudel_id'], $data['kuantiti']);

		$wpdb->insert(
				$this->table_name,
				array(
					'menudel_id' => $data['menudel_id'],
					'jumlah_pesanan' => $data['kuantiti'],
					'nilai_pesanan' => $nilai_menu,
					'harga_satuan' => $data['harga'],
					'invoice_id' => $data['invoice_id']
					),
				array( '%d', '%d', '%d', '%d', '%d')
			);
		//var_dump($nilai_menu);
		//return $nilai_menu;
	}

	public function GetJumlahPemesananMenuByUser( $user_id){
		global $wpdb;

		$invoice_table = "onex_invoice";

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT COUNT(pm.menudel_id) AS total_menudel FROM $this->table_name pm
					LEFT JOIN $invoice_table i 
					ON pm.invoice_id = i.id_invoice
					WHERE i.user_id = %d AND i.status_aktif_invoice = 1 AND i.status_user_confirm = 0",
					$user_id
					),
				ARRAY_A
				);
		return $row['total_menudel'];
	}

	public function GetPesananMenuByInvoice( $invoice_id ){
		global $wpdb;

		$menudel_table = "onex_menu_delivery";

		$attributes = null;
		$attributes = 
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT pm.*, md.* FROM $this->table_name pm
					LEFT JOIN $menudel_table md
					ON pm.menudel_id=md.id_menudel
					WHERE pm.invoice_id = %d",
					$invoice_id
					)
				);
			//var_dump($attributes);
		return $attributes;
	}

	public function GetTotalNilaiPesanan( $invoice_id){
		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT SUM(nilai_pesanan) AS total_nilai FROM $this->table_name 
					WHERE invoice_id = %d",
					$invoice_id
					),
				ARRAY_A
				);

		return $row['total_nilai'];
	}

	public function SetPesananMenu_Id( $id) {
		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name 
					WHERE id_pesanan = %d",
					$id
					),
				ARRAY_A
				);

		$this->id = 0;
		if( !is_null($row) ){
			$this->id = $row['id_pesanan'];
			$this->menudel = $row['menudel_id'];
			$this->jumlah_pesanan = $row['jumlah_pesanan'];
			$this->harga_satuan = $row['harga_satuan'];
			$this->nilai_pesanan = $row['nilai_pesanan'];
			$this->invoice = $row['invoice_id'];
		}
	}

	public function SetPesananMenu_InvoiceMenuDistributor( $invoice, $menudel){
		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name 
					WHERE invoice_id = %d AND menudel_id = %d",
					$invoice, $menudel
					),
				ARRAY_A
				);

		$this->id = 0;
		if( !is_null($row) ){
			$this->id = $row['id_pesanan'];
			$this->menudel = $row['menudel_id'];
			$this->jumlah_pesanan = $row['jumlah_pesanan'];
			$this->harga_satuan = $row['harga_satuan'];
			$this->nilai_pesanan = $row['nilai_pesanan'];
			$this->invoice = $row['invoice_id'];
		}
	}

	public function DeletePesananMenu(){
		global $wpdb;

		$status = false;
		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_pesanan = %d",
				$this->id
			)
		)){
			$status = true;
		}
		return $status;
	}

	public function SudahDiPesan( $invoice_id, $menudel_id){
		global $wpdb;

		return $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name WHERE invoice_id=$invoice_id AND menudel_id=$menudel_id");
	}

	/*private function getNilaiMenuDistributor($menudel_id, $kuantiti){
		$onex_menudel_obj = new Onex_Menu_Distributor();
		$total_harga_menu = ($onex_menudel_obj->GetHargaMenuDistributor($menudel_id) * $kuantiti);
		//var_dump($total_harga_menu);
		return $total_harga_menu;
	}*/

}
?>