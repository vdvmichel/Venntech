<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel_s">
                    <div class="panel-body ">
                        <a href="/admin/venntech/plaatsing_datums/edit">
                            <button type="button" class="btn btn-info import btn-import-submit"><?php echo _l('add_new', _l('plaatsing datum')); ?></button>
                        </a>
                        <hr class="hr-panel-heading">
                        <h4 class="no-margin"><?php echo $title.'s'; ?></h4>
                        <br/>
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>
                        <?php
                        // render datatable initializes the structure of the table without data..
                        render_datatable(array(
                            '#',
                            _l('name'),
                            _l('datum'),
                            _l('client'),


                        ), 'plaatsing_datums');
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
        initDataTable('.table-plaatsing_datums', window.location.href + "/table");
    });
</script>
</body>
</html>
