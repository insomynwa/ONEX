<?php

add_action('admin_menu', 'one_express_modmenu');
function one_express_modmenu(){

	// MAIN MENU *************************
	add_menu_page(
		'One Express',
		'One Express',
		'manage_options',
		'onex-main-page',
		onex_main_page
	);

	// Sub MENU "DISTRIBUTOR" ************
	add_submenu_page(
		'onex-main-page',
		'Distributor One Express',
		'Distributor',
		'manage_options',
		'onex-distributor-page',
		'onex_distributor_page'
	);

	add_submenu_page(
		null,
		'Tambah Distributor',
		'Tambah Distributor',
		'manage_options',
		'onex-distributor-tambah',
		'onex_distributor_tambah'
	);

	add_submenu_page(
		null,
		'Update Distributor',
		'Update Distributor',
		'manage_options',
		'onex-distributor-update',
		'onex_distributor_update'
	);

	add_submenu_page(
		null,
		'Hapus Distributor',
		'Hapus Distributor',
		'manage_options',
		'onex-distributor-hapus',
		'onex_distributor_hapus'
	);

	// Sub MENU "JENIS DELIVERY" ************
	add_submenu_page(
		'onex-main-page',
		'Jenis Delivery One Express',
		'Jenis Delivery',
		'manage_options',
		'onex-jenis-delivery-page',
		'onex_jenis_delivery_page'
	);

	add_submenu_page(
		null,
		'Tambah Jenis Delivery',
		'Tambah Jenis Delivery',
		'manage_options',
		'onex-jenis-delivery-tambah',
		'onex_jenis_delivery_tambah'
	);
	add_submenu_page(
		null,
		'Update Jenis Delivery',
		'Update Jenis Delivery',
		'manage_options',
		'onex-jenis-delivery-update',
		'onex_jenis_delivery_update'
	);
	add_submenu_page(
		null,
		'Hapus Jenis Delivery',
		'Hapus Jenis Delivery',
		'manage_options',
		'onex-jenis-delivery-hapus',
		'onex_jenis_delivery_hapus'
	);

	// Sub MENU "BANK" **********************
	add_submenu_page(
		'onex-main-page',
		'Bank',
		'Bank',
		'manage_options',
		'onex-bank-page',
		'onex_bank_page'
	);

}


require_once(ONEXPLUGINDIR . 'distributor/onex-distributor.php');
require_once(ONEXPLUGINDIR . 'distributor/onex-distributor-tambah.php');
require_once(ONEXPLUGINDIR . 'distributor/onex-distributor-update.php');
require_once(ONEXPLUGINDIR . 'distributor/onex-distributor-hapus.php');
require_once(ONEXPLUGINDIR . 'jenis-delivery/onex-jenis-delivery.php');
require_once(ONEXPLUGINDIR . 'jenis-delivery/onex-jenis-delivery-tambah.php');
require_once(ONEXPLUGINDIR . 'jenis-delivery/onex-jenis-delivery-update.php');
require_once(ONEXPLUGINDIR . 'jenis-delivery/onex-jenis-delivery-hapus.php');
require_once(ONEXPLUGINDIR . 'bank/onex-bank.php');