<?php

class Onex_Template{

	private $table_name;

	function __construct(){
		$this->table_name = "onex_template";
	}

	public function GetTemplateList(){

	}

	public function GetTemplateIdByName($template_name){
		global $wpdb;

		$row = 
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM $this->table_name WHERE nama_template = '%s'",
					$template_name
				), ARRAY_A
			);
		
		if( !is_null($row) && !empty($row))
			return $row['id_template'];

		return 0;
	}
}

?>