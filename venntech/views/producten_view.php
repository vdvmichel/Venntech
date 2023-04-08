<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="form-group">
                            <a href="/admin/venntech/producten/edit">
                                <button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('add_new', _l('product')); ?></button>
                            </a>
                        </div>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>
                        <?php
                        // render datatable initializes the structure of the table without data..
                        render_datatable(array(
                            '#',
                            _l('item_group_name'),
                            _l('invoice_item_add_edit_description'),
                            _l('kilo_watt_piek'),
                            _l('kilo_watt_uur'),
                            _l('technical_description'),
                            _l('forfait'),
                            _l('invoice_item_add_edit_rate_currency'),
                            _l('image'),
                            _l('active'),
                            _l('actions')
                        ), 'producten');
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
        initDataTable('.table-producten', window.location.href + "/table");
    });
</script>
</body>
</html>
