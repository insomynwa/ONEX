<?php

/*include_once(WP_PLUGIN_DIR . '\one-express\template-set\onex_template.php');
include_once(WP_PLUGIN_DIR . '\one-express\jenis-delivery\onex-jenis-delivery.php');*/
class Onex_Kategori_Menu{

	private $table_name;
	private $table_distributor;

	function __construct(){
		$this->table_name = "onex_kategori_menu";
		$this->table_distributor = "onex_distributor";

		add_action('wp_print_scripts', array( $this, 'AjaxKategoriMenuLoadScripts') );
		add_action('wp_ajax_AjaxGetKategoriMenuList', array( $this, 'AjaxLoad_KategoriMenu_List') );
	}

	function AjaxKategoriMenuLoadScripts(){
		wp_localize_script( 'ajax-kategori-menu', 'ajax_one_express', array( 'ajaxurl' => admin_url( 'admin-ajax.php')) );
	}

	function AjaxLoad_KategoriMenu_List(){
		$attributes['katmenu'] = $this->GetKategoriMenuList();
		echo $this->getHtmlTemplate( 'templates/', 'kategori_menu_list', $attributes);
		wp_die();
	}

	/**
	*
	* Called by 
	* this file.php
	*
	*/
	public function GetKategoriMenuList(){
		global $wpdb;

		$attributes = null;

		$attributes = 
			$wpdb->get_results(
					$wpdb->prepare(
						"SELECT km.*, d.nama_dist FROM $this->table_name km
						LEFT JOIN $this->table_distributor d
						ON km.distributor_id=d.id_dist",
						null
					)
				);

		return $attributes;
	}

	public function GetKategoriMenuDistributorById( $katmenu_id){
		global $wpdb;

		$attributes = null;

		$attributes = 
			$wpdb->get_row(
					$wpdb->prepare(
						"SELECT km.*, d.nama_dist FROM $this->table_name km
						LEFT JOIN $this->table_distributor d
						ON km.distributor_id=d.id_dist
						WHERE km.id_katmenu=%d",
						$katmenu_id
					),
					ARRAY_A
				);

		return $attributes;
	}

	/**
	*
	* Called by 
	* onex_distributor.php
	*
	*/
	public function GetKategoriByDistributor($distributor_id){
		global $wpdb;

		$attributes = null;

		$attributes =
			$wpdb->get_results(
					$wpdb->prepare(
							"SELECT * FROM $this->table_name
							WHERE distributor_id = %d",
							$distributor_id
						)
				);
		//var_dump($distributor_id);
		return $attributes;

	}

	public function AddKategoriMenu($data){
		global $wpdb;

		if($wpdb->insert(
			$this->table_name,
			array(
				'nama_katmenu' => $data['katmenu_nama'],
				'distributor_id' => $data['katmenu_distributor'],
				'keterangan_katmenu' => $data['katmenu_keterangan']
			),
			array('%s', '%d', '%s')
		)){
			return 'Berhasil menambah Kategori Menu.';
		}else{
			return 'Terjadi Kesalahan.';
		}
	}

	public function UpdateKategoriMenu($id, $data){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);

		if($wpdb->update(
			$this->table_name,
			array(
				'nama_katmenu' => $data['katmenu_nama'],
				'keterangan_katmenu' => $data['katmenu_keterangan']
			),
			array('id_katmenu' => $id),
			array('%s','%s'),
			array('%d')
		)){
			$result['status'] = true;
			$result['message'] = 'Berhasil.';
		}else{
			$result['status'] = true;
			$result['message'] = 'Tidak ada pembaharuan distributor.';
		}

		return $result;
	}

	public function DeleteDistributor($id){
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

	public function GetKategoriMenuById($id){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name WHERE id_katmenu = %d",
					$id
				), ARRAY_A
			);
		$attributes = $row;
		return $attributes;
	}

	private function getHtmlTemplate( $location, $template_name, $attributes = null ){
		if(! $attributes) $attributes = array();

		ob_start();
		require( $location . $template_name . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		echo $html;
	}
}

?>