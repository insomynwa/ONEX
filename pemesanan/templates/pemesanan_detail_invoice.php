<h3><?php echo $attributes['distributor']->GetNama(); ?></h3>
<p><?php echo $attributes['invoice']->GetTgl(); ?></p>
<p><?php echo $attributes['distributor']->GetKode().$attributes['invoice']->GetUser().$attributes['invoice']->GetNomor(); ?></p>
<table class="table table-responsive">
	<tr>
		<th>No.</th>
		<th>Menu</th>
		<th>Banyak</th>
		<th>Harga @</th>
		<th>Jumlah</th>
	</tr>
<?php for($i = 0; $i < sizeof( $attributes['pesanan']); $i++ ): ?>
	<tr>
		<td><?php echo ($i+1);  ?></td>
		<td><?php echo $attributes['menu'][$i]->GetNama(); ?></td>
		<td><?php echo $attributes['pesanan'][$i]->GetJumlahPesanan(); ?></td>
		<td>Rp.<?php echo $attributes['pesanan'][$i]->GetHargaSatuan(); ?></td>
		<td>Rp.<?php echo $attributes['pesanan'][$i]->GetNilaiPesanan(); ?></td>
	</tr>
<?php endfor; ?>
	<tr>
		<td></td>
		<td>Biaya Kirim</td>
		<td></td>
		<td></td>
		<td>Rp.<?php echo $attributes['invoice']->GetBiayaKirim(); ?> (<?php echo $attributes['invoice']->GetJarakKirim(); ?>KM)</td>
	</tr>
	<tr>
		<td></td>
		<td>Total ( + PPn)</td>
		<td></td>
		<td></td>
		<td><strong>Rp.<?php echo $attributes['total_pembayaran']; ?></strong></td>
	</tr>
</table>
<div>
	<h3>Info Pengiriman</h3>
	<table class="table table-responsive">
		<tr>
			<td>Pemesan</td>
			<td><?php echo $attributes['data_pelanggan']->GetNama(); ?></td>
		</tr>
		<tr>
			<td>Telp</td>
			<td><?php echo $attributes['data_pelanggan']->GetTelp(); ?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td><?php echo $attributes['data_pelanggan']->GetAlamatDetail(); ?></td>
		</tr>
	</table>
</div>