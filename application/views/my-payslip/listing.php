<section class="content-header">
  <h1>
    My Payslips
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('payslip')?>"><i class="fa fa-plus"></i> Generate payslip</a>
    <?php if($this->session->userdata('account_type')=='ad'):?>
      <button type="button" style="margin-right: 5px;" class="btn btn-flat btn-success pull-right btn-sm" id="approve_button"><i class="fa fa-check"></i> Approve selected</button>
    <?php endif;?>
    <button type="button" style="margin-right: 5px;" class="btn btn-flat btn-primary pull-right btn-sm" id="print_button" data-url="<?= base_url('my_payslip/print_payslip/');?>"><i class="fa fa-print"></i> Print payslip</button>
    <div class="col-sm-2 pull-right">
      <input type="number" class="form-control pull-right" id="batch_id"/>
    </div>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-body no-padding">
      <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
      <div class="form-inline" style="padding-top: 10px; padding-left: 5px;">
        <div class="form-group">
          <label>Employee name</label>
          <select class="search_employee form-control">
            <option value=""></option>
            <?php foreach($employee as $emp):?>
              <option value="<?= $emp['id'];?>"><?= "{$emp['lastname']}, {$emp['firstname']} {$emp['middleinitial']}."?></option>
            <?php endforeach;?>
          </select>
        </div>
        <button type="button" class="btn btn-default" id="search_employee" data-url="<?= base_url('my_payslip/search_employee')?>"><i class="fa fa-search"></i> Search</button>
      </div>
      <form class="form-horizontal" action="<?= base_url('my_payslip/approve_payslip');?>" method="POST">
        <table class="table table-hover table-striped">
        	<thead>
  			   <tr>
            <?php if($this->session->userdata('account_type')=='ad'):?>
              <th></th>
            <?php endif;?>
            <th>#</th><th>Batch ID</th><th>Employee Name</th><th>From</th><th>To</th><th>Status</th>
          </tr>
        	</thead>
        	<tbody id="table_body">
            <?php if(empty($items)):?>
              <tr><td class="text-center" colspan="7">Nothing to display</td></tr>
            <?php endif;?>
        		<?php foreach($items AS $row):?>
        			<tr>
                <?php if($this->session->userdata('account_type')=='ad'):?>
                  <td><input type="checkbox" name="checkbox[]" value="<?= $row['id'];?>"/></td>
                <?php endif;?>
                <td><a href="<?= base_url("my_payslip/view/{$row['id']}")?>"><?= str_pad($row['id'], 4, 0, STR_PAD_LEFT)?></a></td>
                <td><?= $row['batch_id'];?></td>
                <td><?= "{$row['firstname']} {$row['middleinitial']} {$row['lastname']}";?></td>
                <td><?= format_date($row['start_date'], 'd-M-Y')?></td>
                <td><?= format_date($row['end_date'], 'd-M-Y')?></td>
                <td>
                  <span class="label label-<?= ($row['approval_status'])?'success':'warning';?>">
                    <?= ($row['approval_status'])?'Approved':'Pending';?>
                  </span>
                </td>
        			</tr>
        		<?php endforeach;?>
        	</tbody>
        </table>
      </form>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>