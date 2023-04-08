<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="form-group">
                            <a href="/admin/venntech/settings_taak/edit">
                                <button type="button"
                                        class="btn btn-info import btn-import-submit"><?php echo _l('add_new', _l('task')); ?></button>
                            </a>
                        </div>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>
                        <?php
                        // render datatable initializes the structure of the table without data..
                        render_datatable(array(
                            '#',
                            _l('custom_field_add_edit_order'),
                            _l('name'),
                            _l('tag'),
                            _l('task_single_assignees'),
                            _l('actions')
                        ), 'instellingen_taak');
                        ?>
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
        initDataTable('.table-instellingen_taak', window.location.href + "/table", undefined, undefined, undefined, [ 1, "asc" ]);
    });
</script>
</body>
</html>
