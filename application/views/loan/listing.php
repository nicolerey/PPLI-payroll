<section class="content-header">
  <h1>
    Loan
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('loan/create')?>"><i class="fa fa-plus"></i> Make a loan</a>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
      <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
      <div class="box-body">
        <div class="form-group">
          <form class="form-inline" method="GET" action="<?= current_url()?>">
            <div class="form-group">
              <label>Employee number</label>
              <select class="search_employee form-control" name="employee_number">
                <option value=""></option>
                <?php foreach($employee as $emp):?>
                  <option value="<?= $emp['id'];?>"><?= "{$emp['lastname']}, {$emp['firstname']} {$emp['middleinitial']}."?></option>
                <?php endforeach;?>
              </select>
            </div>
            <div class="form-group">
              <label for="start-date">Start date</label>
              <input type="text" class="form-control datepicker" id="start-date" name="start_date" value="<?= $this->input->get('start_date')?>">
            </div>
            <div class="form-group">
              <label for="end-date">End date</label>
              <input type="text" class="form-control datepicker" id="end-date" name="end_date" value="<?= $this->input->get('end_date')?>">
            </div>
            <button type="submit" class="btn btn-default btn-flat">Go!</button>
          </form>
        </div>
        <hr>
        <table class="table table-bordered table-condensed table-striped">
          <thead><tr class="active"><th>#</th><th>Loan Name</th><th>Employee Name</th><th>Loan Date</th><th>Loan Amount</th><th></th></tr></thead>
          <tbody>
            <?php if(empty($data)):?>
              <tr><td class="text-center" colspan="5">Nothing to display</td></tr>
            <?php else:?>
              <?php foreach($data as $loan):?>
                <tr>
                  <td>
                  <a href="<?= base_url("loan/view/{$loan['loan']['id']}")?>"><?= str_pad($loan['loan']['id'], 4, 0, STR_PAD_LEFT)?></a>
                  </td>
                  <td>
                    <?= $loan['loan']['loan_name'];?>
                  </td>
                  <td>
                    <?= $loan['name'];?>
                  </td>
                  <td>
                    <?= date_format(date_create($loan['loan']['loan_date']), 'm/d/Y');?>
                  </td>
                  <td>
                    <?= number_format($loan['loan']['loan_amount'], 2); ?>
                  </td>
                  <td>
                  <button type="button" delete_url="<?= base_url('loan/delete/'.$loan['loan']['id']);?>" class="btn btn-flat btn-danger btn-xs" onclick="delete_loan(this);">
                      <span class="glyphicon glyphicon-remove"></span> Delete
                    </button>
                  </td>
                </tr>
              <?php endforeach;?>
            <?php endif;?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>