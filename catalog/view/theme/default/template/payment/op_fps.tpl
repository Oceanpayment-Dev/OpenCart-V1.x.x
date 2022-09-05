<div class="buttons">
<div class="right" >
<input id="button-confirm" class="button" type="button" value="<?php echo $button_confirm; ?>" >
</div>
</div>
<script type="text/javascript"><!--
	$("#button-confirm").bind("click",function(){
		$.ajax({
			type: "GET",
			url: "index.php?route=payment/op_fps/confirm",
			beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#button-confirm').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		        },
			success: function()
			{
			location = 'index.php?route=payment/op_fps/op_fps_form';
			}
		});
	});
//--></script>
