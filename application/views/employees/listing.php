<section class="content-header">
  <h1>
    Employees
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('employees/create')?>"><i class="fa fa-plus"></i> Add new employee</a>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
    <div class="box-body no-padding">
      <table class="table table-hover table-striped">
      	<thead>
			<tr>
        <th><i class="fa fa-lock"></i></th><th>Employee #</th><th>Last Name</th><th>First name</th><th>Middle initial</th><th>Gender</th><th>Department</th><th>Position</th><th>Date hired</th><th></th>
      </tr>      		
      	</thead>
      	<tbody>
          <?php if(empty($items)):?>
            <tr><td class="text-center" colspan="10">Nothing to display</td></tr>
          <?php endif;?>
      		<?php foreach($items AS $row):?>
      			<tr data-pk="<?= $row['id']?>" class="employee_row_fields">
              <td><input type="checkbox" class="lock" <?= intval($row['is_locked']) ? 'checked="checked"' : ''?>/></td>
              <td><a href="<?= base_url("employees/edit/{$row['id']}")?>"><?= $row['id']?></a></td>
      				<td><?= $row['lastname']?></td>
      				<td><?= $row['firstname']?></td>
      				<td><?= $row['middleinitial']?></td>
      				<td><?= $row['gender'] === 'M' ? 'Male' : 'Female' ?></td>
              <td><?= $row['department'] ?></td>
              <td><?= $row['position'] ?></td>
              <td><?= date('m/d/y', strtotime($row['date_hired']))?></td>
              <td>
                <button type="button" delete_url="<?= base_url('employees/delete/'.$row['id']);?>" class="btn btn-flat btn-danger btn-xs" onclick="delete_employee(this);">
                  <span class="glyphicon glyphicon-remove"></span> Delete
                </button>
              </td>
      			</tr>
      		<?php endforeach;?>
      	</tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
  <input type="hidden" id="lockUrl" data-value="<?= base_url('employees/toggle_lock')?>" disabled />
</section>
