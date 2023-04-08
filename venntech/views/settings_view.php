<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body ">
                        <?php echo form_open('/admin/venntech/settings'); ?>
                        <?php if (staff_can('edit', FEATURE_SETTINGS)) {
                            echo "<div class='form-group'>
                                    <button type='submit' class='btn btn-info'>" . _l('save') . "</button>
                                </div>";
                        } ?>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <?php
                        echo render_input('margin_of_profit', _l('margin_of_profit'), $margin_of_profit,'number', ['required' => true, 'step' => 'any']);
                        ?>
                        <div class="alert alert-warning" role="alert">
                            Let op de systeem default winstmarge is %25, alle producten die toegevoegd worden moeten met deze winstmarge ingegeven worden.
                        </div>

                        <?php echo render_select('inspectie_rapport_staffid', $members, ['id', 'name'], 'task_inspectie_rapport', $inspectie_rapport_staffid, ['required' => true]); ?>

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
            margin_of_profit: "required",
            inspectie_rapport_staffid: "required",
        });
        initDataTable('.table-producten', window.location.href + "/table");
    });
</script>
</body>
</html>
