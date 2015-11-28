<?php

class Onex_Jenis_Delivery{
	private $table_name;

	public function __construct(){
		$this->table_name = "onex_kategori_delivery";
	}

	public function DeliveryList(){
		global $wpdb;
		//$table_name = "onex_kategori_delivery";

		if($wpdb->get_var("SELECT COUNT(*) FROM $this->table_name") > 0){
			$attributes['kat_del'] = 
				$wpdb->get_results(
					$wpdb->prepare(
						"SELECT id_kat_del, kategori, keterangan FROM $this->table_name", null
					)
				);
		}else{
			$attributes = null;
		}

		return $attributes;
		//return $this->getHtmlTemplate('delivery_list', $attributes);
	}

	public function AddDelivery($data){
		global $wpdb;
		//$table_kat_del_name = "onex_kategori_delivery";
		if($wpdb->insert(
			$this->table_name,
			array(
				'kategori' => $data['katdel_nama'],
				'keterangan' => $data['katdel_keterangan']
			),
			array('%s','%s')
		)){
			return 'Berhasil menambah jenis delivery.';
		}else{
			return 'Terjadi Kesalahan.';
		}

	}

	public function GetDelivery($id){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name WHERE id_kat_del = %d",
					$id
				), ARRAY_A
			);
		$attributes['kat_del'] = $row;
		return $attributes;
	}

	public function DeleteDelivery($id){
		global $wpdb;

		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_kat_del = %d",
				$id
			)
		)){
			return 'Berhasil menghapus jenis delivery.';
		}else{
			return 'Terjadi Kesalahan.';
		}
	}

	public function UpdateDelivery($data){
		global $wpdb;

		if($wpdb->update(
			$this->table_name,
			array(
				'kategori' => $data['katdel_nama'],
				'keterangan' => $data['katdel_keterangan']
			),
			array('id_kat_del' => $data['katdel_id']),
			array('%s','%s'),
			array('%d')
		)){
			return "Jenis Delivery berhasil diperbaharui.";
		}else{
			return "Terjadi Kesalahan.";
		}
	}
}
?>