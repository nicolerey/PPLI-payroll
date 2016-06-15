<section class="content-header">
  <h1>
    Positions
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('positions/create')?>"><i class="fa fa-plus"></i> Add new position</a>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
    <div class="box-body no-padding">
      <table class="table table-hover table-striped">
      	<thead>
			<tr><th>Name</th><th>Login account type</th><th>Workday/s</th><th></th></tr>      		
      	</thead>
      	<tbody>
          <?php if(empty($items)):?>
            <tr><td class="text-center" colspan="4">Nothing to display</td></tr>
          <?php endif;?>
      		<?php foreach($items AS $row):?>
      			<tr>
              <td><a href="<?= base_url("positions/edit/{$row['id']}")?>"><?= $row['name']?></a></td>
              <td><?= account_type($row['attendance_type'])?></td>
              <td>
                <?php if($row['workday']!=NULL):?>
                  <?php $work = json_decode($row['workday']);?>
                  <?php foreach($work as $workday):?>
                    <?= substr($days[$workday->day], 0, 3);?>
                    <?= (end($work)!=$workday)?"|":""; ?>
                  <?php endforeach;?>
                <?php endif;?>
              </td>
              <td>
              <button type="button" delete_url="<?= base_url('positions/delete/'.$row['id']);?>" class="btn btn-flat btn-danger btn-xs" onclick="delete_positions(this);">
                  <span class="glyphicon glyphicon-remove"></span> Delete
                </button>
              </td>
      			</tr>
      		<?php endforeach;?>
      	</tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>