<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
// initialize style classes
$existing_image_class = 'col-md-4';
$input_file_class = 'col-md-8';

$disabled_taak = !isset($task->id) || $task->id ==  '0' ;

init_head(); ?>


<div id="wrapper">
    <?php echo form_open('/admin/venntech/plaatsing_datums/edit', array('id' => 'plaatsing-datum-form')); ?>
    <?php echo form_hidden('plaatsing_datum[id]', $item->plaatsing_datum->id); ?>
    <?php if (isset($task)) {
        echo form_hidden('plaatsing_datum[taskid]', $task->id);
    } ?>

    <div class="content ">

        <?php include 'top_task_view.php' ?>
        <?php if ($disabled_taak) { ?>
            <div class="col-lg-12">
                <div class="panel_s da">
                    <div class="panel-body text-danger">
                        <h3 class=" ">Opgelet!</h3>
                        <p> Deze opdracht is zichtbaar enkel voor admin omdat het niet verbonden is aan een taak. </p>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-2 text-left">

            </div>
        </div>
        <div class="row ">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open('/admin/venntech/plaatsing_datums/edit', array('id' => 'plaatsing-datum-form')); ?>
                        <?php echo form_hidden('plaatsing_datum[id]', $item->plaatsing_datum->id); ?>
                        <?php if (isset($plaatsing_datum->taskid)) {
                            echo form_hidden('plaatsing_datum[taskid]', $item->plaatsing_datum->taskid);
                        } ?>
                        <h4 class="no-margin"><?php echo $title; ?></h4>
                        <br/>

                        <div class="f_client_id">
                            <div class="form-group select-placeholder">
                                <label for="clientid"
                                       class="control-label"><?php echo _l('estimate_select_customer'); ?> </label>
                                <select id="clientid" name="clientid" data-live-search="true"
                                        data-width="100%"
                                        class="ajax-search<?php if (empty($item->plaatsing_datum->clientid)) {
                                            echo ' customer-removed';
                                        } ?>"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                                        required>
                                    <?php
                                    $selected = $item->plaatsing_datum->clientid;
                                    if ($selected != '') {
                                        $rel_data = get_relation_data('customer', $selected);
                                        $rel_val = get_relation_values($rel_data, 'customer');
                                        echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>

                        <?php echo render_select('plaatsing_datum[staffid]', $members, ['id', 'name'], _l('staff'), $item->plaatsing_datum->staffid, ['onchange' => 'changeCalendar(this)', 'required' => 'true']); ?>

                        <?php echo render_date_input('plaatsing_datum[datum]', _l('task_single_start_date'), $item->plaatsing_datum->datum, ['required' => 'true']); ?>

                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/plaatsing_datums"
                               role="button"><?php echo _l('cancel'); ?></a>

                            <?php if (($edit_type == "edit" && staff_can('edit', FEATURE_PLAATSING_DATUM))
                                || ($edit_type == "create" && staff_can('create', FEATURE_PLAATSING_DATUM))) { ?>

                                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>

                                <?php if (isset($task->id) && $task->status != 5) { ?>
                                    <button type="submit" class="btn btn-success" name="complete"
                                            value="Complete"><?php echo _l('task_single_mark_as_complete'); ?></button>
                                <?php } ?>

                            <?php } ?>
                        </div>


                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="dt-loader hide"></div>
                        <div id="plaatsing_datum_calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<?php
init_tail();
?>

<script>
    function initializeCalender(staffid) {
        var settings = {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable: false,
            dayMaxEventRows: parseInt(app.options.calendar_events_limit) + 1,
            views: {
                day: {
                    dayMaxEventRows: false
                }
            },
            initialView: 'dayGridWeek',
            moreLinkClick: function (info) {
                calendar.gotoDate(info.date)
                calendar.changeView('dayGridDay');

                setTimeout(function () {
                    $('.fc-popover-close').click();
                }, 250)
            },
            loading: function (isLoading, view) {
                !isLoading ? $('.dt-loader').addClass('hide') : $('.dt-loader').removeClass('hide');
            },
            direction: (isRTL == 'true' ? 'rtl' : 'ltr'),
            eventStartEditable: false,
            firstDay: parseInt(app.options.calendar_first_day),
            events: function (info, successCallback, failureCallback) {
                return $.getJSON(admin_url + 'venntech/plaatsing_datums/get_calendar_data', {
                    start: info.startStr,
                    end: info.endStr,
                    calendar_staffid: staffid
                }).then(function (data) {
                    successCallback(data.map(function (e) {
                        return $.extend({}, e, {
                            start: e.start || e.date,
                            end: e.end || e.date
                        });
                    }));
                });
            },
            eventDidMount: function (data) {
                var $el = $(data.el);
                $el.attr('title', data.event.extendedProps._tooltip);
                $el.attr('onclick', data.event.extendedProps.onclick);
                $el.attr('data-toggle', 'tooltip');
            },
        }
        var calendar = new FullCalendar.Calendar(document.getElementById('plaatsing_datum_calendar'), settings);
        calendar.render();
    }

    $(function () {

        appValidateForm($('form'), {
            'plaatsing_datum[name]': "required",
            'clientid': "required",
            'plaatsing_datum[staffid]': "required",
            'plaatsing_datum[datum]': "required",
        });

        let staffid = "<?php echo $item->plaatsing_datum->staffid ?>";
        if ($('#plaatsing_datum_calendar').length) {
            initializeCalender(staffid);
        }
    });

    function changeCalendar(selectObject) {
        var staffid = selectObject.value;
        initializeCalender(staffid);
    }

</script>

</body>
</html>
