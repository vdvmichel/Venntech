<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel_s">
                    <div class="panel-body ">
                        <a href="/admin/venntech/type_kortingen/edit">
                            <button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('add_new', _l('discount_percentage')); ?></button>
                        </a>
                        <hr class="hr-panel-heading">
                        <br/>
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>
                        <?php
                        // render datatable initializes the structure of the table without data..
                        render_datatable(array(
                            '#',
                            _l('name'),
                            _l('discount_percentage'),
                            _l('is_voor_btw'),
                            _l('actions'),
                        ), 'type_kortingen');
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
        initDataTable('.table-type_kortingen', window.location.href + "/table", [0, "desc"]);
    });
</script>
</body>
</html>
