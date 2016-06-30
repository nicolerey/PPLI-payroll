<section class="content-header">
  <h1>
    Bonus
    <a class="btn btn-flat btn-default pull-right btn-sm" href="<?= base_url('bonus/create')?>"><i class="fa fa-plus"></i> Add bonus</a>
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
              <th>#</th><th>Date</th><th>Particular</th><th>Type</th><th>Multiplier</th><th>Total</th><th>Created by</th><th>Status</th><th></th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($data)):?>
              <tr><td class="text-center" colspan="7">Nothing to display</td></tr>
            <?php else:?>
              <?php foreach($data as $bonus):?>
                <tr>
                  <td>
                    <a href="<?= base_url("bonus/view/{$bonus['id']}");?>"><?= str_pad($bonus['id'], 4, 0, STR_PAD_LEFT)?></a>
                  </td>
                  <td>
                    <?= date_format(date_create($bonus['date']), 'm/d/Y');?>
                  </td>
                  <td>
                    <?= $bonus['pay_modifier'];?>
                  </td>
                  <td>
                    <?= ($bonus['type']=='dep')?'Department':'Employee';?>
                  </td>
                  <td>
                    <?= $bonus['multiplier'];?>
                  </td>
                  <td>
                    <?= number_format($bonus['total'], 2);?>
                  </td>
                  <td>
                    <?= "{$bonus['created_by']['lastname']}, {$bonus['created_by']['firstname']} {$bonus['created_by']['middleinitial']}.";?>
                  </td>
                  <td>
                    <span class="label label-<?= ($bonus['status'])?'success':'warning';?>">
                      <?= ($bonus['status'])?'Approved':'Pending';?>
                    </span>
                  </td>
                  <td>
                    <?php if(!$bonus['status']):?>
                      <button type="button" pk="<?= $bonus['id'];?>" data-url="<?= base_url('bonus/delete');?>" class="btn btn-flat btn-danger btn-xs" onclick="delete_report(this);">
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