
<?php $__env->startSection('title', 'Bảng Điều Khiển'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6">
        <h1 class="text-2xl font-bold text-primary flex items-center gap-2">
            <img src="<?php echo e(asset('assets/img/icons/dashboard1.svg')); ?>" class="w-10 h-10" alt="chart">Tổng Quan Doanh Số
        </h1>
        <a href="<?php echo e(route('seller.accounts.create')); ?>"
           class="mt-3 sm:mt-0 inline-flex items-center gap-2 bg-gradient-to-r from-primary to-secondary text-white font-medium px-4 py-2 rounded-lg hover:scale-105 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4" />
            </svg>
            Thêm Tài Khoản
        </a>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
<div class="bg-white rounded-xl p-5 border-t-4 border-black hover:bg-gray-50 transition">
  <div class="flex items-center gap-2">
    <p class="text-gray-500">Tổng Doanh Thu Tháng</p>

    <form method="POST" action="<?php echo e(route('seller.dashboard')); ?>" class="inline">
        <?php echo csrf_field(); ?>
        <select name="month" onchange="this.form.submit()"
            class="font-bold text-green-600 bg-transparent cursor-pointer text-xl outline-none">
            <?php for($m = 1; $m <= 12; $m++): ?>
                <option value="<?php echo e($m); ?>" <?php echo e($m == ($selectedMonth ?? now()->month) ? 'selected' : ''); ?>>
                    <?php echo e($m); ?>

                </option>
            <?php endfor; ?>
        </select>
    </form>
  </div>

  <h2 class="text-2xl font-bold text-black mt-2">
      <?php echo e(number_format($totalRevenue ?? 0)); ?>

      <span class="text-sm text-gray-500">VNĐ</span>
  </h2>
</div>

        <div class="bg-white rounded-xl p-5 border-t-4 border-yellow-500 hover:bg-gray-50 transition">
            <p class="text-gray-500">Tổng Tiền Đã Bán Hôm Nay</p>
            <h2 class="text-2xl font-bold text-yellow-600 mt-2"><?php echo e(number_format($revenueToday)); ?> VND</h2>
        </div>
        <div class="bg-white rounded-xl p-5 border-t-4 border-red-500 hover:bg-gray-50 transition">
            <p class="text-gray-500">Tài Khoản Đã Bán Hôm Nay</p>
            <h2 class="text-2xl font-bold text-red-600 mt-2"><?php echo e($soldToday); ?></h2>
        </div>

        <div class="bg-white rounded-xl p-5 border-t-4 border-green-500 hover:bg-gray-50 transition">
            <p class="text-gray-500">Tài Khoản Đã Bán</p>
            <h2 class="text-2xl font-bold text-green-600 mt-2"><?php echo e($soldAccounts); ?></h2>
        </div>

        <div class="bg-white rounded-xl p-5 border-t-4 border-orange-500 hover:bg-gray-50 transition">
            <p class="text-gray-500">Tài Khoản Chưa Bán</p>
            <h2 class="text-2xl font-bold text-orange-600 mt-2"><?php echo e($availableAccounts); ?></h2>
        </div>
    </div>
    
    <div class="bg-white rounded-xl mt-8 p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-secondary mb-4 flex items-center gap-2">
            <img src="<?php echo e(asset('assets/img/icons/chart.svg')); ?>" class="w-6 h-6" alt="chart">Biểu Đồ Doanh Thu Tháng <?php echo e(now()->format('m')); ?>

        </h3>
        <div class="h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    // Gradient tone cam - xanh
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, '#f97316'); // xanh nhạt
    gradient.addColorStop(1, '#6366f1'); // cam đậm

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($chartLabels, 15, 512) ?>,
            datasets: [{
                label: 'Doanh Thu (VNĐ)',
                data: <?php echo json_encode($chartData, 15, 512) ?>,
                backgroundColor: gradient,
                borderRadius: 8,
                barThickness: 24,
                hoverBackgroundColor: '#4f46e5',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1f2937',
                    titleFont: { size: 13, family: 'Inter, sans-serif' },
                    bodyFont: { size: 12 },
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y.toLocaleString()} VNĐ`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#6b7280',
                        font: { size: 12, family: 'Inter, sans-serif' }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(229,231,235,0.6)', drawBorder: false },
                    ticks: {
                        color: '#6b7280',
                        font: { size: 12 },
                        callback: v => v.toLocaleString()
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.seller.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/seller/dashboard.blade.php ENDPATH**/ ?>