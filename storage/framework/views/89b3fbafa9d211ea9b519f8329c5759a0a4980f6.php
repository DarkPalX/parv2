<?php $__env->startSection('pagecss'); ?>
    <link href="<?php echo e(asset('assets/lib/select2/css/select2.min.css')); ?>" rel="stylesheet">
    <style>
        .content {
            overflow: hidden;
        }
        @media  print{
            .filter { display: none; }
            .b-head { display: none; }
            .content-footer { display: none; }
            .btnCSV { display: none; }
            .dept_header { display: none; }
            .p_header { display: block !important; }
            .btnPrint { display: none; }
        }

        .datepickerlabel {
            position: absolute; 
            z-index: 1;

            margin: 0px; 
            padding: 0px 15px;
            
            left: 5px; 
            bottom: 100%; 

            color: #596882;
        }
        
        .docdate {
            color: #c0ccda;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-sm-flex align-items-center justify-content-between mg-b-20 mg-lg-b-25 mg-xl-b-30">
        <div class="b-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1 mg-b-10">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Reports</li>
                </ol>
            </nav>
            <h4 class="mg-b-0 tx-spacing--1">Par Summary Report<br><small>Open PAR for each dept</small></h4>
        </div>
    </div>

    <div class="row filter">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
            <div class="card">
                <div class="card-body">
                    <form autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <div class="form-group-inner">
                            <div class="row mg-b-10">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    
                                    <select name="dept" id="dept" class="form-control">
                                        <option value="ALL"> - ALL dept -</option>
                                        <?php $__currentLoopData = $dept; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $de): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($de->dept); ?>" 
                                                <?php if(isset($_GET['dept']) && $_GET['dept'] == $de->dept): ?> selected <?php endif; ?>
                                                ><?php echo e($de->dept); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 mg-t-2">
                                            <button class="btn btn-sm pd-x-15 btn-primary btn-uppercase mg-l-5" type="submit">Generate</button>
                                        </div>
                                        <?php if(isset($qry)): ?>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 mg-t-2">
                                                
                                                <a class="btn btn-sm pd-x-15 btn-success btn-uppercase mg-l-5 text-white" href="<?php echo e(route('report.export_department', ['dept' => $_GET['dept']])); ?>">Export to Excel</a>
                                                
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>          
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Dept</th>
                        <th>Accountable</th>
                        <th>Refcode</th>
                        <th>Date</th>
                        <th>Serial#</th>
                        <th>Ref Doc</th>
                        <th>Stock Code</th>
                        <th>Description</th>
                        <th>PAR Status</th>
                        <th>Item Status</th>
                        <th>Qty</th>
                        <th>Cost</th>
                        <th>Added by</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($qry)): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $qry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="tx-13">
                            
                            <td><?php if(isset($_GET['dept'])): ?> <?php echo e($_GET['dept']); ?> <?php endif; ?></td>
                            <td><?php echo e($d->accountable); ?></td>
                            <td><?php echo e($d->refcode); ?></td>                    
                            <td><?php echo e($d->document_date); ?></td>
                            <td><?php echo e($d->detail_serial_no); ?></td>
                            <td><?php echo e($d->doc_ref); ?></td>
                            <td><?php echo e($d->stock_code); ?></td>
                            <td><?php echo e($d->description); ?></td>                    
                            <td><?php echo e(strtoupper($d->doc_status)); ?></td>
                            <td><?php echo e(strtoupper($d->status)); ?></td>
                            <td class="text-right"><?php echo e($d->qty); ?></td>
                            <td class="text-right"><?php echo e($d->cost); ?></td>
                            <td><?php echo e($d->added_by); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php endif; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if(isset($qry)): ?> <?php echo e($qry->appends(request()->query())->links()); ?> <?php endif; ?>
        </div>

    </div>

    <center><img id="loader" src="<?php echo e(asset('assets/img/spinner/spinner10.gif')); ?>"  style="display:none;height:100px;"></center>
        
    <div style="display: none;" class="row mg-t-10 p_header">
        <div class="col-md-12">
            <div class="d-flex flex-row justify-content-start bg-gray-200 mg-b-10">
                <div class="pd-10"><img style="height: 80px;" src="<?php echo e(asset('images/logo_default.jpg')); ?>" alt=""></div>
                <div class="pd-10 mg-t-20"><h2>Philsaga Mining Corporation</h2><span>Purok 1-A Bayugan, Rosario, Agusan Del Sur</span></div>
                <div class="pd-10"></div>
            </div>
            <div class="d-flex justify-content-end"><?php echo e(Carbon\Carbon::now()->format('F d, Y')); ?></div>
        </div>
    </div>

    <div class="row">
        <div id="department_tbl"> </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('pagejs'); ?>
    <script src="<?php echo e(asset('assets/lib/jqueryui/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/lib/select2/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('scripts/report.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>