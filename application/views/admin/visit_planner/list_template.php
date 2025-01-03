<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="col-md-12">
    <div class="tw-mb-2 sm:tw-mb-4">
        <div class="_buttons">

<!--            --><?php //$this->load->view('admin/invoices/invoices_top_stats'); ?>

            <?php if (staff_can('create',  'visit_planner')) { ?>
                <a href="<?php echo admin_url('Visit_Planner/visit_planner'); ?>"
                    class="btn btn-primary pull-left new new-invoice-list mright5">
                    <i class="fa-regular fa-plus tw-mr-1"></i>
                    <?php echo _l('create_new_visit_planner'); ?>
                </a>
            <?php } ?>
            <?php /*if (!isset($project) && !isset($customer) && staff_can('create', 'payments')) { */?><!--
                <button id="add-batch-payment" onclick="add_batch_payment()" class="btn btn-primary pull-left">
                    <i class="fa-solid fa-file-invoice tw-mr-1"></i>
                    <?php /*echo _l('batch_payments'); */?>
                </button>
            <?php /*} */?>
            <?php /*if (!isset($project)) { */?>
                <a href="<?php /*echo admin_url('invoices/recurring'); */?>" class="btn btn-default pull-left mleft5">
                    <i class="fa-solid fa-repeat tw-mr-1"></i>
                    <?php /*echo _l('invoices_list_recurring'); */?>
                </a>
            --><?php /*} */?>

          <div class="display-block pull-right tw-space-x-0 sm:tw-space-x-1.5">
              <!--<app-filters
                      id="<?php /*echo $table->id(); */?>"
                      view="<?php /*echo $table->viewName(); */?>"
                      :rules="<?php /*echo app\services\utilities\Js::from($this->input->get('status') ? $table->findRule('status')->setValue([(int) $this->input->get('status')]) : []); */?>"
                      :saved-filters="<?php /*echo $table->filtersJs(); */?>"
                      :available-rules="<?php /*echo $table->rulesJs(); */?>">
              </app-filters>-->
            </div>

            <div class="clearfix"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="small-table">
            <div class="panel_s">
                <div class="panel-body panel-table-full">
                    <table class="table dataTable no-footer dt-table">
                        <thead>
                        <tr>
                            <th>Visit Planner#</th>
                            <th>Type</th>
                            <th>Subject</th>
                            <th>To</th>
                            <th>Company</th>
                            <th>Relation</th>
                            <th>Date</th>
                            <th>Visited Date</th>
                            <th>Next Followup Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!empty($visit_planner_data)){
                                $i = 1;
                                foreach ($visit_planner_data as $val){ ?>
                                    <?php if($session_id == $val->assigned) { ?>
                                        <tr>
                                            <td><?= _l('visit_planner_short') ?> - <?=  sprintf("%06d", $val->id); ?>
                                                <div class="row-options">
                                                    <a href="<?php echo admin_url('Visit_Planner/visit_planner/'.$val->id); ?>">Edit</a> |
    <!--                                                <a href="--><?php //echo admin_url('visit_planner/view/'.$val->id); ?><!--">View</a> |-->
<!--                                                    <a href="--><?php //echo admin_url('Visit_Planner/view/' . $val->id ); ?><!--" target="_blank">View</a> |-->
                                                    <a href="<?php echo admin_url('Visit_Planner/delete/'.$val->id); ?>" class="text-danger">Delete </a>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($val->type == _l('visit_planner_planned_visit')){ ?>
                                                    <span class="label label-success  s-status invoice-status-2"><?= $val->type ?></span>
                                                <?php } ?>
                                                <?php if($val->type == _l('visit_planner_unplanned_visit')){ ?>
                                                    <span class="label label-warning  s-status invoice-status-2"><?= $val->type ?></span>
                                                <?php } ?>
                                            </td>
                                            <td><?= $val->subject ?></td>
                                            <td>
                                                <?php if(!empty($val->clientid)){ ?>
                                                    <?php $client = $this->db->where_in('userid', $val->clientid)->where_in('is_primary','1')->get('tblcontacts')->row_array(); ?>
                                                    <?= $client['firstname'].' '.$client['lastname'] ?>
                                                <?php }else{ ?>
                                                    <?php $lead = $this->db->where_in('id', $val->leadid)->get('tblleads')->row_array(); ?>
                                                    <?= $lead['name'] ?>
                                                <?php } ?>
                                            </td>
                                            <td><?= $val->visit_planner_company_name ?></td>
                                            <td><?= $val->rel_type ?></td>
                                            <td><?= date('Y-m-d h:i A',strtotime($val->datetime)); ?></td>
                                            <?php
                                            $customer_visit =  $this->db->where_in('visit_planner_id', $val->id)->get('tblcustomer_visit')->result_array(); ?>
                                            <td>
                                                <?php if(!empty($customer_visit[0]['datetime'])){ ?>
                                                    <?= date('Y-m-d h:i A',strtotime($customer_visit[0]['datetime'])); ?>
                                                <?php }else{ ?>
                                                -
                                               <?php } ?>
                                            </td>
                                            <td>
                                                <?php if(!empty($customer_visit[0]['nxt_followup_date'])){ ?>
                                                    <?= date('Y-m-d h:i A',strtotime($customer_visit[0]['nxt_followup_date'])); ?>
                                                <?php }else{ ?>
                                                    -
                                                <?php } ?>
                                            </td>
                                            <td><a href="<?php echo admin_url('Visit_Planner/list_customer_visit/'.$val->id); ?>">Customer Visit</a></td>
                                        </tr>
                                        <?php $i++; ?>
                                    <?php } ?>
                               <?php }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="row">
        <div class="col-md-12" id="small-table">
            <div class="panel_s">
                <div class="panel-body panel-table-full">
                    <?php /*$this->load->view('admin/booking_unfix/table_html'); */?>
                </div>
            </div>
        </div>
    </div>-->
</div>