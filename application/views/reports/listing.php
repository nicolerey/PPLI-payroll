<section class="content-header">
  <h1>
    Employee Reports
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('employee_reports/create')?>"><i class="fa fa-plus"></i> Make a report</a>
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
              <th>#</th><th>Report date</th><th>Title</th><th>Employee</th><th>Created by</th><th>Status</th><th></th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($data)):?>
              <tr><td class="text-center" colspan="6">Nothing to display</td></tr>
            <?php else:?>
              <?php foreach($data as $reports):?>
                <tr>
                  <td>
                    <a href="<?= base_url("employee_reports/view/{$reports['id']}");?>"><?= str_pad($reports['id'], 4, 0, STR_PAD_LEFT)?></a>
                  </td>
                  <td>
                    <?= date_format(date_create($reports['date']), 'm/d/Y');?>
                  </td>
                  <td>
                    <?= $reports['title'];?>
                  </td>
                  <td>
                    <?= "{$reports['employee_name']['firstname']} {$reports['employee_name']['middleinitial']}. {$reports['employee_name']['lastname']}";?>
                  </td>
                  <td>
                    <?= "{$reports['created_by']['firstname']} {$reports['created_by']['middleinitial']}. {$reports['created_by']['lastname']}";?>
                  </td>
                  <td>
                    <span class="label label-<?= ($reports['status'])?'success':'warning';?>">
                      <?= ($reports['status'])?'Resolved':'Unresolved';?>
                    </span>
                  </td>
                  <td>
                    <?php if(!$reports['status']):?>
                      <button type="button" pk="<?= $reports['id'];?>" data-url="<?= base_url('employee_reports/delete');?>" class="btn btn-flat btn-danger btn-xs" onclick="delete_report(this);">
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