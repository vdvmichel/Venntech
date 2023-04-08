<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="form-group">
                            <a href="/admin/venntech/opleverdocumenten/edit">
                                <button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('add_new', _l('OPLEVERDOCUMENTEN')); ?></button>
                            </a>
                        </div>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>
                        <?php
                        // render datatable initializes the structure of the table without data..
                        render_datatable(array(
                            '#',
                            _l('name'),
                            _l('client'),
                            _l('utility_activity_log_dt_date'),
                            _l('verval_datum'),
                            _l('totaal'),

                            _l('actions'),
                        ), 'opleverdocumenten');
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
        initDataTable('.table-opleverdocumenten', window.location.href + "/table");
    });
</script>
</body>
</html>
