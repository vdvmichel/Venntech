<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content ">
        <div class="row ">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open('/admin/venntech/type_kortingen/edit', array('id' => 'type-kortingen-form')); ?>
                        <?php echo form_hidden('type_korting[id]', $item->type_korting->id); ?>

                        <h4 class="no-margin"><?php echo $title; ?></h4>

                        <?php echo render_input('type_korting[name]', _l('name'),$item->type_korting->name, "text", ['required' => true, 'maxLength' => 255]); ?>
                        <?php echo render_input('type_korting[discount_percentage]', _l('discount_percentage'),$item->type_korting->discount_percentage, "number", ['required' => true, 'maxLength' => 11]); ?>
                        <?php render_yes_no_option_venntech('type_korting[is_voor_btw]', _l('is_voor_btw'), $item->type_korting->is_voor_btw); ?>

                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/type_kortingen"
                               role="button"><?php echo _l('cancel'); ?></a>

                            <?php if (($edit_type == "edit" && staff_can('edit', FEATURE_SETTINGS))
                                || ($edit_type == "create" && staff_can('create', FEATURE_SETTINGS))) { ?>
                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                            <?php } ?>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function () {
        appValidateForm($('form'), {
            'type_korting[name]': "required",
            'type_korting[discount_percentage]': "required",
        });
    });
</script>

</body>
</html>