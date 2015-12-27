<div class="wrap ongkir-main-area">
	<h2>Tarif Pengiriman</h2>
	<div id="list-area">
		<div>
			<p>Tarif <span class="ongkir-jarak-minimal"><?php echo $attributes->GetJarakMinimal(); ?></span> KM pertama : Rp.<span class="ongkir-tarif-minimal"><?php echo $attributes->GetTarifMinimal(); ?></span></p>
			<p>Tarif normal: Rp.<span class="ongkir-tarif-normal"><?php echo $attributes->GetTarifNormal(); ?></span> / KM</p>
			<p><a data-toggle="modal" href="#modal-update-tarif-kirim">Update</a></p>
		</div>
	</div>
	<div id="modal-update-tarif-kirim" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
					<h4 class="modal-title">Update Tarif</h4>
				</div>
				<div class="modal-body">
					<form id="form-modal-ongkir-update" class="form-horizontal" role="form">
						<div class="form-group">
							<label class="control-label col-sm-3" for="email">Jarak KM pertama:</label>
							<div class="col-sm-9">
								<input type="text" class="form-control ongkir-jarak-minimal" id="email" placeholder="" value="<?php echo $attributes->GetJarakMinimal(); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3" for="email">Tarif KM pertama:</label>
							<div class="col-sm-9">
								<input type="text" class="form-control ongkir-tarif-minimal" id="email" placeholder="" value="<?php echo $attributes->GetTarifMinimal(); ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-3" for="pwd">Tarif normal / KM:</label>
							<div class="col-sm-9"> 
								<input type="text" class="form-control ongkir-tarif-normal" id="pwd" placeholder="" value="<?php echo $attributes->GetTarifNormal(); ?>">
								<input type="hidden" class="ongkir-id" value="<?php echo $attributes->GetId(); ?>" />
							</div>
						</div>
						<div class="form-group"> 
							<div class="col-sm-offset-3 col-sm-9">
								<button type="submit" class="btn btn-default">Simpan</button>
							</div>
						</div>
					</form>
				</div>
				<!-- <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div> -->
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready( function($){
	$("form#form-modal-ongkir-update").submit( function(event) {
		event.preventDefault();

        /*var data = [];
        data['id'] = $("input.ongkir-id").val();
        data['jarak_minimal'] = $("input.ongkir-jarak-minimal").val();
        data['tarif_minimal'] = $("input.ongkir-tarif-minimal").val();
        data['tarif_normal'] = $("input.ongkir-tarif-normal").val();
        data['action'] = "AjaxUpdateTarifKirim";*/
        var data = {
	      'action'   : 'AjaxUpdateTarifKirim',
	      'id' : $("input.ongkir-id").val(),
	      'jarak_minimal' : $("input.ongkir-jarak-minimal").val(),
	      'tarif_minimal' : $("input.ongkir-tarif-minimal").val(),
	      'tarif_normal' : $("input.ongkir-tarif-normal").val()
	    }
        //alert(data['tarif_normal']);
        $.post(ajax_one_express.ajaxurl, data, function( response){
        		var result = jQuery.parseJSON(response);
        		if( result.status == true){
        			$(".ongkir-jarak-minimal").html(data['jarak_minimal']);
        			$(".ongkir-tarif-minimal").html(data['tarif_minimal']);
        			$(".ongkir-tarif-normal").html(data['tarif_normal']);
        			$(".ongkir-id").html(data['id']);

        			$("#modal-update-tarif-kirim").modal("hide");
        		}else{
        			
        		}
        	});
    });
});
</script>