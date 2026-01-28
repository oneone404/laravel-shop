<?php $__env->startSection('title', 'Đăng Ký'); ?>
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
                    <h1 class="register-title">Đăng Ký Tài Khoản</h1>
                </div>
                <?php if (isset($component)) { $__componentOriginal9b1df53224e42948610ceb30d6d57a7c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9b1df53224e42948610ceb30d6d57a7c = $attributes; } ?>
<?php $component = App\View\Components\AlertError::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('alert-error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AlertError::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9b1df53224e42948610ceb30d6d57a7c)): ?>
<?php $attributes = $__attributesOriginal9b1df53224e42948610ceb30d6d57a7c; ?>
<?php unset($__attributesOriginal9b1df53224e42948610ceb30d6d57a7c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9b1df53224e42948610ceb30d6d57a7c)): ?>
<?php $component = $__componentOriginal9b1df53224e42948610ceb30d6d57a7c; ?>
<?php unset($__componentOriginal9b1df53224e42948610ceb30d6d57a7c); ?>
<?php endif; ?>
                <form method="POST" action="<?php echo e(route('register')); ?>" class="register-form">
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
                            name="username" value="<?php echo e(old('username')); ?>" required autocomplete="username" autofocus
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
                            name="password" required autocomplete="new-password"
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

                    <button type="submit" class="register-btn">
                        Đăng Ký
                    </button>
                    <div class="login-link">
                        Đã Có Tài Khoản? <a href="<?php echo e(route('login')); ?>">Đăng Nhập Ngay</a>
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

<?php echo $__env->make('layouts.user.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/user/register.blade.php ENDPATH**/ ?>