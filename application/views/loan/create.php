<?php $url = base_url('loan')?>
<section class="content-header">
  <h1>
    Loan
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title ?></h3>
    </div>
    <form class="form-horizontal" data-action="<?= "{$url}/{$action}";?>" onsubmit="return confirm('Are you sure?')">
      <input type="text" class="hidden" value="<?= (isset($loan))?$loan['id']:"";?>" name="id"/>
      <div class="box-body">
        <div class="alert alert-info"><p>Fields marked with <span class="fa fa-asterisk text-danger"></span> are required.</p></div>
        <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Date</label>
          <div class="col-sm-2">     
            <input type="text" class="form-control datepicker" name="loan_date" value="<?= (isset($loan))?date_format(date_create($loan['loan_date']), 'm/d/Y'):"";?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Loan name</label>
          <div class="col-sm-4">     
            <input type="text" class="form-control" name="loan_name" value="<?= (isset($loan))?$loan['loan_name']:"";?>"/>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Employee</label>
          <div class="col-sm-3">
            <?= form_dropdown('employee_number', [ 'all' => '*All employees*' ]+ $employees, (isset($loan))?$loan['employee_id']:FALSE, 'class="form-control" required="required"')?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Loan amount</label>
          <div class="col-sm-3">
            <input name="loan_amount"  min="0" step="0.01" value="<?= (isset($loan))?$loan['loan_amount']:'0.00';?>" class="form-control pformat loan_amount"/>
          </div>
          <label class="col-sm-3 control-label"><span class="fa fa-asterisk text-danger"></span> Minimum loan payment</label>
          <div class="col-sm-3">
            <input name="loan_minimum_pay"  min="0" step="0.01" value="<?= (isset($loan))?$loan['loan_minimum_pay']:'0.00';?>" class="form-control pformat"/>
          </div>
        </div>
        <?php if(isset($loan_payments)):?>
          <?php
            $total = 0;
          ?>
          <hr>
          <div>
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Payment date</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($loan_payments as $loan_payments_key=>$loan_payments_value):?>
                  <?php $total += $loan_payments_value['payment_amount'];?>
                  <tr>
                    <td><?= str_pad($loan_payments_key, 4, 0, STR_PAD_LEFT);?></td>
                    <td><?= date_format(date_create($loan_payments_value['payment_date']), 'M d, Y');?></td>
                    <td><?= number_format($loan_payments_value['payment_amount'], 2);?></td>
                  </tr>
                <?php endforeach;?>
              </tbody>
              <tfoot>
                <tr>
                  <td></td>
                  <td><b class="pull-right">Total:</b></td>
                  <td>P <?= number_format($total, 2);?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        <?php endif;?>
        <!-- /.box-body -->
      </div>
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>