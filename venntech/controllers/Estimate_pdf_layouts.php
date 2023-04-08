<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Estimate_pdf_layouts extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('project_template_model');
        $this->load->model('estimate_template_model');
        $this->load->model('estimate_template_element_model');
        $this->load->model('estimate_template_items_model');
        $this->load->model('estimate_pdf_layout_model');
        $this->load->model('estimates_extra_model');
    }

    public function index()
    {
        if (!staff_can('view', FEATURE_ESTIMATE_TEMPLATES)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('estimate_templates'));
        }
        $data['title'] = _l('estimate_pdf_layouts');

        $this->load->view('venntech/estimate_pdf_layouts_view', $data);
    }

    public function edit($estimate_template_id)
    {
        if (!staff_can('edit', FEATURE_ESTIMATE_TEMPLATES)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('estimate_templates'));
        }

        if ($this->input->post()) {

            $data = $this->input->post();
            $data['estimate_template_id'] = $estimate_template_id;
            $layout = $this->estimate_pdf_layout_model->get_by_estimate_template_id($estimate_template_id);

            $data['pre_page_1'] = htmlspecialchars($this->input->post('pre_page_1', false));
            $data['pre_page_2'] = htmlspecialchars($this->input->post('pre_page_2', false));
            $data['pre_page_3'] = htmlspecialchars($this->input->post('pre_page_3', false));
            $data['pre_page_4'] = htmlspecialchars($this->input->post('pre_page_4', false));
            $data['pre_page_5'] = htmlspecialchars($this->input->post('pre_page_5', false));

            $data['post_page_1'] = htmlspecialchars($this->input->post('post_page_1', false));
            $data['post_page_2'] = htmlspecialchars($this->input->post('post_page_2', false));
            $data['post_page_3'] = htmlspecialchars($this->input->post('post_page_3', false));
            $data['post_page_4'] = htmlspecialchars($this->input->post('post_page_4', false));
            $data['post_page_5'] = htmlspecialchars($this->input->post('post_page_5', false));

            if(isset($layout)){
                $data['id'] = $layout->id;
                $success = $this->estimate_pdf_layout_model->edit($data);
            }else{
                $success = $this->estimate_pdf_layout_model->add($data);
            }

            if($success){
                set_alert('success', _l('updated_successfully', _l('estimate_template')));
            }

            redirect(admin_url('venntech/estimate_templates'));

        }else {
            $data['title'] = _l('estimate_pdf_layouts');
            $data['estimate_template'] = $this->estimate_template_model->get($estimate_template_id);
            $layout = $this->estimate_pdf_layout_model->get_by_estimate_template_id($estimate_template_id);

            if(!isset($layout)){
                $layout = new stdClass();
                $layout->estimate_template_id = $estimate_template_id;
                $layout->pre_page_1 = '';
                $layout->pre_page_2 = '';
                $layout->pre_page_3 = '';
                $layout->pre_page_4 = '';
                $layout->pre_page_5 = '';

                $layout->post_page_1 = '';
                $layout->post_page_2 = '';
                $layout->post_page_3 = '';
                $layout->post_page_4 = '';
                $layout->post_page_5 = '';
            } else {
                $layout->pre_page_1 = htmlspecialchars_decode($layout->pre_page_1);
                $layout->pre_page_2 = htmlspecialchars_decode($layout->pre_page_2);
                $layout->pre_page_3 = htmlspecialchars_decode($layout->pre_page_3);
                $layout->pre_page_4 = htmlspecialchars_decode($layout->pre_page_4);
                $layout->pre_page_5 = htmlspecialchars_decode($layout->pre_page_5);

                $layout->post_page_1 = htmlspecialchars_decode($layout->post_page_1);
                $layout->post_page_2 = htmlspecialchars_decode($layout->post_page_2);
                $layout->post_page_3 = htmlspecialchars_decode($layout->post_page_3);
                $layout->post_page_4 = htmlspecialchars_decode($layout->post_page_4);
                $layout->post_page_5 = htmlspecialchars_decode($layout->post_page_5);
            }

            $data['layout'] = $layout;

            $data['available_merge_fields'] = $this->app_merge_fields->all();

            $template = new stdClass();
            $template->type = 'estimate';
            $template->slug = 'estimate-send-to-client';
            $data['template'] = $template;

            $this->load->view('venntech/estimate_pdf_layouts_view', $data);
        }
    }

}
