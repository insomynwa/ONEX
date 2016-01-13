<form class="form" id="form-modal-status-pemesanan">
	<?php if(sizeof($attributes['status'])>0): ?>
	<ul>
		<?php for( $i=0; $i < sizeof($attributes['status']); $i++ ): ?>
		<?php $status = $attributes['status'][$i]; ?>
		<li>
			<input type="radio" name="status-pemesanan" value="<?php echo $status->GetId(); ?>" <?php if($status->GetId()==$attributes['current_status']) echo "checked='checked'"; ?> /> <span><?php echo $status->GetStatus(); ?></span>
		</li>
		<?php endfor; ?>
	</ul>
	<input type="hidden" name="status-invoice" value="<?php echo $attributes['invoice']; ?>" /> 
	<input type="submit" name="status-submit" id="status-submit" value="OK" />
	<?php endif; ?>
</form>
<script type="text/javascript">
jQuery(document).ready(function($) {

	$("#form-modal-status-pemesanan").submit( function (event) {
		event.preventDefault();
		var invoice = $("input[name='status-invoice']").val();
		var status = $("input[name='status-pemesanan']:checked").val();
		var data = {
			action : 'AjaxUpdateStatusPemesanan',
			invoice: invoice,
			status : status
		}
		$.post(ajax_one_express.ajaxurl, data, function(response) {
			var result = jQuery.parseJSON(response);
			$("#modal-invoice").modal('hide');
			if( result){
				$("#modal-invoice").html("");
				window.doCreatePagination("pemesanan-waiting", "div#new-invoice-pagination-area");
			}
		});
	});

});
</script>