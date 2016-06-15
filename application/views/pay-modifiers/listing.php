<section class="content-header">
  <h1>
    Pay Particulars
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('pay_modifiers/create')?>"><i class="fa fa-plus"></i> Add new pay modifier</a>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-body no-padding">
      <table class="table table-hover table-striped">
      	<thead>
			<tr><th>Name</th><th>Particular Type</th><th>Type</th></tr>      		
      	</thead>
      	<tbody>
          <?php if(empty($items)):?>
            <tr><td class="text-center" colspan="3">Nothing to display</td></tr>
          <?php endif;?>
      		<?php foreach($items AS $row):?>
      			<tr>
              <td><a href="<?= base_url("pay_modifiers/edit/{$row['id']}")?>"><?= $row['name']?></a></td>
              <td><?= ($row['particular_type']) ? ($row['particular_type'] === 'd' ? 'Daily' : 'Monthly') : '' ?></td>
      				<td><?= $row['type'] === 'a' ? 'Additionals' : 'Deductions' ?></td>
      			</tr>
      		<?php endforeach;?>
      	</tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>