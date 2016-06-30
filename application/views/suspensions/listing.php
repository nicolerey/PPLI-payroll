<section class="content-header">
  <h1>
    Employee Suspensions
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('employee_suspensions/create')?>"><i class="fa fa-plus"></i> Make a suspension</a>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
      <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
      <div class="box-body">
        <table class="table table-bordered table-condensed table-striped">
          <thead>
            <tr class="active">
              <th>#</th><th>Start date</th><th>End date</th><th>Title</th><th>Employee</th><th>Created by</th><th>Status</th><th></th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($data)):?>
              <tr><td class="text-center" colspan="7">Nothing to display</td></tr>
            <?php else:?>
              <?php foreach($data as $suspension):?>
                <tr>
                  <td>
                    <a href="<?= base_url("employee_suspensions/view/{$suspension['id']}");?>"><?= str_pad($suspension['id'], 4, 0, STR_PAD_LEFT)?></a>
                  </td>
                  <td>
                    <?= date_format(date_create($suspension['start_date']), 'm/d/Y');?>
                  </td>
                  <td>
                    <?= date_format(date_create($suspension['end_date']), 'm/d/Y');?>
                  </td>
                  <td>
                    <?= $suspension['title'];?>
                  </td>
                  <td>
                    <?= "{$suspension['employee_name']['firstname']} {$suspension['employee_name']['middleinitial']}. {$suspension['employee_name']['lastname']}";?>
                  </td>
                  <td>
                    <?= "{$suspension['created_by']['firstname']} {$suspension['created_by']['middleinitial']}. {$suspension['created_by']['lastname']}";?>
                  </td>
                  <td>
                    <span class="label label-<?= ($suspension['status'])?'success':'warning';?>">
                      <?= ($suspension['status'])?'Approved':'Pending';?>
                    </span>
                  </td>
                  <td>
                    <?php if(!$suspension['status']):?>
                      <button type="button" pk="<?= $suspension['id'];?>" data-url="<?= base_url('employee_suspensions/delete');?>" class="btn btn-flat btn-danger btn-xs" onclick="delete_report(this);">
                        <span class="glyphicon glyphicon-remove"></span> Delete
                      </button>
                    <?php endif;?>
                  </td>
                </tr>
              <?php endforeach;?>
            <?php endif;?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
  </div><!-- /.box -->
</section>