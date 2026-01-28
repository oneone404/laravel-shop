

<?php $__env->startSection('title', 'Đăng Nhập'); ?>
<?php $__env->startPush('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/auth.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<style>
.containerv2{
    padding: 0px;
}
</style>
    <section class="register-section">
        <div class="containerv2">
            <div class="register-container">
                <div class="register-header">
                    <h1 class="register-title">Đăng Nhập</h1>
                </div>
                <?php if(session('error')): ?>
                    <div class="service__alert service__alert--error">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <span><?php echo e(session('error')); ?></span>
                        </div>
                        <button type="button" class="service__alert-close">&times;</button>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('login')); ?>" class="register-form">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label for="username" class="form-label">Tên Tài Khoản</label>
                        <input id="username" type="text"
                            class="form-input <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            name="username" value="<?php echo e(old('username')); ?>" required autofocus
                            placeholder="Nhập Username...">
                        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="form-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password"
                            class="form-input <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            name="password" required autocomplete="current-password"
                            placeholder="Nhập Password...">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="form-error"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <input type="hidden" name="remember" value="on">
                    <button type="submit" class="register-btn">
                        Đăng Nhập
                    </button>
                    <div class="login-link">
                        Chưa Có Tài Khoản? <a href="<?php echo e(route('register')); ?>">Đăng Ký Nhanh</a>
                    </div>
                    <?php if(config_get('login_social.google.active', false) || config_get('login_social.facebook.active', false)): ?>
                        <div class="social-login">
                            <p class="social-login-text">Hoặc</p>
                            <div class="social-login-buttons">
                                <?php if(config_get('login_social.google.active', false)): ?>
                                    <a href="<?php echo e(route('auth.google')); ?>" class="google-login-btn">
                                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google icon" style="width: 20px; margin-right: 10px;">
                                        <span>Đăng Nhập Bằng Google</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/login.blade.php ENDPATH**/ ?>