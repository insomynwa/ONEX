<?php
class Onex_Data_Pembeli{

	private $table_name;

	public function __construct(){
		$this->table_name = "onex_data_pembeli";
	}

	public function GetDataPembeliByUser( $customer_id ){
		global $wpdb;

		$alamat_area_table = "onex_alamat_area";

		$attributes = null;

		$attributes =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name dp
					LEFT JOIN $alamat_area_table aa
					ON dp.alamatarea_id=aa.id_alamatarea
					WHERE dp.user_id = %d",
					$customer_id
					),
				ARRAY_A
				);
		return $attributes;
	}

	public function GetAlamatCustomer( $id_customer){
		global $wpdb;
		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT alamat_detail_datapembeli FROM $this->table_name WHERE id_datapembeli = %d",
					$id_customer
					), 
				ARRAY_A
				);

		return $row['alamat_detail_datapembeli'];
	}

	public function UpdateDataPembeliByUser( $data_id, $data, $user_id ){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		//var_dump($data_id, $data, $user_id);
		if($wpdb->update(
			$this->table_name,
			array(
				'nama_datapembeli' => $data['nama'],
				'alamat_detail_datapembeli' => $data['detail_alamat'],
				'telp_datapembeli' => $data['telp']
			),
			array('id_datapembeli' => $data_id, 'user_id' => $user_id),
			array('%s','%s','%s'),
			array('%d', '%d')
		)){
			$result['status'] = true;
			$result['message'] = 'berhasil diperbaharui.';
		}else{
			$result['status'] = true;
			$result['message'] = 'Tidak ada pembaharuan';
		}

		return $result;
	}
}

?>