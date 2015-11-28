<?php
function onex_jenis_menu_page(){
?>
<div class="wrap">
	<h2>Daftar Bank</h2>
	<a href="<?php echo admin_url('admin.php?page=onex-jenis-delivery-tambah'); ?>">Tambah</a>
<?php
	echo "<table class='wp-list-table widefat fixed'>";
	echo "<tr><th>id</th>
		<th>Jenis Menu</th>
		<th></th></tr>";

	global $wpdb;
	$onex_katmenumakmin_table_name = 'onex_kategori_menu_makmin';
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT km.* FROM $onex_katmenumakmin_table_name km",
			null
		)
	);
	foreach($rows as $row){
		echo "<tr>";
		echo "<td>$row->id_kat_menu_makmin</td>";
		echo "<td>$row->nama_kategori</td>";
		echo "<td><a href='". admin_url('admin.php?page=onex-jenis-delivery-hapus&id='. $row->id_kat_menu_makmin) ."'>Hapus</a> | ";
		echo "<a href='". admin_url('admin.php?page=onex-jenis-delivery-update&id='. $row->id_kat_menu_makmin) ."'>Update</a></td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<a href='". admin_url('admin.php?page=onex-jenis-delivery-tambah') . "' >Tambah</a>";
?>
</div>
<?php
}
function get_jenis_menu(){
	global $wpdb;
	$onex_katmenumakmin_table_name = 'onex_kategori_menu_makmin';
	$rows = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT km.* FROM $onex_katmenumakmin_table_name km",
			null
		)
	);
	$retrieve = "";
	$retrieve .= "<table class='wp-list-table widefat fixed'>";
	$retrieve .= "<tr><th>id</th>
		<th>Jenis Menu</th>
		<th></th></tr>";
	foreach($rows as $row){
		$retrieve .= "<tr>";
		$retrieve .= "<td>$row->id_kat_menu_makmin</td>";
		$retrieve .= "<td>$row->nama_kategori</td>";
		$retrieve .= "</tr>";
	}
	$retrieve .= "</table>";

	return $retrieve;
}
?>