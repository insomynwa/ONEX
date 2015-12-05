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

	public function GetDistributorByTemplate($template_name){
		global $wpdb;

		$template = new Onex_Template();

		$template_id = $template->GetTemplateIdByName($template_name);
			
		$attributes = null;

		if($template_id){

			$kat_delivery = new Onex_Jenis_Delivery();

			$kat_del_id = $kat_delivery->GetJenisDeliveryByTemplate($template_id);

			if($kat_del_id){
				if($wpdb->get_var("SELECT COUNT(*) FROM $this->table_name") > 0){
							
					$attributes['distributor'] = 
						$wpdb->get_results(
							$wpdb->prepare(
								"SELECT * FROM $this->table_name d
								 WHERE kategori_delivery = %d",
								 $kat_del_id
							)
						);
				}
			}
		}

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

	public function UpdateDistributor($id, $data){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';

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

		return $result;
	}

	public function DeleteDistributor($id){
		global $wpdb;

		if($wpdb->query(
			$wpdb->prepare(
				"DELETE FROM $this->table_name WHERE id_dist = %d",
				$id
			)
		)){
			return 'Berhasil menghapus Distributor.';
		}else{
			return 'Terjadi Kesalahan.';
		}
	}

	public function GetDistributor($id){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name WHERE id_dist = %d",
					$id
				), ARRAY_A
			);
		$attributes['distributor'] = $row;
		return $attributes;
	}
}

?>