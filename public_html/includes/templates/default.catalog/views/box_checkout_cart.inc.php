<div id="checkout-cart" class="panel panel-info">
  <div class="panel-heading">
    <h2 class="panel-title"><?php echo language::translate('title_shopping_cart', 'Shopping Cart'); ?></h2>
  </div>

  <div class="panel-body table-responsive">
  <table class="items table table-striped data-table" style="width: 100%;">
    <thead>
      <tr class="item">
        <th><?php echo language::translate('title_item', 'Item'); ?></th>
        <th><?php echo language::translate('title_name', 'Name'); ?></th>
        <th><?php echo language::translate('title_options', 'Options'); ?></th>
        <th><?php echo language::translate('title_price', 'Price'); ?></th>
        <th><?php echo language::translate('title_quantity', 'Quantity'); ?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $key => $item) { ?>
      <tr class="item">
        <td><a href="<?php echo htmlspecialchars($item['link']); ?>" class="image-wrapper shadow"><img src="<?php echo htmlspecialchars($item['thumbnail']); ?>" height="48" /></a></td>
        <td><a href="<?php echo htmlspecialchars($item['link']); ?>" style="color: inherit;"><strong><?php echo $item['name']; ?></strong></a><br /><?php echo catalog::product($item['product_id'])->code; ?></td>
        <td><?php if (!empty($item['options'])) echo '<p>'. implode('<br />', $item['options']) .'</p>'; ?></td>
        <td><?php echo currency::format(tax::get_price($item['price'], $item['tax_class_id'])); ?></td>
        <td>
          <div class="form-inline">
            <?php if (!empty($item['quantity_unit']['name'])) { ?>
            <div class="input-group" style="max-width: 150px;">
              <?php echo !empty($item['quantity_unit']['decimals']) ? functions::form_draw_decimal_field('item['.$key.'][quantity]', $item['quantity'], $item['quantity_unit']['decimals'], 0, null) : functions::form_draw_number_field('item['.$key.'][quantity]', $item['quantity'], 0, null); ?>
              <span class="input-group-addon"><?php echo $item['quantity_unit']['name']; ?></span>
            </div>
            <?php } else { ?>
              <?php echo !empty($item['quantity_unit']['decimals']) ? functions::form_draw_decimal_field('item['.$key.'][quantity]', $item['quantity'], $item['quantity_unit']['decimals'], 0, null) : functions::form_draw_number_field('item['.$key.'][quantity]', $item['quantity'], 0, null, 'style="max-width: 150px;"'); ?>
            <?php } ?>
            <?php echo functions::form_draw_button('update_cart_item', array($key, functions::draw_fonticon('fa-refresh')), 'submit', 'title="'. htmlspecialchars(language::translate('title_update', 'Update')) .'" formnovalidate'); ?>
          </div>
        </td>
        <td><?php echo functions::form_draw_button('remove_cart_item', array($key, functions::draw_fonticon('fa-trash')), 'submit', 'class="btn btn-danger" title="'. htmlspecialchars(language::translate('title_remove', 'Remove')) .'" formnovalidate'); ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  </div>
  
  <div class="panel-footer text-right" style="font-size: 1.25em;">
    <?php echo language::translate('title_subtotal', 'Subtotal'); ?>: <strong><?php echo currency::format(cart::$total['value']); ?></strong>
  </div>
</div>