<?php
function onex_bank_page(){
?>
<div class="wrap">
	<h2>Daftar Bank</h2>
	<a href="<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>">Tambah</a>
<?php
	echo "<table class='wp-list-table widefat fixed'>";
	echo "<tr><th>id</th>
		<th>Bank</th>
		<th>Atas Nama</th>
		<th>No. Rekening</th>
		<th></th></tr>";

	global $wpdb;
	$table_bank_name = 'onex_bank';
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT b.* FROM $table_bank_name b",
			null
		)
	);
	foreach($rows as $row){
		echo "<tr>";
		echo "<td>$row->id_bank</td>";
		echo "<td>$row->nama_bank</td>";
		echo "<td>$row->atas_nama</td>";
		echo "<td>$row->no_rekening</td>";
		echo "<td><a href='". admin_url('admin.php?page=onex-jenis-delivery-hapus&id='. $row->id_bank) ."'>Hapus</a> | ";
		echo "<a href='". admin_url('admin.php?page=onex-jenis-delivery-update&id='. $row->id_bank) ."'>Update</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<a href='". admin_url('admin.php?page=onex-jenis-delivery-tambah') . "' >Tambah</a>";
?>
</div>
<?php
}
?>