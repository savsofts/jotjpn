<?php
    $this->set_css($this->default_theme_path.'/bootstrap-v5/css/bootstrap/bootstrap.min.css');
    $this->set_css($this->default_theme_path.'/bootstrap-v5/css/elusive-icons/css/elusive-icons.min.css');
    $this->set_css($this->default_theme_path.'/bootstrap-v5/css/common.css');
    $this->set_css($this->default_theme_path.'/bootstrap-v5/css/list.css');
    $this->set_css($this->default_theme_path.'/bootstrap-v5/css/general.css');
    $this->set_css($this->default_theme_path.'/bootstrap-v5/css/plugins/animate.min.css');
    $this->set_css($this->default_theme_path.'/bootstrap-v5/css/main.css');

    $jquery_js = isset($jquery_js) ? $jquery_js : grocery_CRUD::JQUERY;
    if ($this->config->environment == 'production') {
        $this->set_js_lib($this->default_javascript_path.'/' . $jquery_js);
        $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/build/js/global-libs.min.js');
    } else {
        $this->set_js_lib($this->default_javascript_path.'/' . $jquery_js);
        $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/jquery-plugins/jquery.form.js');
        $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/common/cache-library.js');
        $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/common/common.js');
    }

    //section libs
    $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/jquery-plugins/gc-dropdown.min.js');
    $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/jquery-plugins/gc-modal.min.js');
    $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/jquery-plugins/bootstrap-growl.min.js');
    $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/jquery-plugins/jquery.print-this.js');


    //page js
    $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/datagrid/gcrud.datagrid.js');
    $this->set_js_lib($this->default_theme_path.'/bootstrap-v5/js/datagrid/list.js');

    if (!empty($this->upload_fields)) {
        $this->load_js_fancybox();
    }



    $colspans = (count($columns) + 2);

    //Start counting the buttons that we have:
    $buttons_counter = 0;

    if (!$unset_edit) {
        $buttons_counter++;
    }

    if (!$unset_read) {
        $buttons_counter++;
    }

    if (!$unset_delete) {
        $buttons_counter++;
    }

    if (!empty($list[0]) && !empty($list[0]->action_urls)) {
        $buttons_counter = $buttons_counter +  count($list[0]->action_urls);
    }

    $search_column_string = $this->l('list_search_column');
    $alert_multiple_delete = $this->l('alert_delete_multiple');
    $alert_multiple_delete_one = $this->l('alert_delete_multiple_one');

    $list_displaying = str_replace(
        array(
            '{start}',
            '{end}',
            '{results}'
        ),
        array(
            '<span class="paging-starts">1</span>',
            '<span class="paging-ends">10</span>',
            '<span class="current-total-results">'. $this->get_total_results() . '</span>'
        ),
        $this->l('list_displaying'));

    include(__DIR__ . '/common_javascript_vars.php');
?>
<script type='text/javascript'>
    var base_url = '<?php echo base_url();?>';

    var subject = '<?php echo $subject?>';
    var ajax_list_info_url = '<?php echo $ajax_list_info_url; ?>';
    var ajax_list_url = '<?php echo $ajax_list_url;?>';
    var unique_hash = '<?php echo $unique_hash; ?>';

    var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";
    var THEME_VERSION = '1.5.3';
