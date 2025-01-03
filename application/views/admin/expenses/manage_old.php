<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
              
                <div class="tw-mb-2 sm:tw-mb-4">
                    <div class="_buttons">
                        <?php if (staff_can('create',  'expenses')) { ?>
                        <a href="<?php echo admin_url('expenses/expense'); ?>" class="btn btn-primary">
                            <i class="fa-regular fa-plus tw-mr-1"></i>
                            <?php echo _l('new_expense'); ?>
                        </a>
                        <a href="<?php echo admin_url('expenses/import'); ?>" class="btn btn-primary mleft5">
                            <i class="fa-solid fa-upload tw-mr-1"></i>
                            <?php echo _l('import_expenses'); ?>
                        </a>
                        <!--<a href="<?php /*echo admin_url('expenses/report'); */?>" class="btn btn-primary mleft5">
                            <i class="fa-solid fa-chart-simple tw-mr-1"></i>
                            <?php /*echo _l('expense_report'); */?>
                        </a>-->
                        <button class="btn btn-primary mleft5 store_expense_approve">
                            <i class="fa-solid fa-check tw-mr-1"></i>
                            <?php echo _l('expense_approve'); ?>
                        </button>
                        <?php } ?>
                        
                        <div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5">
                            <app-filters 
                                id="<?php echo $table->id(); ?>" 
                                view="<?php echo $table->viewName(); ?>"
                                :saved-filters="<?php echo $table->filtersJs(); ?>"
                                :available-rules="<?php echo $table->rulesJs(); ?>">
                            </app-filters>
                        </div>

                        <a href="#" onclick="slideToggle('#stats-top'); return false;"
                            class="pull-right btn btn-default mleft5 btn-with-tooltip" data-toggle="tooltip"
                            title="<?php echo _l('view_stats_tooltip'); ?>"><i class="fa fa-bar-chart"></i></a>
                        <a href="#" class="btn btn-default pull-right btn-with-tooltip toggle-small-view hidden-xs"
                            onclick="toggle_small_view('.table-expenses','#expense'); return false;"
                            data-toggle="tooltip" title="<?php echo _l('invoices_toggle_table_tooltip'); ?>"><i
                                class="fa fa-angle-double-left"></i></a>
                        <div id="stats-top" class="hide">
                            <hr />
                            <div id="expenses_total"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" id="small-table">
                        <div class="panel_s">
                            <div class="panel-body">
                                <div class="clearfix"></div>
                                <!-- if expenseid found in url -->
                                <?php echo form_hidden('expenseid', $expenseid); ?>
                               <!-- <div class="panel-table-full">
                                    <?php /*$this->load->view('admin/expenses/table_html', ['withBulkActions' => true]); */?>
                                </div>-->
                                <table class="table dataTable no-footer dt-table expense_table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Billable</th>
