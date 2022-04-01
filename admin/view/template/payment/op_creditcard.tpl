<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
<div class="box">
      <div class="heading">
        <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
        <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
      </div>
      <div class="content">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>
              <td><span class="required">*</span> <?php echo $entry_account; ?></td>
              <td><input type="text" name="op_creditcard_account" value="<?php echo $op_creditcard_account; ?>" />
                <?php if ($error_account) { ?>
                <span class="error"><?php echo $error_account; ?></span>
                <?php } ?></td>
            </tr>
	     <tr>
              <td><span class="required">*</span> <?php echo $entry_terminal; ?></td>
              <td><input type="text" name="op_creditcard_terminal" value="<?php echo $op_creditcard_terminal; ?>" />
                <?php if ($error_terminal) { ?>
                <span class="error"><?php echo $error_terminal; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_securecode; ?></td>
              <td><input type="text" name="op_creditcard_securecode" value="<?php echo $op_creditcard_securecode; ?>" />
                <?php if ($error_securecode) { ?>
                <span class="error"><?php echo $error_securecode; ?></span>
                <?php } ?></td>
            </tr>
            
            <tr>
              <td><?php echo $entry_3d; ?></td>
              <td><select name="op_creditcard_3d" id="op_creditcard_3d" onchange="is_3d(this.value)">
                  <?php if ($op_creditcard_3d == 1) { ?>
                  <option value="1" selected="selected"><?php echo $text_3d_on; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_3d_on; ?></option>
                  <?php } ?>

                  <?php if ($op_creditcard_3d == 0) { ?>
                  <option value="0" selected="selected"><?php echo $text_3d_off; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_3d_off; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr class="3d_tr">
              <td><?php echo $entry_3d_terminal; ?></td>
              <td><input type="text" name="op_creditcard_3d_terminal" value="<?php echo $op_creditcard_3d_terminal; ?>" />
                <?php if ($error_terminal) { ?>
                <span class="error"><?php echo $error_terminal; ?></span>
                <?php } ?></td>
            </tr>
            <tr class="3d_tr">
              <td><?php echo $entry_3d_securecode; ?></td>
              <td><input type="text" name="op_creditcard_3d_securecode" value="<?php echo $op_creditcard_3d_securecode; ?>" />
                <?php if ($error_securecode) { ?>
                <span class="error"><?php echo $error_securecode; ?></span>
                <?php } ?></td>
            </tr>
            <tr class="3d_tr">
              <td><?php echo $entry_currencies; ?></td>
              <td><select name="op_creditcard_currencies" onchange="show_currency_value(this.value)"> 
                  <option value="0"><?php echo $text_select_currency; ?></option>
                  <?php foreach ($currencies as $currency) { ?>
                  <option value="<?php echo $currency; ?>"><?php echo $currency; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr class="3d_tr">
              <td><?php echo $entry_currencies_value; ?></td>
              <td>
                <?php foreach ($currencies as $currency) { ?>
                <input type="text" class="currencies_value" style="display:none" id="<?php echo $currency; ?>_value" name="op_creditcard_currencies_value[<?php echo $currency; ?>]" value="<?php echo $op_creditcard_currencies_value[$currency]; ?>" />
                <?php } ?></td>
            </tr>
            <tr class="3d_tr">
	            <td><?php echo $entry_countries; ?></td>
	            <td><div class="scrollbox" style="width: 80%;">
	                <?php foreach ($countries as $country) { ?>
	                <div style="width: 40%; float: left;">
	                  <?php if (in_array($country['country_id'], $op_creditcard_country_array)) { ?>
	                  <input type="checkbox" name="op_creditcard_country_array[]" value="<?php echo $country['country_id']; ?>" checked="checked" />
	                  <?php echo $country['name']; ?>
	                  <?php } else { ?>
	                  <input type="checkbox" name="op_creditcard_country_array[]" value="<?php echo $country['country_id']; ?>" />
	                  <?php echo $country['name']; ?>
	                  <?php } ?>
	                </div>
	                <?php } ?>
	              </div>
	              <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
	        </tr>
  
            <tr>
              <td><?php echo $entry_transaction; ?></td>
              <td><select name="op_creditcard_transaction">
                  <?php if ($op_creditcard_transaction == $text_pay) { ?>
                  <option value="<?php echo $text_pay; ?>" selected="selected"><?php echo $text_pay; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $text_pay; ?>"><?php echo $text_pay; ?></option>
                  <?php } ?>

                  <?php if ($op_creditcard_transaction == $text_test) { ?>
                  <option value="<?php echo $text_test; ?>" selected="selected"><?php echo $text_test; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $text_test; ?>"><?php echo $text_test; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
  
  			<tr>
              <td><?php echo $entry_pay_mode; ?></td>
              <td><select name="op_creditcard_pay_mode">
                  <?php if ($op_creditcard_pay_mode == 1) { ?>
                  <option value="1" selected="selected"><?php echo $text_pay_iframe; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_pay_iframe; ?></option>
                  <?php } ?>

                  <?php if ($op_creditcard_pay_mode == 0) { ?>
                  <option value="0" selected="selected"><?php echo $text_pay_redirect; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_pay_redirect; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            	
            <tr>
              <td><?php echo $entry_default_order_status; ?></td>
              <td><select name="op_creditcard_default_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $op_creditcard_default_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
    		<tr>
              <td><?php echo $entry_success_order_status; ?></td>
              <td><select name="op_creditcard_success_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $op_creditcard_success_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
    		<tr>
              <td><?php echo $entry_failed_order_status; ?></td>
              <td><select name="op_creditcard_failed_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $op_creditcard_failed_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_pending_order_status; ?></td>
              <td><select name="op_creditcard_pending_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $op_creditcard_pending_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_geo_zone; ?></td>
              <td><select name="op_creditcard_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $op_creditcard_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="op_creditcard_status">
                  <?php if ($op_creditcard_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_sort_order; ?></td>
              <td><input type="text" name="op_creditcard_sort_order" value="<?php echo $op_creditcard_sort_order; ?>" size="1" /></td>
            </tr>
			
			<tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="op_creditcard_location">
                  <?php if ($op_creditcard_location) { ?>
                  <option value="1" selected="selected"><?php echo $text_show; ?></option>
                  <option value="0"><?php echo $text_hide; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_show; ?></option>
                  <option value="0" selected="selected"><?php echo $text_hide; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
              <td><?php echo $entry_locations; ?></td>
              <td><input type="text" name="op_creditcard_locations" value="<?php echo $op_creditcard_locations; ?>" size="1" /></td>
            </tr>
			<tr>
              <td><?php echo $entry_entity; ?></td>
              <td><select name="op_creditcard_entity">
                  <?php if ($op_creditcard_entity) { ?>
                  <option value="1" selected="selected"><?php echo $text_shows; ?></option>
                  <option value="0"><?php echo $text_hides; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_shows; ?></option>
                  <option value="0" selected="selected"><?php echo $text_hides; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
			<tr>
              <td><?php echo $entry_entitys; ?></td>
              <td><input type="text" name="op_creditcard_entitys" value="<?php echo $op_creditcard_entitys; ?>" size="1" /></td>
            </tr>
			
          </table>
        </form>
      </div>
  </div>
</div>
<script type="text/javascript"><!--
	function show_currency_value(currency){
		$(".currencies_value").hide();
		$("#"+currency+"_value").show();
	}
	function is_3d(val){
		if(val == 1){
			$(".3d_tr").show();
		}else{
			$(".3d_tr").hide();
		}
	}
	
	if($("#op_creditcard_3d").val() == 1){
		$(".3d_tr").show();
	}else{
		$(".3d_tr").hide();
	}
	
//--></script>
<?php echo $footer; ?>
