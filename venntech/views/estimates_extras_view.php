<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="form-group">
                            <a href="/admin/venntech/offertes/edit">
                                <button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('add_new', _l('estimate')); ?></button>
                            </a>
                        </div>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>
                        <?php
                        // render datatable initializes the structure of the table without data..   kolom aanmaken zonder de data...
                        render_datatable(array(
                            //'#',
                            _l('estimate'),
                            _l('invoice_table_amount_heading'),
                            //_l('acs_sales_taxes_submenu'),
                          // Kolom btw bedrag tonen
                            _l('client'),
                            _l('project'),
                            _l('naam_sales_verkoper'),
                            _l('estimate_template_id'),
                            _l('utility_activity_log_dt_date'),
                            _l('tax_rate'),
                            _l('number_of_panels'),
                            _l('estimate_status'),

                        ), 'offertes');
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
        initDataTable('.table-offertes', window.location.href + "/table", undefined, undefined,'',[0,'desc']);

        
    });
</script>
</body>
</html>
