<?php
class Onex_Data_Pembeli{

	private $table_name;
	
	private $id;
	public function GetId(){ return $this->id; }

	private $nama;
	public function GetNama() { return $this->nama; }
	public function SetNama( $nama ) { $this->nama = $nama; }

	private $telp;
	public function GetTelp() { return $this->telp; }
	public function SetTelp( $telp ) { $this->telp = $telp; }

	private $alamat_area;
	public function GetAlamatArea() { return $this->alamat_area; }
	public function SetAlamatArea( $alamat_area ) { $this->alamat_area = $alamat_area; }

	private $alamat_detail;
	public function GetAlamatDetail() { return $this->alamat_detail; }
	public function SetAlamatDetail( $alamat_detail ) { $this->alamat_detail = $alamat_detail; }

	private $user;
	public function GetUser() { return $this->user; }
	public function SetUser( $user ) { $this->user = $user; }

	public function __construct(){
		$this->table_name = "onex_data_pembeli";
	}

	public function GetAll_User_DataPembeli(){
		global $wpdb;

		$result =
			$wpdb->get_results(
				$wpdb->prepare(
					"SELECT user_id FROM $this->table_name",
					null
					)
				);
		return $result;
	}

	public function SetDataPembeliUser( $user_id ){
		global $wpdb;

		$alamat_area_table = "onex_alamat_area";

		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT dp.* FROM $this->table_name dp
					WHERE dp.user_id = %d",
					$user_id
					),
				ARRAY_A
				);
		
		$this->id = 0;
		if( !is_null($row) ){
			$this->id = $row['id_datapembeli'];
			$this->nama = $row['nama_datapembeli'];
			$this->telp = $row['telp_datapembeli'];
			$this->alamat_area = $row['alamatarea_id'];
			$this->alamat_detail = $row['alamat_detail_datapembeli'];
			$this->user = $row['user_id'];
		}
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

	public function GetAlamatCustomer( $user_id){
		global $wpdb;
		$row =
			$wpdb->get_row(
				$wpdb->prepare(
					"SELECT alamat_detail_datapembeli FROM $this->table_name WHERE user_id = %d",
					$user_id
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
			$result['code'] = 1;
			$result['message'] = 'berhasil diperbaharui.';
		}else{
			$result['status'] = true;
			$result['code'] = 2;
			$result['message'] = 'Tidak ada pembaharuan';
		}

		return $result;
	}

	public function UpdateJumlahPesananMenu( $pesanan_id, $jumlah_pesan){
		global $wpdb;

		$result = array( 'status' => false, 'message' => '');

		if(
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE $this->table_name 
					SET jumlah_pesanan = %d , nilai_pesanan = (harga_satuan *  %d) 
					WHERE id_pesanan = %d",
					$jumlah_pesan, $jumlah_pesan,
					$pesanan_id
					)
				)
			){
			$result['status'] = true;
			$result['message'] = 'Berhasil menambah jumlah pesanan';
		}

		return $result;
	}

	public function AddDataPembeliUser( $data, $user_id){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);

		if(
			$wpdb->insert(
				$this->table_name,
				array(
					'nama_datapembeli' => $data['nama'],
					'telp_datapembeli' => $data['telp'],
					'alamat_detail_datapembeli' => $data['detail_alamat'],
					'alamatarea_id' => 1,
					'user_id' => $user_id
					),
				array(
					'%s', '%s', '%s', '%d', '%d'
					)
				)
			){
			$result['status'] = true;
			$result['code'] = 1;
			$result['message'] = 'Berhasil menambah data customer';
		}

		return $result;
	}
}

?>