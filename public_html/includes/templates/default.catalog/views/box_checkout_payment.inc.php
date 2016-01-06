<div id="checkout-payment">
  <h2><?php echo language::translate('title_payment', 'Payment'); ?></h2>
  
  <ul id="payment-options" class="list-unstyled">
<?php
  foreach ($options as $module) {
    foreach ($module['options'] as $option) {
?>
    <li class="option well<?php echo ($module['id'].':'.$option['id'] == $selected['id']) ? ' active' : false; ?>">
    <?php echo functions::form_draw_form_begin('payment_form', 'post') . functions::form_draw_hidden_field('selected_payment', $module['id'].':'.$option['id'], $selected['id']); ?>
      <div class="thumbnail">
        <img src="<?php echo functions::image_thumbnail(FS_DIR_HTTP_ROOT . WS_DIR_HTTP_HOME . $option['icon'], 200, 70, 'FIT_ONLY_BIGGER_USE_WHITESPACING'); ?>" />
      </div>
      <h3 class="title"><?php echo $module['title']; ?></h3>
      <div class="name"><?php echo $option['name']; ?></div>
      <p class="description"><?php echo $option['fields'] . $option['description']; ?></p>
      <div class="footer">
        <div class="price"><?php if ($option['cost'] != 0) echo '+ ' . currency::format(tax::get_price($option['cost'], $option['tax_class_id'])); ?></div>
        <div class="select text-right">
<?php
  if ($module['id'].':'.$option['id'] == $selected['id']) {
    if (!empty($option['fields'])) {
      echo functions::form_draw_button('set_payment', language::translate('title_update', 'Update'), 'submit');
    } else {
    echo functions::form_draw_button('set_payment', language::translate('title_selected', 'Selected'), 'submit', 'class="active"');
    }
  } else {
    echo functions::form_draw_button('set_payment', language::translate('title_select', 'Select'), 'submit');
  }
?>
        </div>
      </div>
    <?php echo functions::form_draw_form_end(); ?>
    </li>
<?php
    }
  }
?>
  </ul>
</div>