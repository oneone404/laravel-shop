<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a class="btn btn-searchset">
                                    <img src="<?php echo e(asset('assets/img/icons/search-white.svg')); ?>" alt="img">
                                </a>
                                <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                    <label>
                                        <input type="search" class="form-control form-control-sm"
                                            placeholder="Tìm Kiếm">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>USER</th>
                                    <th>LOẠI GIAO DỊCH</th>
                                    <th>SỐ TIỀN</th>
                                    <th>SỐ DƯ TRƯỚC</th>
                                    <th>SỐ DƯ SAU</th>
                                    <th>MÔ TẢ</th>
                                    <th>THỜI GIAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.users.show', $transaction->user_id)); ?>">
                                                <?php echo e($transaction->user->username ?? 'N/A'); ?>

                                            </a>
                                        </td>
                                        <td>
                                            <?php echo display_status_transactions_admin($transaction->type); ?>


                                        </td>
                                        <td>
                                            <?php if($transaction->amount > 0): ?>
                                                <span class="text-success">+<?php echo e(number_format($transaction->amount)); ?>

                                                    đ</span>
                                            <?php else: ?>
                                                <span class="text-danger"><?php echo e(number_format($transaction->amount)); ?> đ</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(number_format($transaction->balance_before)); ?> đ</td>
                                        <td><?php echo e(number_format($transaction->balance_after)); ?> đ</td>
                                        <td><?php echo e($transaction->description); ?></td>

                                        <td><?php echo e($transaction->created_at->format('d/m/Y H:i:s')); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/admin/history/transactions.blade.php ENDPATH**/ ?>