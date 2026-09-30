
<?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="tx-12">
        <th scope="row" class="wd-5p"><a href="/item/details/<?php echo e($item->id); ?>" target="_blank"><?php echo e($item->id); ?></a></th>
        <td class="wd-390"><?php echo e(strtoupper($item->description)); ?></td>
        <td><?php echo e($item->expense_type); ?></td>
        <td><?php echo e($item->serial_no); ?></td>
        <td><?php echo e($item->cost); ?></td>
        <td><?php echo e($item->asset_code); ?></td>
        <td><?php echo e($item->po_no); ?></td>
        <td><?php echo e($item->dr_no); ?></td>
        <td>
            <?php 
                $check = \App\Items::check_item_status($item->id); 
            ?>
            <?php if($check == 1): ?>
                <a href="/item/edit/<?php echo e($item->id); ?>" title="Edit Item" class="btn btn-xs btn-primary btn-sm">
                    <i class="fa fa-edit"></i>
                </a>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="11"><center>Item not found</center></td>
    </tr>
<?php endif; ?>