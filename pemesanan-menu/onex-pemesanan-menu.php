<?php
include_once(WP_PLUGIN_DIR . '\one-express\menu-distributor\onex-menu-distributor.php');
class Onex_Pemesanan_Menu{
	private $table_name;

	function __construct(){
		$this->table_name = "onex_pemesanan_menu";
	}

	public function AddPemesananMenu($data){
		global $wpdb;

		$nilai_menu = $this->getNilaiMenuDistributor( $data['menudel_id'], $data['kuantiti']);

		$wpdb->insert(
				$this->table_name,
				array(
					'menudel_id' => $data['menudel_id'],
					'jumlah_pesanan' => $data['kuantiti'],
					'nilai_pesanan' => $nilai_menu,
					'invoice_id' => $data['invoice_id']
					),
				array( '%d', '%d', '%d', '%d')
			);
		//var_dump($nilai_menu);
		return $nilai_menu;
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

	public function SudahDiPesan( $invoice_id, $menudel_id){
		global $wpdb;

		return $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name WHERE invoice_id=$invoice_id AND menudel_id=$menudel_id");
	}

	private function getNilaiMenuDistributor($menudel_id, $kuantiti){
		$onex_menudel_obj = new Onex_Menu_Distributor();
		$total_harga_menu = ($onex_menudel_obj->GetHargaMenuDistributor($menudel_id) * $kuantiti);
		//var_dump($total_harga_menu);
		return $total_harga_menu;
	}

}
?>