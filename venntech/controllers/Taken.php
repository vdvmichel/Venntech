<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Taken extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('taken_model');
        $this->load->model('tasks_model');
    }


    public function index()
    {
        if (!staff_can('view', FEATURE_TAKEN)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('tasks'));
        }

        $data['title'] = _l('tasks');
        $this->load->view('venntech/taken_view', $data);
    }

    public function table()
    {
        if (!staff_can('view', FEATURE_TAKEN)) {
            ajax_access_denied();
        }
        $this->app->get_table_data(module_views_path('venntech', 'tables/taken_table'));
    }

    public function edit($id = '')
    {
        $inspectie = $this->taken_model->get_inspectie_by_task_id($id);
        $opleverdocument = $this->taken_model->get_opleverdocument_by_task_id($id);
        $plaatsing_datum = $this->taken_model->get_plaatsing_datum_by_task_id($id);

        if (isset($inspectie)) {
            redirect(admin_url('venntech/inspectie_rapporten/edit/' . $inspectie->id));
        } else if (isset($opleverdocument)) {
            redirect(admin_url('venntech/opleverdocumenten/edit/' . $opleverdocument->id));
        } else if (isset($plaatsing_datum)) {
            redirect(admin_url('venntech/plaatsing_datums/edit/' . $plaatsing_datum->id));
        }
        redirect(admin_url('venntech/taak/edit/' . $id));
    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_TAKEN)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('tasks'));
        }


        // delete rapport with id
        $this->taken_model->delete($id);

        redirect(admin_url('venntech/taken'));
    }

    public function change_staffid($task_id)
    {
        if ($task_id > 0) {
            $data = $this->input->post();


            $task_staffid = $data['task_staffid'];
            $sql = 'UPDATE ' . db_prefix() . 'task_assigned SET staffid = ' . $task_staffid . ' WHERE taskid = ' . $task_id . ';';
            $success = $this->db->query($sql);

            if ($success) {

                $task = $this->tasks_model->get($task_id);

                $html = '';

                if ($task->billed == 0) {
                    $is_assigned = $task->current_user_is_assigned;
                    if (!$this->tasks_model->is_timer_started($task->id)) {

                        $tooltip_html = '';
                        if (!$is_assigned) {
                            $tooltip_html = 'data-toggle="tooltip" data-title="' . _l('task_start_timer_only_assignee') . '"';
                        }

                        if (!$is_assigned || $task->status == Tasks_model::STATUS_COMPLETE) {
                            $extra_class = ' disabled btn-default';
                        } else {
                            $extra_class = ' btn-success';
                        }

                        $html .= '<p ' . $tooltip_html . '>';
                        $html .= '<a href="#" class="mbot10 btn ' . $extra_class . '" onclick="timer_action_venntech(this, ' . $task->id . '); return false;">';
                        $html .= '<i class="fa fa-clock-o"></i> ' . _l('task_start_timer').' ';
                        $html .= '</a></p>';

                    } else {

                        if (!$is_assigned) {
                            $extra_class = ' disabled';
                        } else {
                            $extra_class = '';
                        }

                        $popup_btn = '<button type="button" onclick="timer_action_venntech(this, ' . $task->id . ', ' . $this->tasks_model->get_last_timer($task->id)->id . ');" class="btn btn-info btn-xs">' . _l('save') . '</button>';
                        $popup = render_textarea('timesheet_note'). ' ' . $popup_btn;
                        $popup = htmlspecialchars($popup);
                        $html .= '<p>';
                        $html .= '<a href="#" data-toggle="popover"';
                        $html .= ' data-placement="bottom"';
                        $html .= ' data-html="true" data-trigger="manual"';
                        $html .= ' data-title="' . _l('note') . '"';
                        $html .= ' data-content="' . $popup .'"';
                        $html .= ' class="btn mbot10 btn-danger' . $extra_class . '" onclick="return false;">';
                        $html .= '<i class="fa fa-clock-o"></i> ' . _l('task_stop_timer').' ';
                        $html .= '</a></p>';
                    }
                }

                $staff = get_staff($task_staffid);
                echo json_encode([
                    'success' => true,
                    'message' => _l('task_checklist_assigned', $staff->firstname . ' ' . $staff->lastname),
                    'html' => $html
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Een fout tijdens toewijzen van de taak'
                ]);
            }

            die();
        }
    }
}
