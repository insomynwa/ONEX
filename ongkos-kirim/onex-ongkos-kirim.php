<?php
class Onex_Ongkos_Kirim{

	private $table_name;
	
	private $id;
	public function GetId(){ return $this->id; }

	private $jarak_minimal;
	public function GetJarakMinimal() { return $this->jarak_minimal; }
	public function SetJarakMinimal( $jarak_minimal ) { $this->jarak_minimal = $jarak_minimal; }
	
	private $tarif_minimal;
	public function GetTarifMinimal() { return $this->tarif_minimal; }
	public function SetTarifMinimal($tarif_minimal) { $this->tarif_minimal = $tarif_minimal; }

	private $tarif_normal;
	public function GetTarifNormal() { return $this->tarif_normal; }
	public function SetTarifNormal($tarif_normal) { $this->tarif_normal = $tarif_normal; }

	public function __construct(){
		$this->table_name = "onex_biaya_kirim";

		//add_action('')
	}

	/*public function GetListOfTarifKirim(){
		global $wpdb;

		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name "
					)
				);

		return $result;
	}*/

	public function SetATarifKirim(){
		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name LIMIT 1",
					null
					),
				ARRAY_A
				);
		$this->id = 0;
		if( !is_null($row) ){
			$this->id = $row['id_biayakirim'];
			$this->jarak_minimal = $row['jarak_min_kirim'];
			$this->tarif_minimal = $row['tarif_min_kirim'];
			$this->tarif_normal = $row['tarif_normal_kirim'];
		}
	}

	public function SetATarifKirim_Id( $tarif_id){
		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name
					WHERE id_biayakirim = %d",
					$tarif_id
					),
				ARRAY_A
				);
		$this->id = 0;
		if( !is_null($row) ){
			$this->id = $row['id_biayakirim'];
			$this->jarak_minimal = $row['jarak_min_kirim'];
			$this->tarif_minimal = $row['tarif_min_kirim'];
			$this->tarif_normal = $row['tarif_normal_kirim'];
		}
	}

	public function UpdateTarifKirim(){
		global $wpdb;
		//var_dump($this->jarak_minimal, $this->tarif_minimal, $this->tarif_normal, $this->id);
		if(
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $this->table_name 
					SET 
					jarak_min_kirim = %d,
					tarif_min_kirim = %d,
					tarif_normal_kirim = %d
					WHERE id_biayakirim = %d",
					$this->jarak_minimal, $this->tarif_minimal, $this->tarif_normal,
					$this->id
					)
				)
			){
			return true;
		}
		return false;
	}

	public function GetTarifPerKm(){
		global $wpdb;

		//$alamat_area_table = "onex_alamat_area";

		$attributes = null;

		$attributes =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT tarif_normal_kirim FROM $this->table_name",
					null
					),
				ARRAY_A
				);
		return $attributes['tarif_normal_kirim'];
	}

	function GetJarakKm($from, $to) {
		$API_KEY = "AIzaSyAGJKzSI54D-9Fm6zW0zD4GttumyM5oXxQ";
		$from = urlencode($from);
		$to = urlencode($to);
		$url_google_distance_matrix_API = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$from."&destinations=".$to."&language=id-ID&key=".$API_KEY;
		//var_dump($url_google_distance_matrix_API);
		$result = file_get_contents($url_google_distance_matrix_API);

		$data = json_decode( $result, true);

		return ($data['rows'][0]['elements'][0]['distance']['value'])  / 1000; // m to km
	}

	public function CountBiayaKirim( $jarak){
		if( $jarak < $this->jarak_minimal){
			return $this->tarif_minimal;
		}else{
			$n5km = intval($jarak) / $this->jarak_minimal;
			if( $n5km < 1) $n5km = 0;
			$mod5km = intval( $jarak ) % $this->jarak_minimal;
			return ( intval( $n5km ) * $this->tarif_minimal ) + ( $mod5km * $this->tarif_normal);
		}
	}

	public function GetTarifAwal5Km(){
		global $wpdb;

		//$alamat_area_table = "onex_alamat_area";

		$attributes = null;

		$attributes =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT tarif_min_kirim FROM $this->table_name",
					null
					),
				ARRAY_A
				);
		return $attributes['tarif_min_kirim'];
	}
}

?>