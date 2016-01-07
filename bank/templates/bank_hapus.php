<?php
	$bank_id = $attributes->GetId();

	if(isset($_POST['menu-hapus-submit'])){
		
		$result = $attributes->DeleteBank();
		$message = $result['message'];
	}
	
?>
<div class="wrap">
	<h2>Hapus Bank</h2>
<?php if($_POST['menu-hapus-submit']) { ?>
	<div class="updated"><?php echo $message; ?></div>
<?php } else { ?>
	<p>Apa anda yakin akan menghapus <strong><?php echo $attributes->GetNama(); ?></strong>?</p>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		<p>
			<input type="submit" name="menu-hapus-submit" value="Ya" />
		</p>
	</form>
<?php } ?>
	<a href="<?php echo admin_url('admin.php?page=onex-bank-page'); ?>">kembali ke Daftar Bank</a>
</div>