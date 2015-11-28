<?php

class Onex_Distributor{

	private $table_name;
	private $table_jenis_delivery;

	function __construct(){
		$this->table_name = "onex_distributor";
		$this->table_jenis_delivery = "onex_kategori_delivery";
	}

	public function DistributorList(){
		global $wpdb;

		if($wpdb->get_var("SELECT COUNT(*) FROM $this->table_name") > 0){
			
			$attributes['distributor'] = $wpdb->get_results(
						$wpdb->prepare(
							"SELECT d.*, kd.kategori FROM $this->table_name d
							 LEFT JOIN $this->table_jenis_delivery kd
							 ON d.kategori_delivery=kd.id_kat_del
							",null
						)
					);
		}else{
			$attributes = null;
		}

		return $attributes;
	}

	public function AddDistributor($data){
		global $wpdb;

		$result = array(
					'status' => false,
					'message' => ''
				);
		if ($data['dist_gambar'] == '' ) $data['dist_gambar'] = 'NOIMAGE';
		if($wpdb->insert(
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
			array('%s','%s','%d','%s','%s','%s', '%s')
		)){
			$result['status'] = true;
			$result['message'] = 'Berhasil menambah distributor.';
		}else{
			$result['status'] = true;
			$result['message'] = 'Gagal menambah distributor.';
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
/*function onex_distributor_page(){
	echo '<div class="wrap">';
	echo '<h2>Daftar Distributor</h2>';
	echo '<a href="'. admin_url('admin.php?page=onex-distributor-tambah') .'">Tambah</a>';

	echo "<table class='wp-list-table widefat fixed'>";
	echo "<tr><th>id</th>
		<th>nama</th>
		<th>jenis delivery</th>
		<th>alamat</th>
		<th>no. telp</th>
		<th>e-mail</th>
		<th>keterangan</th>
		<th></th></tr>";

	global $wpdb;
	$tbl_onex_distributor = 'onex_distributor';
	$tbl_onex_kategori_delivery = 'onex_kategori_delivery';
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT d.*, kd.kategori FROM $tbl_onex_distributor d
			 LEFT JOIN $tbl_onex_kategori_delivery kd
			 ON d.kategori_delivery=kd.id_kat_del
			",null
		)
	);
	foreach($rows as $row){
		echo "<tr>";
		echo "<td>$row->id_dist</td>";
		echo "<td>$row->nama</td>";
		echo "<td>$row->kategori</td>";
		echo "<td>$row->alamat</td>";
		echo "<td>$row->telp</td>";
		echo "<td>$row->email</td>";
		echo "<td>$row->keterangan</td>";
		echo "<td><a href='". admin_url('admin.php?page=onex-distributor-hapus&id='. $row->id_dist) ."'>Hapus</a> | ";
		echo "<a href='". admin_url('admin.php?page=onex-distributor-update&id='. $row->id_dist) ."'>Update</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<a href='". admin_url('admin.php?page=onex-distributor-tambah') . "' >Tambah</a>";

	echo '</div>';
}*/
?>