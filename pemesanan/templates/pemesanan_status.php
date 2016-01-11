<form>
	<?php if(sizeof($attributes['status'])>0): ?>
	<ul>
		<?php for( $i=0; $i < sizeof($attributes['status']); $i++ ): ?>
		<?php $status = $attributes['status'][$i]; ?>
		<li>
			<input type="radio" name="status-pemesanan" value="<?php echo $status->GetId(); ?>" <?php if($status->GetId()==$attributes['current_status']) echo "checked='checked'"; ?> /> <span><?php echo $status->GetStatus(); ?></span>
		</li>
		<?php endfor; ?>
	</ul>
	<?php endif; ?>
</form>