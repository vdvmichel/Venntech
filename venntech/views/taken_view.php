<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel_s">
                    <div class="panel-body ">
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>
                        <?php
                        // render datatable initializes the structure of the table without data..
                        render_datatable(array(
                            '#',
                            _l('name'),
                            _l('task_assigned'),
                            _l('tags'),
                            _l('datum'),
                        ), 'taken');
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
        initDataTable('.table-taken', window.location.href + "/table");
    });
</script>
</body>
</html>
