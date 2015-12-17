<?php
class Onex_Ongkos_Kirim{

	private $table_name;

	public function __construct(){
		$this->table_name = "onex_ongkos_kirim";
	}

	public function GetTarifPerKm(){
		global $wpdb;

		//$alamat_area_table = "onex_alamat_area";

		$attributes = null;

		$attributes =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name",
					null
					),
				ARRAY_A
				);
		return $attributes['tarif_perkm_ongkir'];
	}
}

?>