<!--                                        <th>Currency</th>-->
                                        <th>Total Amount</th>
                                        <th>Payment Mode</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(!empty($expenses_data)){
                                        $i = 1;
                                        foreach ($expenses_data as $val){ ?>
                                            <tr>
                                                <td class="text-right">
<!--                                                    <span style="margin-right: 35px;">--><?//= $i ?><!--</span>-->
                                                    <?php if($val->status == 0){ ?>
                                                        <input type="checkbox" id="expense_approve_<?= $val->id ?>" name="approve[]" value="<?= $val->id ?>">
                                                    <?php } ?>
                                                </td>
                                                <td><?= $val->category; ?>
                                                    <div class="row-options">
                                                        <a href="<?php echo admin_url('expenses/expense/'.$val->id); ?>">Edit</a> |
                                                        <a href="<?php echo admin_url('expenses/delete/'.$val->id); ?>" class="text-danger">Delete </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if(!empty($val->employee)){
                                                         $emp = $this->db->where_in('staffid', $val->employee)->get('tblstaff')->row_array();
                                                        echo $emp['firstname'].' '.$emp['lastname'];
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?= $val->type ?>
                                                </td>
                                                <td><?= date('Y-m-d',strtotime($val->date)); ?></td>
                                                <td><?php if($val->billable == '0'){ ?>
                                                    No
                                                  <?php }else{ ?>
                                                    Yes
                                                   <?php } ?>
                                                </td>
                                                <td><?= $val->total_amount ?></td>
                                                <td><?= $val->paymentmode ?>
                                                   <!-- --><?php /*$paymode = $this->db->where_in('id', $val->paymentmode)->get('tblpayment_modes')->row_array();
                                                    echo $paymode['name'];
                                                    */?>
                                                </td>
                                                <td>
                                                    <?php if($val->status == 1){ ?>
                                                        <span class="label label-success  s-status invoice-status-2">Approved</span>
                                                    <?php }else{ ?>
                                                        <span class="label label-danger  s-status invoice-status-2">Not Approved</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        <?php }
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 small-table-right-col">
                        <div id="expense" class="hide">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="expense_convert_helper_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('additional_action_required'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="radio radio-primary">
                    <input type="radio" checked id="expense_convert_invoice_type_1" value="save_as_draft_false"
                        name="expense_convert_invoice_type">
                    <label for="expense_convert_invoice_type_1"><?php echo _l('convert'); ?></label>
                </div>
                <div class="radio radio-primary">
                    <input type="radio" id="expense_convert_invoice_type_2" value="save_as_draft_true"
                        name="expense_convert_invoice_type">
                    <label for="expense_convert_invoice_type_2"><?php echo _l('convert_and_save_as_draft'); ?></label>
                </div>
                <div id="inc_field_wrapper">
                    <hr />
                    <p><?php echo _l('expense_include_additional_data_on_convert'); ?></p>
                    <p><b><?php echo _l('expense_add_edit_description'); ?> +</b></p>
                    <div class="checkbox checkbox-primary inc_note">
                        <input type="checkbox" id="inc_note">
                        <label for="inc_note"><?php echo _l('expense'); ?>
                            <?php echo _l('expense_add_edit_note'); ?></label>
                    </div>
                    <div class="checkbox checkbox-primary inc_name">
                        <input type="checkbox" id="inc_name">
                        <label for="inc_name"><?php echo _l('expense'); ?> <?php echo _l('expense_name'); ?></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    id="expense_confirm_convert"><?php echo _l('confirm'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
var hidden_columns = [4, 5, 6, 7, 8, 9];
</script>
<?php init_tail(); ?>
<script>
Dropzone.autoDiscover = false;
$(function() {
    initDataTable('.table-expenses', admin_url + 'expenses/table', [0], [0], {},
            <?php echo hooks()->apply_filters('expenses_table_default_order', json_encode([6, 'desc'])); ?>)
        .column(1).visible(false, false).columns.adjust();

    init_expense();

    $('#expense_convert_helper_modal').on('show.bs.modal', function() {
        var emptyNote = $('#tab_expense').attr('data-empty-note');
        var emptyName = $('#tab_expense').attr('data-empty-name');
        if (emptyNote == '1' && emptyName == '1') {
            $('#inc_field_wrapper').addClass('hide');
        } else {
            $('#inc_field_wrapper').removeClass('hide');
            emptyNote === '1' && $('.inc_note').addClass('hide') || $('.inc_note').removeClass('hide')
            emptyName === '1' && $('.inc_name').addClass('hide') || $('.inc_name').removeClass('hide')
        }
    });

    $('body').on('click', '#expense_confirm_convert', function() {
        var parameters = new Array();
        if ($('input[name="expense_convert_invoice_type"]:checked').val() == 'save_as_draft_true') {
            parameters['save_as_draft'] = 'true';
        }
        parameters['include_name'] = $('#inc_name').prop('checked');
        parameters['include_note'] = $('#inc_note').prop('checked');
        window.location.href = buildUrl(admin_url + 'expenses/convert_to_invoice/' + $('body').find(
            '.expense_convert_btn').attr('data-id'), parameters);
    });
});
$(document).on('click', '.store_expense_approve', function(e) {
    var approve = [];
    $.each($("input[name='approve[]']:checked"), function () {
        approve.push($(this).val());
    });
    // console.log(approve);
    if(approve != ""){
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "<?php echo base_url('admin/Expenses/approve_expense'); ?>",
            data: {
                id: approve
            },
            success: function(response) {
                table.ajax.reload();
                // $( "#expense_table" ).load( "manage.php #expense_table" );
                /*if(response === 'true'){
                    table.ajax.reload();
                }*/
            }
        });
    }
});
</script>
</body>

</html>