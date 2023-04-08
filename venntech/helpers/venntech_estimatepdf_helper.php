<?php

defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('pdf_footer', 'print_pdf_footer');
hooks()->add_action('before_customer_pdf_signature', 'venntech_before_customer_pdf_signature');

function venntech_before_customer_pdf_signature($data){
    $pdf_instance = $data['pdf_instance'];
    if (get_option('show_pdf_signature_estimate') == 0) {
        $pdf_instance->AddPage('P', 'A4');
    }
}

function estimatepdf_merge_fields($estimate_id, $html)
{
    $CI = &get_instance();

    if (!class_exists('other_merge_fields', false)) {
        $CI->load->library('merge_fields/other_merge_fields');
    }

    $CI->load->library('merge_fields/client_merge_fields');
    $CI->load->library('merge_fields/estimate_merge_fields');

    $CI->db->where('id', $estimate_id);
    $estimate = $CI->db->get(db_prefix() . 'estimates')->row();
    $CI->db->where('estimates_id', $estimate_id);
    $estimate_extra = $CI->db->get(db_prefix() . 'pc_estimates_extra')->row();

    $merge_fields = [];
    $merge_fields = array_merge($merge_fields, $CI->client_merge_fields->format($estimate->clientid));
    $merge_fields = array_merge($merge_fields, $CI->estimate_merge_fields->format($estimate_id));
    $merge_fields = array_merge($merge_fields, $CI->other_merge_fields->format());

    $fields['{taxrate}'] = get_tax_by_id($estimate_extra->tax_id)->taxrate;
    $fields['{number_of_panels}'] = $estimate_extra->number_of_panels;

    $merge_extra_fields = hooks()->apply_filters('estimate_merge_fields', $fields, [
        'id' => $estimate_id,
        'estimate' => $estimate,
    ]);

    $merge_fields = array_merge($merge_fields, $merge_extra_fields);

    foreach ($merge_fields as $key => $val) {
        $html = stripos($html, $key) !== false ? str_replace($key, $val, $html) : str_replace($key, '', $html);
    }

    return $html;
}

function get_item_pdf_info($item_id)
{
    if (isset($item_id)) {

        $CI = &get_instance();


        $product_path = '/modules/' . VENNTECH_MODULE_NAME . '/uploads/product/';

        // product
        $CI->db->where('item_id', $item_id);
        $items_extra = $CI->db->get(db_prefix() . 'pc_items_extra')->row();
        // invoice item
        $CI->db->where('id', $item_id);
        $items = $CI->db->get(db_prefix() . 'items')->row();

        // return object
        $item_pdf_info = new stdClass();
        $item_pdf_info->description = $items->description;
        $item_pdf_info->long_description = $items_extra->estimate_description;

        if (isset($items_extra->image_path) && $items_extra->image_path != '') {
            $item_pdf_info->image_path = $product_path . $items_extra->image_path;
        } else {
            $item_pdf_info->image_path = $product_path . 'venntech.png';
        }

        return $item_pdf_info;
    }

    return null;
}

function get_estimate_extra_selected_item_id_of_group($estimates_extra_id, $group_id)
{
    $CI = &get_instance();
    $CI->load->model('venntech/estimates_extra_items_model');

    $extra_items = $CI->estimates_extra_items_model->get_by_estimates_extra_relation($estimates_extra_id, $group_id, 'groups');

    if (isset($extra_items) && sizeof($extra_items) > 0) {
        return $extra_items[0]['items_id'];
    } else {
        return null;
    }

}

function get_html_code($title, $item_pdf_info)
{
    if (isset($item_pdf_info)) {
        return '
                <tr>
                    <td style="width: 30%"><img src="' . $item_pdf_info->image_path . '"  height="200" ><br></td>
                    <td style="width: 70%"><h3><b>' . $title . ': </b><span style="color: #1E88E5;">' . $item_pdf_info->description . '</span></h3><p>' . $item_pdf_info->long_description . ' </p></td>
                </tr> ';
    }
}

function print_pdf_footer($app_pdf_arr)
{
    $pdf = $app_pdf_arr['pdf_instance'];

    $pdf->writeHTML('<p><b>' . get_option('invoice_company_name') . '</b> | ' . get_option('invoice_company_address') . ' | ' . get_option('invoice_company_postal_code') . ' ' . get_option('invoice_company_city') . ' | www.venntech.be | Fortis BE21 0018 5056 6303</p>');
}