<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="form-group">
                            <h4><?php echo _l('estimate_pdf_layout') . ': ' . _l('estimate_template') . ' ' . $estimate_template->name ?></h4>
                        </div>
                        <hr class="hr-panel-heading">
                        <div class="clearfix"></div>
                        <div class="clearfix mtop20"></div>

                        <?php echo form_open($this->uri->uri_string()); ?>

                        <?php echo render_textarea('pre_page_1', 'Pre Pagina 1', $layout->pre_page_1, array(), array(), '', 'tinymce tinymce-manual'); ?>
                        <?php echo render_textarea('pre_page_2', 'Pre Pagina 2', $layout->pre_page_2, array(), array(), '', 'tinymce tinymce-manual'); ?>
                        <?php echo render_textarea('pre_page_3', 'Pre Pagina 3', $layout->pre_page_3, array(), array(), '', 'tinymce tinymce-manual'); ?>
                        <?php echo render_textarea('pre_page_4', 'Pre Pagina 4', $layout->pre_page_4, array(), array(), '', 'tinymce tinymce-manual'); ?>
                        <?php echo render_textarea('pre_page_5', 'Pre Pagina 5', $layout->pre_page_5, array(), array(), '', 'tinymce tinymce-manual'); ?>

                        <?php echo render_textarea('post_page_1', 'Post Pagina 1', $layout->post_page_1, array(), array(), '', 'tinymce tinymce-manual'); ?>
                        <?php echo render_textarea('post_page_2', 'Post Pagina 2', $layout->post_page_2, array(), array(), '', 'tinymce tinymce-manual'); ?>
                        <?php echo render_textarea('post_page_3', 'Post Pagina 3', $layout->post_page_3, array(), array(), '', 'tinymce tinymce-manual'); ?>
                        <?php echo render_textarea('post_page_4', 'Post Pagina 4', $layout->post_page_4, array(), array(), '', 'tinymce tinymce-manual'); ?>
                        <?php echo render_textarea('post_page_5', 'Post Pagina 5', $layout->post_page_5, array(), array(), '', 'tinymce tinymce-manual'); ?>

                        <!-- sticky footer with submit button -->
                        <div class="btn-bottom-toolbar text-right">
                            <a class="btn btn-info" href="/admin/venntech/estimate_templates"
                               role="button"><?php echo _l('cancel'); ?></a>
                            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                        </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo _l('available_merge_fields'); ?>
                        </h4>
                        <hr class="hr-panel-heading" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row available_merge_fields_container">
                                    <?php
                                    $mergeLooped = array();
                                    foreach($available_merge_fields as $field){
                                        foreach($field as $key => $val){
                                            echo '<div class="col-md-6 merge_fields_col">';
                                            echo '<h5 class="bold">'.ucwords(str_replace([ '-', '_'], ' ', $key)).'</h5>';
                                            foreach($val as $_field){
                                                if(count($_field['available']) == 0
                                                    && isset($_field['templates']) && in_array($template->slug, $_field['templates'])) {
                                                    // Fake data to simulate foreach loop and check the templates key for the available slugs
                                                    $_field['available'][] = '1';
                                                }
                                                foreach($_field['available'] as $_available){
                                                    if(
                                                        ($_available == $template->type ||
                                                            isset($_field['templates']) &&
                                                            in_array($template->slug, $_field['templates'])
                                                        ) && !in_array($template->slug, $_field['exclude'] ?? []) &&
                                                        !in_array($_field['name'], $mergeLooped)){
                                                        $mergeLooped[] = $_field['name'];
                                                        echo '<p>'.$_field['name'];
                                                        echo '<span class="pull-right"><a href="#" class="add_merge_field">';
                                                        echo $_field['key'];
                                                        echo '</a>';
                                                        echo '</span>';
                                                        echo '</p>';
                                                    }
                                                }
                                            }
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
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

        init_editor('textarea[name="pre_page_1"]');
        init_editor('textarea[name="pre_page_2"]');
        init_editor('textarea[name="pre_page_3"]');
        init_editor('textarea[name="pre_page_4"]');
        init_editor('textarea[name="pre_page_5"]');

        init_editor('textarea[name="post_page_1"]');
        init_editor('textarea[name="post_page_2"]');
        init_editor('textarea[name="post_page_3"]');
        init_editor('textarea[name="post_page_4"]');
        init_editor('textarea[name="post_page_5"]');

        appValidateForm($('form'), {});

        var merge_fields_col = $('.merge_fields_col');
        // If not fields available
        $.each(merge_fields_col, function () {
            var total_available_fields = $(this).find('p');
            if (total_available_fields.length == 0) {
                $(this).remove();
            }
        });
        // Add merge field to tinymce
        $('.add_merge_field').on('click', function (e) {
            e.preventDefault();
            tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).text());
        });
    });
</script>
</body>
</html>
