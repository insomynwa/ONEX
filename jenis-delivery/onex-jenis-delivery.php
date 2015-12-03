<?php

include_once(WP_PLUGIN_DIR . '\one-express\distributor\onex-distributor.php');
class Onex_Jenis_Delivery{
	private $table_name;

	public function __construct(){
		$this->table_name = "onex_kategori_delivery";

		add_action('wp_print_scripts', array( $this, 'AjaxDeliveryLoadScripts'));
		add_action('wp_ajax_AjaxGetJenisDeliveryList', array( $this, 'AjaxLoad_JenisDelivery_List') );
		add_action('wp_ajax_AjaxGetJenisDeliveryDetail', array( $this, 'AjaxLoad_JenisDelivery_Detail') );
	}

	function AjaxDeliveryLoadScripts(){
		wp_localize_script( 'ajax-delivery', 'ajax_one_express', array( 'ajaxurl' => admin_url( 'admin-ajax.php')) );
	}

	function AjaxLoad_JenisDelivery_List(){
		$attributes = $this->DeliveryList();
		echo $this->getHtmlTemplate( 'templates/', 'delivery_list', $attributes );
		wp_die();
	}

	function AjaxLoad_JenisDelivery_Detail(){
		if( isset( $_GET['katdel']) ){
			$attributes = $this->GetDelivery( $_GET['katdel'] );
			$onex_distributor_obj = new Onex_Distributor();
			$attributes['katdel_rel'] = $onex_distributor_obj->GetDistributorByJenisDelivery( $_GET['katdel']);

			echo $this->getHtmlTemplate( 'templates/', 'delivery_detail', $attributes );
		}
		wp_die();
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

	public function GetJenisDeliveryByTemplate($template_id){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name WHERE template_id = %d",
					$template_id
				), ARRAY_A
			);

		if( !is_null($row) && !empty($row))
			return $row['id_kat_del'];

		return 0;
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
		$attributes['katdel'] = $row;
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