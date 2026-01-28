
<?php $__env->startSection('title', 'DASHBOARD'); ?>
<?php $__env->startSection('content'); ?>
<style>
.key-select {
    width: 80px;
    padding: 0.2rem;
    font-size: 0.85rem;
    line-height: 1;
    border: 1px solid #00CD66;
    border-radius: 5px;
    background-color: #00CD66;
    color: #000000;
}

.key-select:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

</style>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>DASHBOARD</h4>
                </div>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger">
                    <strong>Lỗi!</strong> Đã xảy ra lỗi khi tải dữ liệu dashboard. Vui lòng thông báo cho quản trị viên.
                    <?php if(config('app.debug')): ?>
                        <p><?php echo e($error); ?></p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Thống kê tài khoản -->
<div class="row">
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count das3">
            <div class="dash-counts">
                <h4><?php echo e($statistics['users']['total']); ?></h4>
                <h5>TỔNG NGƯỜI DÙNG</h5>
            </div>
            <div class="dash-imgs">
                <i data-feather="users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6 col-12 d-flex">
        <div class="dash-count das3">
            <div class="dash-counts">
                <h4><?php echo e($statistics['users']['new_today']); ?></h4>
                <h5>NGƯỜI DÙNG HÔM NAY</h5>
            </div>
            <div class="dash-imgs">
                <i data-feather="user-plus"></i>
            </div>
        </div>
    </div>

                <div class="card">
    <div class="card-header"><h4 class="card-title">TỔNG GIAO DỊCH</h4></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center">
                <thead class="bg-light">
    <tr>
        <th>Loại</th>
        <th>Hôm Qua</th>
        <th>Hôm Nay</th>
        <th>Tuần Này</th>
        <th>Tháng Này</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="text-start">Nạp Tiền</td>
        <td><?php echo e(number_format($transactionSummary['deposit']['yesterday'])); ?> VNĐ</td>
        <td><?php echo e(number_format($transactionSummary['deposit']['day'])); ?> VNĐ</td>
        <td><?php echo e(number_format($transactionSummary['deposit']['week'])); ?> VNĐ</td>
        <td><?php echo e(number_format($transactionSummary['deposit']['month'])); ?> VNĐ</td>
    </tr>
    <tr>
        <td class="text-start">Mua Hàng</td>
        <td><?php echo e(number_format($transactionSummary['purchase']['yesterday'])); ?> VNĐ</td>
        <td><?php echo e(number_format($transactionSummary['purchase']['day'])); ?> VNĐ</td>
        <td><?php echo e(number_format($transactionSummary['purchase']['week'])); ?> VNĐ</td>
        <td><?php echo e(number_format($transactionSummary['purchase']['month'])); ?> VNĐ</td>
    </tr>
</tbody>

            </table>
        </div>
    </div>
</div>
                <!-- Giao dịch gần đây -->
                <div class="card mb-0">
                    <div class="card-header">
                        <h4 class="card-title">LỊCH SỬ GIAO DỊCH</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive dataview">
                            <table class="table datatable">
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
                                    <?php $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($transaction->id); ?></td>
                                            <td class="productimgname">
                                                <a
                                                    href="<?php echo e(route('admin.users.show', ['id' => $transaction->user->id])); ?>"><?php echo e($transaction->user->username ?? 'N/A'); ?></a>
                                            </td>
                                            <td>
                                                <?php if($transaction->type == 'deposit'): ?>
                                                    <span class="badge bg-success">NẠP TIỀN</span>
                                                <?php elseif($transaction->type == 'purchase'): ?>
                                                    <span class="badge bg-primary">MUA HÀNG</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary"><?php echo e($transaction->type); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e(number_format($transaction->amount)); ?> VNĐ</td>
                                            <td><?php echo e(number_format($transaction->balance_before)); ?> VNĐ</td>
                                            <td><?php echo e(number_format($transaction->balance_after)); ?> VNĐ</td>
                                            <td><?php echo e($transaction->description ?? 'N/A'); ?></td>
                                            <td><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>