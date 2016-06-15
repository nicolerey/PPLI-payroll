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
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Employee</label>
          <div class="col-sm-3">
            <?= form_dropdown('employee_number', [ 'all' => '*All employees*' ]+ $employees, (isset($loan))?$loan['employee_id']:FALSE, 'class="form-control" required="required"')?>
          </div>
          <div class="col-sm-1"></div>
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span> Loan amount</label>
          <div class="col-sm-2">
            <input name="loan_amount"  min="0" step="0.01" value="<?= (isset($loan))?$loan['loan_amount']:'0.00';?>" class="form-control pformat loan_amount"/>
          </div>
        </div>
        <hr>
        <div class="form-group">
          <label class="col-sm-2 control-label"> Loan payment series count</label>
          <div class="col-sm-1">            
              <input type="number" class="form-control payment_count" value="0" name="payment_count"/>
          </div>
          <div class="col-sm-2">            
            <button type="button" class="btn btn-default btn-flat" onclick="generate_payment_terms(this);">Generate</button>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><span class="fa fa-asterisk text-danger"></span></label>
          <div class="col-sm-6">
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th class="col-sm-1">Date</th>
                  <th class="col-sm-1">Amount</th>
                </tr>
              </thead>
              <tbody class="payment_terms_tbody">
                <tr class="payment_terms_fields hidden">
                  <td>
                    <input type="text" class="form-control datepicker col-sm-1 loan_date_field" value=""/>
                  </td>
                  <td>
                    <input min="0" step="0.01" value="0.00" class="form-control pformat loan_amount_field" onchange="calculate_payment_total();"/>
                  </td>
                </tr>
                <?php if(isset($loan)):?>
                  <?php foreach($loan['payment_terms'] as $value):?>
                    <tr class="payment_terms_fields payment_terms">
                      <td>
                        <input type="text" class="form-control datepicker col-sm-1 loan_date_field" value="<?= date_format(date_create($value['payment_date']), 'm/d/Y');?>" name="payment_date[]"/>
                      </td>
                      <td>
                        <input min="0" step="0.01" value="<?= $value['payment_amount'];?>" class="form-control pformat loan_amount_field" name="payment_amount[]" onchange="calculate_payment_total();"/>
                      </td>
                    </tr>
                  <?php endforeach;?>
                <?php endif;?>
              </tbody>
              <tbody>
                <td class="text-right"><label class="control-label"> Total:</label></td>
                <td style="vertical-align: bottom;" class="payment_total">2000</td>
              </tbody>
            </table>
            <input type="text" name="payment_total" class="input_payment_total hidden"/>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Submit</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>