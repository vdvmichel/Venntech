<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <?php echo form_open('/admin/venntech/settings_taak/edit', array('id' => 'settings_taak-form')); ?>
                    <?php echo form_hidden('settings_taak[id]', $item->settings_taak->id); ?>
                    <div class="panel-body ">
                        <?php echo form_open('/admin/venntech/settings_taak'); ?>
                        <?php if (staff_can('edit', FEATURE_SETTINGS)) {
                            echo "<div class='form-group'>
                                    <button type='submit' class='btn btn-info'>" . _l('save') . "</button>
                                </div>";
                        } ?>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <?php
                        echo render_input('settings_taak[name]', _l('name'),$item->settings_taak->name, "text", ['required' => true, 'maxLength' => 255]);
                        echo render_input('settings_taak[tag_name]', _l('tag'),$item->settings_taak->tag_name, "text", ['required' => true, 'maxLength' => 255]);
                        echo render_select('settings_taak[staffid]', $members, ['id', 'name'], _l('staff'), $item->settings_taak->staffid);
                        ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php
init_tail();
?>
<script>
    $(function () {
        appValidateForm($('form'), {
            'settings_taak[name]': "required",
            'settings_taak[tag_name]': "required",
            'settings_taak[staffid]': "required",
        });
        initDataTable('.table-producten', window.location.href + "/table");
    });
</script>
</body>
</html>
