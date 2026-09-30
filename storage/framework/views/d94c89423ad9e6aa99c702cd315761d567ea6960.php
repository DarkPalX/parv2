<?php $__env->startSection('pagecss'); ?>
    <link href="<?php echo e(asset('assets/lib/select2/css/select2.min.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-sm-flex align-items-center justify-content-between mg-b-10 mg-lg-b-25 mg-xl-b-10">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-style1 mg-b-10">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Par Management</li>
            </ol>
        </nav>
        <h4 class="mg-b-0 tx-spacing--1">PPE Issuance Form</h4>
    </div>
</div>

<form autocomplete="off" action="/ppe/store" role="form" action="" method="POST" id="selectForm2" class="parsley-style-1" data-parsley-validate novalidate>
    <?php echo csrf_field(); ?>
    <div class="row row-xs">
        <div class="col-lg-12 mg-t-10">
            <div class="card">
                <div class="card-body">
                    <h4 class="tx-success">Requestor : <?php echo e(str_replace(':','/',$emp)); ?></h4>
                    <input type="hidden" id="total_items" name="total_items" value="0">
                    <div class="divider-text bg-gray-400 text-white">PPE Issuance Details</div>

                    <div class="form-group row">
                        <div class="col-sm-4" style="display: none;">
                            <select class="form-control select2-no-search" name="par_type" id="par_type">
                                <option value="">Select One</option>
                                <option selected value="new">New</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>

                        <label class="col-sm-2 col-form-label">Location <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <select required class="form-control select2-no-search" name="p_location">
                                <option value="">Select One</option>
                                <option value="davao">Davao</option>
                                <option value="agusan">Agusan</option>
                            </select>
                        </div>
                        
                        <label for="inputEmail3" class="col-sm-3 col-form-label d-flex justify-content-end">Doc Date <i class="tx-danger">*</i></label>
                        <div class="col-sm-3">
                            <input readonly type="text" id="datepicker4" name="doc_date" value="<?php echo e(\Carbon\Carbon::today()->format('m/d/Y')); ?>" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">                            
                        <label class="col-sm-2 col-form-label">Site <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <select required class="form-control select2-no-search" name="p_site">
                                <option value="">Select One</option>
                                <option value="mcd mine">MCD Mine</option>
                                <option value="mcd main">MCD Main</option>
                            </select>
                        </div>
                    </div>

                    <div class="divider-text bg-gray-400 text-white">End-User Information</div>
                    <div class="form-group row">                            
                        <label class="col-sm-2 col-form-label">End-User Type <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <select required class="form-control select2-no-search" name="e_type" id="e_type">
                                <option value="">Select One</option>
                                <option value="1">Personal</option>
                                <option value="2">Common</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row" id="empdiv">                            
                        <label class="col-sm-2 col-form-label">Personal <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <input required type="search" name="emp" id="employees" class="form-control" placeholder="Search employee id, employee lastname....">
                            <span><img style="display: none;" id="emp_spinner" class="wd-15p mg-t-4" src="<?php echo e(asset('assets/img/spinner/spinner5.gif')); ?>" alt=""></span>
                            <div id="employee_list"></div>
                        </div>
                    </div>

                    <input type="hidden" id="dept" name="emp_dept">

                    <div class="form-group row" id="deptdiv" style="display: none;">                            
                        <label class="col-sm-2 col-form-label">Common <i class="tx-danger">*</i></label>
                        <div class="col-sm-4">
                            <input type="search" name="dept" id="department" class="form-control" placeholder="Enter department name to search">
                            <span><img style="display: none;" id="dept_spinner" class="wd-15p mg-t-4" src="<?php echo e(asset('assets/img/spinner/spinner5.gif')); ?>" alt=""></span>
                            <div id="department_list"></div>
                        </div>
                    </div>

                    <div class="divider-text bg-gray-400 text-white">Item Details</div>
                    <div class="form-row mg-b-10">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-sm mg-b-0">
                                    <thead class="thead-secondary">
                                        <tr>
                                          <th class="wd-25p">Description</th>
                                          <th class="wd-20p">Color</th>
                                          <th class="wd-5p">Size</th>
                                          <th class="wd-25p">Remarks</th>
                                          <th class="wd-5p">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="addedItems">
                                        <?php $__currentLoopData = $irms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $bal = $i->qty-$i->qtyReleased;
                                            ?>
                                                <tr>
                                                    <td><?php echo e($i->itemDesc); ?></td>
                                                    <td><?php echo e($i->itemColor); ?></td>
                                                    <td><?php echo e($i->itemSize); ?></td>
                                                    <td><?php echo e($i->remarks); ?></td>
                                                    <td>
                                                        <?php if($bal <> 0): ?>
                                                            <?php echo e($bal); ?>

                                                        <?php else: ?>
                                                            <span class="badge badge-success">SERVED</span>            
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php if(($i->qty-$i->qtyReleased) <> 0 ): ?>
                                                <tr>
                                                    <td colspan='6'>
                                                        <div class="form-row">
                                                            <div class="col-md-6">
                                                                <input type="hidden" value="<?php echo e($i->id); ?>" name="request_no[]">
                                                                <input type="hidden" value="0" id="item_<?php echo e($i->id); ?>" name="item_id[]">

                                                                <input type="text" onkeyup="itemSearch($(this).val(),<?php echo e($i->id); ?>)" id="item_search_<?php echo e($i->id); ?>" class="form-control" placeholder="Search item description or Stock Code...">
                                                                <span><img style="display: none;" id="item_spinner_<?php echo e($i->id); ?>" class="wd-10p mg-t-4" src="<?php echo e(asset('assets/img/spinner/spinner5.gif')); ?>" alt=""></span>
                                                                <div id="items_tbl_<?php echo e($i->id); ?>"></div>
                                                            </div>
                                                           <div class="col-md-2">
                                                                <input type="number" class="form-control" name="stockcode[]" id="stockcode_<?php echo e($i->id); ?>" placeholder="Stock Code">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <input type="number" class="form-control" name="qty[]" id="qty_<?php echo e($i->id); ?>" placeholder="Quantity" min="1" max="<?php echo e($i->qty-$i->qtyReleased); ?>">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="divider-text bg-gray-400 text-white">Reference</div> 
                    <div class="form-row mg-b-20">
                        <div class="col-md-4">
                            <label for="department">Issuance Doc Reference(Batch#/MWIS) <i class="tx-danger">*</i></label>
                            <input required type="text" name="doc_ref" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="department">Safety Control # <i class="tx-danger">*</i></label>
                            <input readonly type="text" name="safety" class="form-control" value="<?php echo e($id); ?>">
                        </div>
                    </div> 

                    <div class="form-row">
                        <div class="col-md-12 mg-t-30 d-flex justify-content-end">
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('pagejs'); ?>
    <script src="<?php echo e(asset('scripts/par.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/lib/jqueryui/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/lib/parsleyjs/parsley.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/lib/select2/js/select2.min.js')); ?>"></script>
    <script>
        $(document).ready(function(){

            $('form').submit(function(){
                $(this).find('button[type=submit]').prop('disabled', true);
            });

        });

        $(function(){
            'use strict'
            $('.select2-no-search').select2({
                minimumResultsForSearch: Infinity,
                placeholder: 'Choose One'
            });
        });

        function update_rqcode(){
            var x = $('#item_rq').val();
            var i = x.split("|");
            $('#rq').val(i[0]);
        }

        var typingTimer;
        $('#employees').keydown(function(){
            $('#emp_spinner').show();
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTypingEmployee, 2000);
        });

        function doneTypingEmployee(){
            var query = $('#employees').val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "<?php echo e(route('employee.fetch')); ?>",
                method: "POST",
                data: { query :query, _token:_token },
                success: function(data)
                {
                    $('#emp_spinner').hide();
                    $('#employee_list').fadeIn();
                    $('#employee_list').html(data);
                }
            })
        }


        $('#department').keydown(function(){
                $('#dept_spinner').show();
                clearTimeout(typingTimer);
                typingTimer = setTimeout(doneTypingDepartment, 2000);
            });

            function doneTypingDepartment(){
                var query = $('#department').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "<?php echo e(route('api.dept')); ?>",
                    method: "POST",
                    data: { query :query, _token:_token },
                    success: function(data)
                    {
                        $('#dept_spinner').hide();
                        $('#department_list').fadeIn();
                        $('#department_list').html(data);
                    }
                })
            }

        function itemSearch(itemDesc,controlNum){
            var _token = $('input[name="_token"]').val();

            $('#item_spinner_'+controlNum).show();
            
            if(itemDesc.length > 4){
               $.ajax({
                    url: "<?php echo e(route('item.fetch')); ?>",
                    method: "POST",
                    data: { query :itemDesc, cNum : controlNum, _token:_token },
                    success: function(data)
                    {
                        $('#item_spinner_'+controlNum).hide();
                        $('#items_tbl_'+controlNum).fadeIn();
                        $('#items_tbl_'+controlNum).html(data);

                        saveItemOnInput(controlNum);
                    }
                }) 
            }
        }

        function saveItemOnInput(cNum){
            $(document).on('click','.item_li_'+cNum,function(){
                var string = $(this).text();
                var item   = string.split(" => ");

                $('#item_'+cNum).val(item[0]);
                $('#item_search_'+cNum).val(item[1]);
                $('#items_tbl_'+cNum).fadeOut();
                $("#qty_"+cNum).attr("required",true);
                $("#stockcode_"+cNum).val(item[2]);
            }); 
        }

        $(document).on('click','.emp_li',function(){
            var emp = $(this).text();
            var i = emp.split("=");

            $('#employees').val(i[0]);
            $('#dept').val(i[1]);

            $('#employee_list').fadeOut();
        });

        $(document).on('click','.dept_li',function(){
            $('#department').val($(this).text());
            $('#department_list').fadeOut();
        });

        $('#e_type').on('change', function(){
            if($(this).val() == 1){
                $('#empdiv').show('slow');
                $('#deptdiv').hide('slow');
                $('#department').val('');
                $("#employees").attr("required",true);
                $("#department").attr("required",false);
            } else {
                $('#deptdiv').show('slow');
                $('#empdiv').hide('slow');
                $('#employees').val('');
                $("#department").attr("required",true);
                $("#employees").attr("required",false);
            }
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>