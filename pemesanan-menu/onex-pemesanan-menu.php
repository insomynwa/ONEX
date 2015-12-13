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

	private function getNilaiMenuDistributor($menudel_id, $kuantiti){
		$onex_menudel_obj = new Onex_Menu_Distributor();
		$total_harga_menu = ($onex_menudel_obj->GetHargaMenuDistributor($menudel_id) * $kuantiti);
		//var_dump($total_harga_menu);
		return $total_harga_menu;
	}

}
?>