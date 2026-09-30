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
            <h4 class="mg-b-0 tx-spacing--1">Par v1 OPEN Transactions<br></h4>
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
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Type Employee ID, Name or Transaction Code" value="<?php if(isset($_GET['search'])): ?><?php echo e($_GET['search']); ?><?php endif; ?>">
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-4 mg-t-2">
                                            <button class="btn btn-sm btn-primary btn-uppercase w-100" type="submit">
                                                Generate
                                            </button>
                                        </div>

                                        <?php if(isset($qry)): ?>
                                            <div class="col-4 mg-t-2">
                                                <a class="btn btn-sm btn-success btn-uppercase text-white w-100"
                                                    href="<?php echo e(route('report.parv1_transactions_export', [
                                                            'dept' => request('dept','ALL'),
                                                            'search' => request('search','ALL')
                                                    ])); ?>">
                                                        Export to Excel
                                                </a>

                                                
                                            </div>
                                        <?php endif; ?>

                                        
                                        <div class="col-1 mg-t-2">
                                            <a class="btn btn-sm btn-uppercase text-primary w-100" href="<?php echo e(route('report.parv1_transactions')); ?>">
                                                <i class="fa fa-sync"></i>
                                            </a>
                                        </div>
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
                        <th>Emp ID</th>
                        <th>Accountable</th>
                        <th>Dept</th>
                        <th>PAR#</th>
                        <th>Date</th>
                        <th>Tracking #</th>
                        <th>Description</th>
                        <th>PAR Status</th>
                        <th>Item Status</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Cost</th>
                        <th>Added by</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($qry)): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $qry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="tx-13">
                            <td><?php echo e($d->empid); ?></td>
                            <td><?php echo e($d->fullname); ?></td>
                            <td><?php echo e($d->dept); ?></td>
                            <td><?php echo e($d->id); ?></td>                    
                            <td><?php echo e($d->documentDate); ?></td>
                            <td><?php echo e($d->item_tracking); ?></td>
                            <td><?php echo e($d->item_name); ?></td>                    
                            <td><?php echo e(strtoupper($d->docStatus)); ?></td>
                            <td><?php echo e(strtoupper($d->status)); ?></td>
                            <td class="text-right"><?php echo e($d->item_qty); ?></td>
                            <td class="text-right"><?php echo e($d->item_price); ?></td>
                            <td><?php echo e($d->addedBy); ?></td>
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


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>