<section class="content-header">
  <h1>
    Departments
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('departments/create')?>"><i class="fa fa-plus"></i> Add new department</a>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
    <div class="box-body no-padding">
      <table class="table table-hover table-striped">
      	<thead>
			<tr><th>Department #</th><th>Name</th><th>Supervisor</th><th></th></tr>      		
      	</thead>
      	<tbody>
          <?php if(empty($items)):?>
            <tr><td class="text-center" colspan="4">Nothing to display</td></tr>
          <?php endif;?>
      		<?php foreach($items AS $row):?>
      			<tr>
              <td><a href="<?= base_url("departments/edit/{$row['id']}")?>"><?= $row['id_number']?></a></td>
      				<td><?= $row['name']?></td>
              <td><?= $row['supervisor']?></td>
              <td>
                <button type="button" delete_url="<?= base_url('departments/delete/'.$row['id']);?>" class="btn btn-flat btn-danger btn-xs" onclick="delete_department(this);">
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