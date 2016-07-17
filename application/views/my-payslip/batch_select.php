<section class="content-header">
  <h1>
    Payslips
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-body no-padding">
      <table class="table table-hover table-striped">
      	<thead>
        <tr>
          <th>#</th><th>Batch ID</th><th>Start date</th><th>End date</th>
        </tr>
      	</thead>
      	<tbody id="table_body">
          <?php if(empty($batches)):?>
            <tr><td class="text-center" colspan="4">Nothing to display</td></tr>
          <?php endif;?>
      		<?php foreach($batches AS $index=>$row):?>
      			<tr>
              <td>
                <a href="<?= base_url('my_payslip/view_payslip/'.$row['batch_id']);?>">
                  <?= str_pad($index+1, 4, 0, STR_PAD_LEFT)?>
                </a>
              </td>
              <td><?= $row['batch_id'];?></td>
              <td><?= $row['start_date'];?></td>
              <td><?= $row['end_date'];?></td>
      			</tr>
      		<?php endforeach;?>
      	</tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>