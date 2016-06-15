<section class="content-header">
  <h1>
    Salaries
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('salaries/create')?>"><i class="fa fa-plus"></i> Add new salary</a>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-body no-padding">
      <table class="table table-hover table-striped">
      	<thead>
			   <tr><th>Position</th></tr>      		
      	</thead>
      	<tbody>
      		<?php foreach($items AS $row):?>
      			<tr><td><a href="<?=base_url("salaries/edit/{$row['id']}")?>"><?= $row['name']?></a></td></tr>
      		<?php endforeach;?>
      	</tbody>
      </table>
    </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>