
<?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="tx-12">
        <th><?php echo e($item->stock_code); ?></th>
        <td><?php echo e($item->inv_code); ?></td>
        <td><?php echo e($item->description); ?></td>
        <td><?php echo e($item->serial_no); ?></td>
        <td><?php echo e($item->oem_id); ?></td>
        <td><?php echo e($item->uom); ?></td>
        <td><?php echo e($item->expense_type); ?></td>
        <td>
            <a href="/item/edit/<?php echo e($item->id); ?>" title="Edit Item" class="btn btn-xs btn-primary btn-sm">
                <i class="fa fa-edit"></i>
            </a>
            <a href="/item/delete/<?php echo e($item->id); ?>" title="Delete Item" class="btn btn-xs btn-danger btn-sm">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
	<tr><td colspan="8"><center>Stock item not founds</center></td></tr>
<?php endif; ?>