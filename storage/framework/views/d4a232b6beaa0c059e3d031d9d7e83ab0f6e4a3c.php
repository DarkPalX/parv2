
<?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="tx-12">
        <th><?php echo e($item->inv_code); ?></th>
        <td><?php echo e($item->stock_code); ?></td>
        <td><?php echo e($item->description); ?></td>
        <td><?php echo e($item->oem_id); ?></td>
        <td><?php echo e($item->uom); ?></td>
        <td class="d-flex justify-content-end">
        	<a href="/create/item/<?php echo e($item->stock_code); ?>" target="_blank" class="btn btn-sm btn-primary mg-r-5"><i class="fa fa-share"></i></a>
        	<a href="#" data-toggle="modal" data-target="#delete-stock" data-id="<?php echo e($item->id); ?>" class="btn btn-sm btn-danger stock_delete"><i class="fa fa-trash"></i></a>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
	<tr><td colspan="6"><center>Stock code not found</center></td></tr>
<?php endif; ?>