</script>
    <br/>
    <div class="container-fluid gc-container">
        <div class="success-message hidden"><?php
        if($success_message !== null){?>
           <?php echo $success_message; ?> &nbsp; &nbsp;
        <?php }
        ?></div>

 		<div class="row">
        	<div class="table-section">
                <div class="table-label">
                    <div class="floatL l5">
                        <?php echo $subject_plural; ?>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="table-container">
                    <?php echo form_open("", 'method="post" autocomplete="off" id="gcrud-search-form"'); ?>
                        <div class="header-tools">
                            <?php if(!$unset_add){?>
                                <div class="floatL t5">
                                    <a class="btn btn-default btn-outline-dark" href="<?php echo $add_url?>">
                                        <i class="el el-plus"></i> &nbsp; <?php echo $this->l('list_add'); ?> <?php echo $subject?>
                                    </a>
                                </div>
                            <?php } ?>
                            <div class="floatR">
                                <?php if(!$unset_export) { ?>
                                    <a class="btn btn-default btn-outline-dark t5 gc-export" data-url="<?php echo $export_url; ?>" href="javascript:;">
                                        <i class="el el-share floatL t3"></i>
                                        <span class="hidden-xs floatL l5">
                                            <?php echo $this->l('list_export');?>
                                        </span>
                                        <div class="clear"></div>
                                    </a>
                                <?php } ?>
                                <?php if(!$unset_print) { ?>
                                    <a class="btn btn-default btn-outline-dark t5 gc-print" data-url="<?php echo $print_url; ?>" href="javascript:;">
                                        <i class="el el-print floatL t3"></i>
                                        <span class="hidden-xs floatL l5">
                                            <?php echo $this->l('list_print');?>
                                        </span>
                                        <div class="clear"></div>
                                    </a>
                                <?php }?>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="scroll-if-required">
        			        <table class="table table-bordered grocery-crud-table table-hover">
        					<thead>
        						<tr>
        							<th colspan="2" <?php if ($buttons_counter === 0) {?>class="hidden"<?php }?>>
                                        <?php echo $this->l('list_actions'); ?>
                                    </th>
                                    <?php foreach($columns as $column){?>
                                        <th class="column-with-ordering" data-order-by="<?php echo $column->field_name; ?>"><?php echo $column->display_as; ?></th>
                                    <?php }?>
        						</tr>
        						
        						<tr class="filter-row gc-search-row">
        							<td class="no-border-right <?php if ($buttons_counter === 0) {?>hidden<?php }?>">
                                        <?php if (!$unset_delete) { ?>
            							     <div class="floatL t5">
            							         <input type="checkbox" class="select-all-none" />
            							     </div>
                                         <?php } ?>
        							 </td>
        							<td class="no-border-left <?php if ($buttons_counter === 0) {?>hidden<?php }?>">
                                        <div class="floatL">
                                            <a href="javascript:void(0);" title="<?php echo $this->l('list_delete')?>"
                                               class="hidden btn btn-outline-dark delete-selected-button">
                                                <i class="el el-remove text-danger"></i>
                                                <span class="text-danger"><?php echo $this->l('list_delete')?></span>
                                            </a>
                                        </div>
                                        <div class="floatR l5">
                                            <a href="javascript:void(0);" class="btn btn-default btn-outline-dark gc-refresh">
                                                <i class="el el-refresh"></i>
                                            </a>
                                        </div>
                                        <div class="clear"></div>
                                    </td>
                                    <?php foreach($columns as $column){?>
                                        <td>
                                            <input
                                                type="text"
                                                class="form-control searchable-input floatL"
                                                placeholder="<?php echo str_replace('{column_name}', $column->display_as, $search_column_string); ?>"
                                                name="<?php echo $column->field_name; ?>" />
                                        </td>
                                    <?php }?>
        						</tr>

        					</thead>
        					<tbody>
                                <?php include(__DIR__."/list_tbody.php"); ?>
        					</tbody>
                            </table>
                        </div>
                            <!-- Table Footer -->
        					<div class="footer-tools">

                                            <!-- "Show 10/25/50/100 entries" (dropdown per-page) -->
                                            <div class="floatL t20 l5">
                                                <div class="floatL t8">
                                                    <?php list($show_lang_string, $entries_lang_string) = explode('{paging}', $this->l('list_show_entries')); ?>
                                                    <?php echo $show_lang_string; ?>
                                                </div>
                                                <div class="floatL r5 l5">
                                                    <select name="per_page" class="per_page form-control form-select">
                                                        <?php foreach($paging_options as $option){?>
                                                            <option value="<?php echo $option; ?>"
                                                                    <?php if($option == $default_per_page){?>selected="selected"<?php }?>>
                                                                        <?php echo $option; ?>&nbsp;&nbsp;
                                                            </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="floatL t8">
                                                    <?php echo $entries_lang_string; ?>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                            <!-- End of "Show 10/25/50/100 entries" (dropdown per-page) -->

                                            <!-- Start of: Settings button -->
                                            <div class="btn-group floatR t20 l5 r5 settings-button-container">
                                                <button type="button" class="btn btn-default btn-outline-primary settings-button gc-bootstrap-dropdown dropdown-toggle">
                                                    <i class="el el-cog r5"></i>
                                                    <span class="caret"></span>
                                                </button>

                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="javascript:void(0)" class="clear-filtering dropdown-item">
                                                        <i class="el el-broom"></i> <?php echo $this->l('list_clear_filtering'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- End of: Settings button -->


                                            <div class="floatR r5 gc-pagination-container">

                                                <!-- Buttons - First,Previous,Next,Last Page -->
                                                <ul class="pagination">
                                                    <li class="disabled paging-first page-item">
                                                        <a href="javascript:;" class="page-link">
                                                            <i class="el el-step-backward"></i>
                                                        </a>
                                                    </li>
                                                    <li class="prev disabled paging-previous page-item">
                                                        <a href="javascript:;" class="page-link">
                                                            <i class="el el-chevron-left"></i>
                                                        </a>
                                                    </li>
                                                    <li class="page-item">
                                                        <span class="page-number-input-container page-link">
                                                            <input type="number" value="1" class="form-control page-number-input" />
                                                        </span>
                                                    </li>
                                                    <li class="next paging-next page-item">
                                                        <a href="#" class="page-link">
                                                            <i class="el el-chevron-right"></i>
                                                        </a>
                                                    </li>
                                                    <li class="paging-last page-item">
                                                        <a href="#" class="page-link">
                                                            <i class="el el-step-forward"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <!-- End of Buttons - First,Previous,Next,Last Page -->

                                                <input type="hidden" name="page_number" class="page-number-hidden" value="1" />
                                            </div>

                                            <!-- "Displaying 1 to 10 of 116 items" -->
                                            <div class="floatR r10 displaying-paging-items">
                                                <?php echo $list_displaying; ?>
                                                <span class="full-total-container hidden">
                                                    <?php echo str_replace(
                                                                "{total_results}",
                                                                "<span class='full-total'>" . $this->get_total_results() . "</span>",
                                                                $this->l('list_filtered_from'));
                                                    ?>
                                                </span>
                                            </div>
                                            <!-- End of "Displaying 1 to 10 of 116 items" -->

                                            <div class="clear"></div>
                            </div>
                            <!-- End of: Table Footer -->

                    <?php echo form_close(); ?>
                </div>
        	</div>

            <!-- Delete confirmation dialog -->
            <div class="delete-confirmation modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo $this->l('list_delete'); ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p><?php echo $this->l('alert_delete'); ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $this->l('form_cancel'); ?></button>
                            <button type="button" class="btn btn-danger delete-confirmation-button"><?php echo $this->l('list_delete'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Delete confirmation dialog -->

            <!-- Delete Multiple confirmation dialog -->
            <div class="delete-multiple-confirmation modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?php echo $this->l('list_delete'); ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p class="alert-delete-multiple hidden">
                                <?php echo str_replace('{items_amount}', '<span class="delete-items-amount"></span>', $alert_multiple_delete); ?>
                            </p>
                            <p class="alert-delete-multiple-one hidden">
                                <?php echo $alert_multiple_delete_one; ?>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <?php echo $this->l('form_cancel'); ?>
                            </button>
                            <button type="button" class="btn btn-danger delete-multiple-confirmation-button"
                                    data-target="<?php echo $delete_multiple_url; ?>">
                                <?php echo $this->l('list_delete'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Delete Multiple confirmation dialog -->

            </div>
        </div>