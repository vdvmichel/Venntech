<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php

if (!isset($items) || !isset($items_extra)) {
    exit('No item is set');
}

// initialize style classes
$existing_image_class = 'col-md-4';
$input_file_class = 'col-md-8';
if (empty($items_extra->image_path)) {
    $existing_image_class = 'col-md-12';
    $input_file_class = 'col-md-12';
}

?>

<div id="wrapper">
    <div class="content ">
        <div class="row ">

            <?php echo form_open_multipart('/admin/venntech/producten/edit'); ?>
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">

                        <!-- header with title -->
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading"/>
                        <div class="row">
                            <div class="col-md-12">

                            <?php echo render_select('items[group_id]', $groups, ['id', 'name'], _l('item_group_name'), $items->group_id, ['required' => true]); ?>
                                <?php echo render_input('items[description]', _l('invoice_item_add_edit_description'), $items->description, "text", ['required' => true, 'maxLength' => 255]); ?>
                                <?php echo render_textarea('items[long_description]', _l('invoice_item_long_description'), $items->long_description, ['maxLength' => 1023]); ?>
                                <?php echo render_input('items[rate]', _l('invoice_item_add_edit_rate_currency'), $items->rate, "number", ['required' => true, 'step' => 'any']); ?>
                                
                                <?php echo render_input('items_extra[inkoopprijs]', _l('inkoopprijs' ), $items_extra->inkoopprijs, "number", ['step' => 'any']); ?>
                                <?php echo render_input('items_extra[aanbevolen_verkoopprijs]', _l('aanbevolen_verkoopprijs' ), $items_extra->aanbevolen_verkoopprijs, "number", ['step' => 'any']); ?>
                                <?php echo render_input('items_extra[transport_prijs]', _l('transport_prijs' ), $items_extra->transport_prijs, "number", ['step' => 'any']); ?>

                                <?php echo render_input('items_extra[kilo_watt_piek]', _l('kilo_watt_piek'), $items_extra->kilo_watt_piek, "number", ['step' => 'any']); ?>
                                <?php echo render_input('items_extra[kilo_watt_uur]', _l('kilo_watt_uur'), $items_extra->kilo_watt_uur, "number", ['step' => 'any']); ?>
                                <?php echo render_input('items_extra[gewicht]', _l('gewicht' ), $items_extra->gewicht, "number", ['step' => 'any']); ?>


                                <?php echo render_textarea('items_extra[estimate_description]', _l('estimate_description'), $items_extra->estimate_description, ['maxLength' => 1023]); ?>
                                <?php echo render_textarea('items_extra[technical_description]', _l('technical_description'), $items_extra->technical_description, ['maxLength' => 1023]); ?>
                                <?php render_yes_no_option_venntech('items_extra[forfait]', _l('forfait'), $items_extra->forfait); ?>


                                <!-- IMAGE Start -->
                                <?php if (!empty($items_extra->image_path)) { ?>
                                    <div class="<?php echo htmlspecialchars($existing_image_class); ?>">
                                        <div class="existing_image">
                                            <label class="control-label"><?php echo _l('existing_image'); ?></label><br/>
                                            <a href="<?php echo base_url('modules/' . VENNTECH_MODULE_NAME . '/uploads/product/' . $items_extra->image_path); ?>" data-lightbox="gallery">
                                                <img src="<?php echo base_url('modules/' . VENNTECH_MODULE_NAME . '/uploads/product/' . $items_extra->image_path); ?>" class="img img-responsive img-thumbnail zoom"/>
                                            </a>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="<?php echo htmlspecialchars($input_file_class); ?>">
                                    <div class="attachment">
                                        <div class="form-group">
                                            <label for="attachment" class="control-label"><?php echo _l('image'); ?></label>
                                            <input type="file" extension="png,jpg,jpeg,gif" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="product" id="product">
                                        </div>
                                    </div>
                                </div>
                                <!-- IMAGE End -->

                                <?php echo form_hidden('id', $items_extra->id); ?>
                                <?php echo form_hidden('itemid', $items->itemid); ?>
                            </div>
                        </div>

                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/producten" role="button"><?php echo _l('cancel'); ?></a>

                            <?php if( ($edit_type == "edit" && staff_can('edit', FEATURE_PRODUCTEN))
                                || ($edit_type == "create" && staff_can('create', FEATURE_PRODUCTEN)) ) { ?>
                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                            <?php } ?>

                        </div>

                    </div>
                </div>
            </div>

            <?php echo form_close(); ?>

        </div>
    </div>
</div>
<?php
init_tail();
?>
<script type="text/javascript">
    $(function () {
        appValidateForm($('form'), {
            'items[group_id]': 'required',
            'items[description]': 'required',
            'items[rate]': 'required'
        });
    });
</script>
<script type="text/javascript">
    function calculate_price() {
        var transport_prijs = parseFloat(document.getElementById('transport_prijs').value);
        var inkoopprijs = parseFloat(document.getElementById('inkoopprijs').value);
        if (isNaN(transport_prijs)) {
            transport_prijs = 0;
        }
        if (isNaN(inkoopprijs)) {
            inkoopprijs = 0;
        }
        var selling_price = (inkoopprijs + transport_prijs) * 1.25;
        document.getElementById('aanbevolen_verkoopprijs').value = aanbevolen_verkoopprijs.toFixed(2);
    }
</script>
</body>
</html>
