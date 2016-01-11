<?php
	
class Onex_Status_Pemesanan {

	private $table_name;

	function __construct(){
		$this->table_name = "onex_status_pemesanan";
	}

	private $id;
	public function GetId(){ return $this->id; }

	private $status;
	public function GetStatus() { return $this->status; }
	public function SetStatus($status) { $this->status = $status; }

	public function SetAStatusPemesanan_Id($status_id){
		global $wpdb;

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name
					WHERE id_status = %d",
					$status_id
					),
				ARRAY_A
				);
		$this->id = 0;
		if( !is_null($row) ){
			$this->id = $row['id_status'];
			$this->status = $row['status_pemesanan'];
		}
	}

	public function GetAllStatusPemesananId(){
		global $wpdb;

		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT id_status FROM $this->table_name",
					null
					)
				);

		return $result;
	}
}
?>