<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Opleverdocumenten_verbruiksmaterialen extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('opleverdocument_model');
        $this->load->model('opleverdocument_verbruiksmaterialen_model');
        $this->load->model('product_model');
    }

    /* List all available groepen */
    public function index()
    {
        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('opleverdocumenten'));
        }
        $data['title'] = _l('verbruiksmaterialen');
        $this->load->view('venntech/opleverdocument_verbruiksmaterialen_view', $data);
    }

    public function view_table($opleverdocument_id)
    {
        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('opleverdocumenten'));
        }

        $item = new stdClass();
        $item->opleverdocument_id = $opleverdocument_id;
        $item->verbruiksmaterialen = $this->product_model->get_active_items_combobox_by_groupid(get_option('verbruiksmateriaal_id'));

        $data['title'] = _l('verbruiksmaterialen');
        $data['item'] = $item;
        $this->load->view('venntech/opleverdocument_verbruiksmaterialen_view', $data);
    }

    public function table($id = '')
    {
        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            ajax_access_denied();
        }
        if (isset($id) && $id != '') {
            $sTable = db_prefix() . 'pc_opleverdocument_verbruiksmaterialen';
            $items = db_prefix() . 'items';

            $aColumns = [
                $sTable . '.id as id',
                $items . '.description as description',
                $sTable . '.aantal as aantal',
                $items . '.rate as rate'];

            $sIndexColumn = 'id';
            $join = ['JOIN ' . $items . ' ON ' . $sTable . '.item_id=' . $items . '.id'];
            $where = ['AND opleverdocument_id=' . $id];

            $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where);
            $output = $result['output'];
            $rResult = $result['rResult'];
            foreach ($rResult as $aRow) {

                // init empty array
                $row = [];

                $row[] = $aRow['id'];
                $row[] = $aRow['description'];
                $row[] = $aRow['aantal'];
                $row[] = $aRow['rate'] * $aRow['aantal'];

                // add to next index the data, no need to specify index the item will be added to the end.
                $actions = icon_btn('venntech/opleverdocumenten_verbruiksmaterialen/delete/' . $aRow['id'], 'remove', 'btn-danger _delete');
                $row[] = $actions;
                $output['aaData'][] = $row;
            }
            echo json_encode($output);
            die();
        }
    }

    public function add()
    {
        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('verbruiksmaterialen'));
        }
        if ($this->input->post()) {
            $data = $this->input->post();

            $opleverdocument_id = $data['opleverdocument_id'];
            $item_id = $data['item_id'];
            $aantal = $data['aantal'];

            $new_item = [];
            $new_item['opleverdocument_id'] = $opleverdocument_id;
            $new_item['item_id'] = $item_id;
            $new_item['aantal'] = $aantal;

            $success = $this->opleverdocument_verbruiksmaterialen_model->add($new_item);
            if ($success) {
                set_alert('success', _l('added_successfully', _l('verbruiksmateriaal')));
            }
            redirect(admin_url('venntech/opleverdocumenten_verbruiksmaterialen/view_table/' . $opleverdocument_id));

        }
    }

    public function add_item()
    {
        if (!staff_can('view', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('view') . ' VENNTECH ' . _l('verbruiksmaterialen'));
        }
        if ($this->input->post()) {
            $data = $this->input->post();

            $opleverdocument_id = $data['opleverdocument_id'];
            $item_id = $data['item_id'];
            $aantal = $data['aantal'];

            $new_item = [];
            $new_item['opleverdocument_id'] = $opleverdocument_id;
            $new_item['item_id'] = $item_id;
            $new_item['aantal'] = $aantal;

            $success = $this->opleverdocument_verbruiksmaterialen_model->add($new_item);

            echo json_encode([
                'success' => true,
                'message' => _l('added_successfully', _l('verbruiksmateriaal')),
            ]);
        }
    }

    public function delete($id = '')
    {
        if (!staff_can('delete', FEATURE_OPLEVERDOCUMENT)) {
            access_denied(_l('delete') . ' VENNTECH ' . _l('verbruiksmaterialen'));
        }
        $opleverdocument_verbruiksmaterial = $this->opleverdocument_verbruiksmaterialen_model->get($id);
        $opleverdocument_id = $opleverdocument_verbruiksmaterial->opleverdocument_id;
        $success = $this->opleverdocument_verbruiksmaterialen_model->delete($id);
        if ($success) {
            set_alert('success', _l('deleted', _l('verbruiksmateriaal')));
        }
        redirect(admin_url('venntech/opleverdocumenten_verbruiksmaterialen/view_table/' . $opleverdocument_id));
    }
}
