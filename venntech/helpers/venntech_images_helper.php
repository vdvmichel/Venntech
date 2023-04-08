<?php

defined('BASEPATH') or exit('No direct script access allowed');

const IMAGE_TYPE_PRODUCT = "product";
const IMAGE_TYPE_INSPECTIE = "inspectie";
const IMAGE_TYPE_OPLEVERDOCUMENT = "opleverdocument";


function get_upload_path_venntech($image_type, $parent_id = '')
{
    $path = get_upload_path_by_type('venntech');

    if ($image_type == IMAGE_TYPE_PRODUCT) {
        $product_path = get_upload_path_by_type('venntech') . 'product/';
        if (!is_dir($product_path)) {
            mkdir($product_path, 0755, true);
        }
        $path = $product_path;
    } else if ($image_type == IMAGE_TYPE_INSPECTIE) {

        $inspectie_path = get_upload_path_by_type('venntech') . 'inspectie/';
        if (!is_dir($inspectie_path)) {
            mkdir($inspectie_path, 0755, true);
        }

        $path = get_upload_path_by_type('venntech') . 'inspectie/' . 'rapport_' . $parent_id . '/';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }    else if ($image_type == IMAGE_TYPE_OPLEVERDOCUMENT) {

        $opleverdocument_path = get_upload_path_by_type('venntech') . 'opleverdocument/';
        if (!is_dir($opleverdocument_path)) {
            mkdir($opleverdocument_path, 0755, true);
        }

        $path = get_upload_path_by_type('venntech') . 'opleverdocument/' . 'opleverdocument_' . $parent_id . '/';
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
    }

    return $path;
}

function get_download_path_venntech($image_type, $parent_id = '')
{

    $path = base_url('modules/' . VENNTECH_MODULE_NAME . '/uploads/');

    if ($image_type == IMAGE_TYPE_PRODUCT) {
        $path .= 'product/';
    } else if ($image_type == IMAGE_TYPE_INSPECTIE) {

        $path .= 'inspectie/rapport_' . $parent_id . '/';
    }else if ($image_type == IMAGE_TYPE_OPLEVERDOCUMENT) {

        $path .= 'opleverdocument/opleverdocument_' . $parent_id . '/';
    }

    return $path;
}

function handle_venntech_image_upload($image_type, $parent_id = '', $item_id = '', $index_name = 'file')
{
    if (isset($_FILES[$index_name]['name']) && '' != $_FILES[$index_name]['name']) {

        $path = get_upload_path_venntech($image_type, $parent_id);

        $tmpFilePath = $_FILES[$index_name]['tmp_name'];
        if (!empty($tmpFilePath) && '' != $tmpFilePath) {
            $filename = $_FILES[$index_name]['name'];
            $path_parts = pathinfo($filename);
            $extension = $path_parts['extension'];
            $extension = strtolower($extension);
            $filename = $image_type . '_' . $item_id . '.' . $extension;
            $newFilePath = $path . $filename;
            _maybe_create_upload_path($path);
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                return $filename;
            }
        }

    }
    return false;
}

function handle_venntech_dropzone_upload($image_type, $parent_id = '', $item_id = '', $filename, $tmpFilePath)
{
    $path = get_upload_path_venntech($image_type, $parent_id);
    $path_parts = pathinfo($filename);
    $extension = $path_parts['extension'];
    $extension = strtolower($extension);
    $filename = $image_type . '_' . $item_id . '.' . $extension;
    $newFilePath = $path . $filename;
    _maybe_create_upload_path($path);
    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
        return $filename;
    } else {
        return false;
    }

}

function handle_venntech_image_delete($image_type, $parent_id = '', $filename)
{
    if ($filename != "") {
        $path = get_upload_path_venntech($image_type, $parent_id);
        $filePath = $path . $filename;
        if (file_exists($filePath)) {
            return unlink($filePath);
        } else {
            return false;
        }
    }
}

function handle_venntech_product_upload($product_id)
{
    return handle_venntech_image_upload(IMAGE_TYPE_PRODUCT, '', $product_id, 'product');
}

function handle_venntech_product_delete($image_path)
{
    handle_venntech_image_delete(IMAGE_TYPE_PRODUCT, '', $image_path);
}

function render_image($image_type, $parentid, $filename, $label)
{
    $path = get_download_path_venntech($image_type, $parentid);
    $path_parts = pathinfo($filename);
    $file_id = $path_parts['filename'];
    $params=''.$parentid .' , \''.$filename.'\'' .' , \''.$file_id.'\'' ;

    echo '   
    <div id="'.$file_id.'" class="col-md-4">
        <button type="button" class="pull-right close" onclick="remove_image( '.$params.' )" >
            <i class="fa fa fa-times"></i>
        </button>
        <div class="existing_image">
            <a href="' . $path . $filename . '" data-lightbox = "gallery" >
                <img src = "' . $path . $filename . '" class="img img-responsive img-thumbnail zoom" />
            </a >
        </div >
    </div >';

}