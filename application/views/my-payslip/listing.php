<section class="content-header">
  <h1>
    My Payslips
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('payslip')?>"><i class="fa fa-plus"></i> Generate payslip</a>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-body no-padding">
      <table class="table table-hover table-striped">
      	<thead>
			<tr><th>#</th><th>Employee Name</th><th>From</th><th>To</th></tr>      		
      	</thead>
      	<tbody>
          <?php if(empty($items)):?>
            <tr><td class="text-center" colspan="4">Nothing to display</td></tr>
          <?php endif;?>
      		<?php foreach($items AS $row):?>
      			<tr>
              <td><a href="<?= base_url("my_payslip/view/{$row['id']}")?>"><?= str_pad($row['id'], 4, 0, STR_PAD_LEFT)?></a></td>
              <td><?= "{$row['firstname']} {$row['middleinitial']} {$row['lastname']}";?></td>
              <td><?= format_date($row['start_date'], 'd-M-Y')?></td>
              <td><?= format_date($row['end_date'], 'd-M-Y')?></td>
      			</tr>
      		<?php endforeach;?>
      	</tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>