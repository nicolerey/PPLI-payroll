<div>
    <div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th class="col-sm-1"></th>
                <th class="col-sm-3">Particulars</th>
                <th class="col-sm-2">Rate type</th>
                <th class="col-sm-2">Rate</th>
                <th></th>
                <th></th>
                <th class="col-sm-2">Amount</th>
            </tr>
        </thead>
        <tbody class="additional_particulars_container">
            <tr>
                <td></td>
                <td>Basic Rate</td>
                <td class="p_type">Daily</td>
                <td class="basic_rate">
                    <input name="basic_rate"  min="0" step="0.01" class="form-control pformat particular_rate" value="<?= $basic_rate?$basic_rate:0.00;?>"/>
                </td>
                <td>
                    <input type="number" class="form-control days_rendered" name="days_rendered" onchange="change_days(this)" value="0" min="0" />
                </td>
                <td style="padding-right: 50px;">day/s</td>
                <td class="particular_amount">
                    0.00
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Overtime</td>
                <td>Daily</td>
                <td class="overtime_rate">
                    <?= number_format($basic_rate * ($overtime_rate/100), 2);?>
                </td>
                <td>
                    <input type="number" min="0" step="0.01" class="form-control overtime_time" name="overtime_time" value="0.00" onchange="calculate_overtime_amount()" />
                </td>
                <td>hour/s</td>
                <td class="overtime_amount">
                    0.00
                </td>
            </tr>
            <?php foreach($emp_particulars as $emp_part):?>
                <?php if($emp_part['type']=='a'):?>
                    <tr>
                        <td></td>
                        <td>
                            <?= $emp_part['name'];?>
                            <input type="hidden" name="particular_id[]" value="<?= $emp_part['id']?>"/>
                        </td>
                        <td class="p_type"><?= ($emp_part['particular_type']=='d')?'Daily':'Monthly';?></td>
                        <td>
                            <input name="particular_rate[]"  min="0" step="0.01" class="form-control pformat particular_rate" value="<?= $emp_part['amount'];?>"/>
                        </td>
                        <td>
                            <input type="number" class="form-control days_rendered" onchange="change_days(this)" value="0" min="0"/>
                        </td>
                        <td>
                            day/s
                        </td>
                        <td class="particular_amount">
                            0.00
                        </td>
                    </tr>
                <?php endif;?>
            <?php endforeach;?>
            <tr class="dynamic_add_particulars hidden particular_group">
                <td>
                    <button type="button" class="btn btn-flat btn-danger" onclick="delete_particular_group(this);">
                        <span class="glyphicon glyphicon-remove"></span>
                    </button>
                </td>
                <td>
                    <select class="form-control additional_name" onchange="change_particular_type(this);">
                        <option value=""></option>
                        <?php if(!empty($particulars)):?>
                            <?php foreach($particulars as $particular):?>
                            <?php if($particular['type']==='a'):?>
                                <option value="<?= $particular['id'];?>" rate_type="<?= $particular['particular_type'];?>"><?= $particular['name'];?></option>
                            <?php endif;?>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                </td>
                <td class="p_type">-</td>
                <td>
                    <input name=""  min="0" step="0.01" value="0" class="form-control pformat particular_rate"/>
                </td>
                <td>
                    <input type="number" class="form-control days_rendered" name="" value="0" onchange="change_days(this, 1);" min="0"/>
                </td>
                <td style="padding-right: 50px;">day/s</td>
                <td class="particular_amount">
                    0.00
                </td>
            </tr>
        </tbody>
        </table>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-2">
        <button type="button" class="btn btn-flat btn-primary" onclick="add_particular_group(this);">
            <span class="glyphicon glyphicon-plus"></span> Add particular
        </button>
        </div>
        <div class="col-sm-7"></div>
        <div class="col-sm-2">
        <table class="table">
            <tbody>
            <tr>
                <td style="text-align: right;">
                <label class="coltrol-label" style="margin-bottom: 0;"> Total:</label>
                </td>
                <td class="total_additional">0.00</td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
    </div>
    <div class="row">
    <div class="row">
        <label class="col-sm-5 control-label text-danger"> Deductions:</label>
    </div>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-7">
        <table class="table table-hover table-striped text-danger">
            <thead>
                <tr>
                    <th class="col-sm-1"></th>
                    <th class="col-sm-3">Particulars</th>
                    <th class="col-sm-2">Rate type</th>
                    <th></th>
                    <th></th>
                    <th class="col-sm-2">Amount</th>
                </tr>
            </thead>
            <tbody class="deduction_particulars_container">
                <tr>
                    <td></td>
                    <td>Late penalties</td>
                    <td>-</td>
                    <td>
                        <input type="number" class="form-control late_minutes" name="late_minutes" step="0.01" min="0" value="0.00" onchange="calculate_late_amount()" />
                    </td>
                    <td style="padding-right: 50px;">
                        min/s
                        <input type="text" class="hidden late_rate" value="<?= number_format($late_penalty_rate, 2);?>" />
                    </td>
                    <td class="late_amount">
                        0.00
                    </td>
                </tr>
                <?php foreach($emp_particulars as $emp_part):?>
                    <?php if($emp_part['type']=='d'):?>
                        <tr>
                            <td></td>
                            <td><?= $emp_part['name'];?></td>
                            <td><?= ($emp_part['particular_type']=='d')?'Monthly':'Daily';?></td>
                            <td>
                                <input type="number" class="form-control days_rendered" onchange="change_days(this)" value="0" min="0"/>
                            </td>
                            <td style="padding-right: 50px;">
                                day/s
                            </td>
                            <td>
                                <input  min="0" step="0.01" value="<?= $emp_part['amount'];?>" class="form-control pformat deduction_particular_amount" name="particular_rate[]"/>
                                <input type="hidden" name="particular_id[]" value="<?= $emp_part['id']?>"/>
                            </td>
                        </tr>
                    <?php endif;?>
                <?php endforeach;?>
                <?php if($loans):?>
                    <?php foreach($loans as $loan):?>
                      <tr>
                        <td></td>
                        <td>Loan: <?= $loan['loan_name'];?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          <input  min="0" step="0.01" value="<?= $loan['loan_minimum_pay'];?>" class="form-control pformat deduction_particular_amount" name="loan_payment[]"/>
                        </td>
                      </tr>
                    <?php endforeach;?>
                  <?php endif;?>
            <tr class="dynamic_ded_particulars hidden particular_group">
                <td>
                <button type="button" class="btn btn-flat btn-danger" onclick="delete_particular_group(this);">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
                </td>
                <td>
                <select class="form-control deduction_name" onchange="change_particular_type(this);">
                    <option value=""></option>
                    <?php if(!empty($particulars)):?>
                    <?php foreach($particulars as $particular):?>
                        <?php if($particular['type']==='d'):?>
                        <option value="<?= $particular['id'];?>" rate_type="<?= $particular['particular_type'];?>"><?= $particular['name'];?></option>
                        <?php endif;?>
                    <?php endforeach;?>
                    <?php endif;?>
                </select>
                </td>
                <td class="particular_rate_type">-</td>
                <td>
                    <input type="number" class="form-control days_rendered" onchange="change_days(this)" value="0" min="0"/>
                </td>
                <td style="padding-right: 50px;">
                    day/s
                </td>
                <td>
                <input name=""  min="0" step="0.01" value="0" class="form-control pformat deduction_particular_amount"/>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7"></div>
        <div class="col-sm-2">
        <button type="button" class="btn btn-flat btn-primary" onclick="ded_particular_group();">
            <span class="glyphicon glyphicon-plus"></span> Add particular
        </button>
        </div>
        <div class="col-sm-2">
        <table class="table table-hover table-striped">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: right;"><label class="coltrol-label" style="margin-bottom: 0;"> Net Pay:</label></td><td class="net_pay">0.00</td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.pformat').priceFormat({prefix:''});
    });
</script>