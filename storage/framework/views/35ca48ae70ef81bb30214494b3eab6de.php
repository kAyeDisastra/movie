<?php $__env->startSection('title', 'Riwayat Transaksi'); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="content py-4">
        <div class="container">
            <h3 class="my-4">Riwayat Transaksi</h3>
            
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Order #<?php echo e($transaction->id); ?></strong>
                            <span class="badge bg-<?php echo e($transaction->status == 'confirmed' ? 'success' : 'warning'); ?>">
                                <?php echo e(ucfirst($transaction->status)); ?>

                            </span>
                        </div>
                        <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('d M Y, H:i')); ?></small>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = $transaction->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row mb-2">
                                <div class="col-md-8">
                                    <h6><?php echo e($detail->seat->schedule->film->title ?? 'Film tidak ditemukan'); ?></h6>
                                    <small class="text-muted">
                                        Studio: <?php echo e($detail->seat->schedule->studio->name ?? '-'); ?> | 
                                        Kursi: <?php echo e($detail->seat->seat_code ?? '-'); ?> | 
                                        Tanggal: <?php echo e(\Carbon\Carbon::parse($detail->seat->schedule->show_date)->format('d M Y')); ?> | 
                                        Jam: <?php echo e(\Carbon\Carbon::parse($detail->seat->schedule->show_time)->format('H:i')); ?>

                                    </small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <strong>Rp <?php echo e(number_format($detail->seat->schedule->price->amount ?? 50000, 0, ',', '.')); ?></strong>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                                <strong>Total</strong>
                            </div>
                            <div class="col-md-4 text-end">
                                <strong>Rp <?php echo e(number_format($transaction->payment->total_amount ?? 0, 0, ',', '.')); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="alert alert-info text-center">
                    <h5>Belum ada transaksi</h5>
                    <p>Anda belum melakukan pemesanan tiket</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ThinkPad\movie-ticket-laravel\resources\views/transactions.blade.php ENDPATH**/ ?>