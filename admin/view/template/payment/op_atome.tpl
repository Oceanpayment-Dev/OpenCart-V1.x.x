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
              <td><input type="text" name="op_atome_account" value="<?php echo $op_atome_account; ?>" />
                <?php if ($error_account) { ?>
                <span class="error"><?php echo $error_account; ?></span>
                <?php } ?></td>
            </tr>
	     <tr>
              <td><span class="required">*</span> <?php echo $entry_terminal; ?></td>
              <td><input type="text" name="op_atome_terminal" value="<?php echo $op_atome_terminal; ?>" />
                <?php if ($error_terminal) { ?>
                <span class="error"><?php echo $error_terminal; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> <?php echo $entry_securecode; ?></td>
              <td><input type="text" name="op_atome_securecode" value="<?php echo $op_atome_securecode; ?>" />
                <?php if ($error_securecode) { ?>
                <span class="error"><?php echo $error_securecode; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><?php echo $entry_transaction; ?></td>
              <td><select name="op_atome_transaction">
                  <?php if ($op_atome_transaction == $text_pay) { ?>
                  <option value="<?php echo $text_pay; ?>" selected="selected"><?php echo $text_pay; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $text_pay; ?>"><?php echo $text_pay; ?></option>
                  <?php } ?>

                  <?php if ($op_atome_transaction == $text_test) { ?>
                  <option value="<?php echo $text_test; ?>" selected="selected"><?php echo $text_test; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $text_test; ?>"><?php echo $text_test; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
              <tr>
                  <td><?php echo $entry_pay_mode; ?></td>
                  <td><select name="op_atome_pay_mode">
                          <?php if ($op_atome_pay_mode == 1) { ?>
                          <option value="1" selected="selected"><?php echo $text_pay_iframe; ?></option>
                          <?php } else { ?>
                          <option value="1"><?php echo $text_pay_iframe; ?></option>
                          <?php } ?>

                          <?php if ($op_atome_pay_mode == 0) { ?>
                          <option value="0" selected="selected"><?php echo $text_pay_redirect; ?></option>
                          <?php } else { ?>
                          <option value="0"><?php echo $text_pay_redirect; ?></option>
                          <?php } ?>
                      </select></td>
              </tr>
            	
            <tr>
              <td><?php echo $entry_default_order_status; ?></td>
              <td><select name="op_atome_default_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $op_atome_default_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
    		<tr>
              <td><?php echo $entry_success_order_status; ?></td>
              <td><select name="op_atome_success_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $op_atome_success_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
    		<tr>
              <td><?php echo $entry_failed_order_status; ?></td>
              <td><select name="op_atome_failed_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $op_atome_failed_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_pending_order_status; ?></td>
              <td><select name="op_atome_pending_order_status_id">
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $op_atome_pending_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_geo_zone; ?></td>
              <td><select name="op_atome_geo_zone_id">
                  <option value="0"><?php echo $text_all_zones; ?></option>
                  <?php foreach ($geo_zones as $geo_zone) { ?>
                  <?php if ($geo_zone['geo_zone_id'] == $op_atome_geo_zone_id) { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_code; ?></td>
              <td><select name="op_atome_code">
                  <?php if ($op_atome_code) { ?>
                  <option value="1" selected="selected"><?php echo $text_code_online; ?></option>
                  <option value="0"><?php echo $text_code_local; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_code_online; ?></option>
                  <option value="0" selected="selected"><?php echo $text_code_local; ?></option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><?php echo $entry_status; ?></td>
              <td><select name="op_atome_status">
                  <?php if ($op_atome_status) { ?>
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
              <td><input type="text" name="op_atome_sort_order" value="<?php echo $op_atome_sort_order; ?>" size="1" /></td>
            </tr>
          </table>
        </form>
      </div>
  </div>
</div>
<?php echo $footer; ?>
