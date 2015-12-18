<?php

/*include_once(WP_PLUGIN_DIR . '\one-express\template-set\onex_template.php');
include_once(WP_PLUGIN_DIR . '\one-express\jenis-delivery\onex-jenis-delivery.php');*/
class Onex_Menu_Distributor{

	private $table_name;
	private $table_kategori_menu;

	function __construct(){
		$this->table_name = "onex_menu_delivery";
		$this->table_kategori_menu = "onex_kategori_menu";
	}

	public function GetMenuDistributorList(){
		global $wpdb;

		if($wpdb->get_var("SELECT COUNT(*) FROM $this->table_name") > 0){
			
			$attributes['menudist'] = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT * FROM $this->table_name",
							null
						)
					);
		}else{
			$attributes = null;
		}

		return $attributes;
	}

	/**
	*
	* Called by 
	* onex_distributor.php
	*
	*/
	public function GetMenuByDistributorKategori( $distributor_id, $katmenu_id){
		global $wpdb;

		$attributes = null;
		
		$attributes = 
			$wpdb->get_results(
					$wpdb->prepare(
						"SELECT m.* FROM $this->table_name m
						WHERE m.distributor_id = %d AND m.katmenu_id = %d",
						$distributor_id, $katmenu_id
					)
				);

		return $attributes;
	}

	public function GetMenuByKategori( $katmenu_id ){
		global $wpdb;

		$attributes = null;
		$attributes =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name
					WHERE katmenu_id = %d",
					$katmenu_id
					)
				);
		//var_dump($attributes);

		return $attributes;
	}

	/*public function AddKategoriMenu($data){
		global $wpdb;

		if($wpdb->insert(
			$this->table_name,
			array(
				'nama_katmenu' => $data['katmenu_nama'],
				'keterangan_katmenu' => $data['katmenu_keterangan']
			),
			array('%s','%s')
		)){
			return 'Berhasil menambah Kategori Menu.';
		}else{
			return 'Terjadi Kesalahan.';
		}
	}*/

	public function AddMenuDistributor( $data){
		global $wpdb;

		$result = array('status'=>false, 'message' =>'');

		if( $wpdb->insert(
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
		}
		return $result;
	}

	public function UpdateMenuDistributor($id, $data){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';

		if($wpdb->update(
			$this->table_name,
			array(
				'nama_menudel' => $data['menudist_nama'],
				'harga_menudel' => $data['menudist_harga'],
				'gambar_menudel' => $data['menudist_gambar'],
				'keterangan_menudel' => $data['menudist_keterangan'],
				'distributor_id' => $data['menudist_distributor'],
				'katmenu_id' => $data['menudist_kategori']
			),
			array('id_menudel' => $id),
			array('%s','%s', '%s', '%s', '%d', '%d'),
			array('%d')
		)){
			$result['status'] = true;
			$result['message'] = 'berhasil diperbaharui.';
		}else{
			$result['status'] = true;
			$result['message'] = 'Tidak ada pembaharuan.';
		}

		return $result;
	}

	public function DeleteMenuDistributor( $menudel_id){
		global $wpdb;

		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_menudel = %d",
				$menudel_id
			)
		)){
			return 'Berhasil menghapus Menu.';
		}else{
			return 'Terjadi Kesalahan.';
		}
	}

	public function GetHargaMenuDistributor($menudel_id){
		global $wpdb;

		$row =
		$wpdb->get_row(
			$wpdb->prepare(
					"SELECT harga_menudel FROM $this->table_name 
					WHERE id_menudel = %d",
					$menudel_id
				),
				ARRAY_A
			);
		return $row['harga_menudel'];
	}

	public function GetMenuDistributorById( $menudel_id ){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name
					WHERE id_menudel = %d",
					$menudel_id
				), ARRAY_A
			);
		$attributes = $row;
		return $attributes;
	}
}

?>