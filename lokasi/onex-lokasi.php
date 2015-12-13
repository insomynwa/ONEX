<?php

class Onex_Lokasi{

	private $table_name;

	function __construct(){
		$this->table_name = "onex_lokasi";
	}

	public function GetLokasiAll(){
		global $wpdb;
		$attributes = null;

		$attributes = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT * FROM $this->table_name",
						null
					)
				);

		return $attributes;
	}

	public function AddLokasi( $data){
		global $wpdb;

		$result = array('status'=>false, 'message' =>'');

		/*if( $wpdb->insert(
				$this->table_name,
				array(
					'nama_menudel' => $data['menudist_nama'],
					'harga_menudel' => $data['menudist_harga'],
					'gambar_menudel' => $data['menudist_gambar'],
					'keterangan_menudel' => $data['menudist_keterangan'],
					'distributor_id' => $data['menudist_distributor'],
					'katmenu_id' => $data['menudist_kategori']
				),
				array('%s','%d','%s','%s','%d','%d')
			)
		){
			$result['status'] = true;
			$result['message'] = "Berhasil menambah menu.";
		}else{
			$result['message'] = "Terjadi kesalahan.";
		}*/
		return $result;
	}

	public function UpdateLokasi($id, $data){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		/*if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';

		if($wpdb->update(
			$this->table_name,
			array(
				'nama' => $data['dist_nama'],
				'alamat' => $data['dist_alamat'],
				'kategori_delivery' => $data['dist_jenis_delivery'],
				'telp' => $data['dist_telp'],
				'email' => $data['dist_email'],
				'keterangan' => $data['dist_keterangan'],
				'gambar' => $data['dist_gambar']
			),
			array('id_dist' => $id),
			array('%s','%s'),
			array('%d')
		)){
			$result['status'] = true;
			$result['message'] = 'Distributor berhasil diperbaharui.';
		}else{
			$result['status'] = true;
			$result['message'] = 'Tidak ada pembaharuan distributor.';
		}

		return $result;*/
	}

	public function DeleteLokasi($id){
		/*global $wpdb;

		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_dist = %d",
				$id
			)
		)){
			return 'Berhasil menghapus Distributor.';
		}else{
			return 'Terjadi Kesalahan.';
		}*/
	}

	public function GetLokasiById($id){
		/*global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name WHERE id_dist = %d",
					$id
				), ARRAY_A
			);
		$attributes['distributor'] = $row;
		return $attributes;*/
	}
}

?>