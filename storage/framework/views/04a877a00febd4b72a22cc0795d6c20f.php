<?php $__env->startSection('title', $title); ?>
<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Cài đặt chung</h4>
                    <h6>Quản lý thông tin chung của website</h6>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('admin.settings.general.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Tên trang web <span class="text-danger">*</span></label>
                                    <input type="text" name="site_name"
                                        class="form-control <?php $__errorArgs = ['site_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('site_name', $configs['site_name'])); ?>"
                                        placeholder="Nhập tên trang web">
                                    <?php $__errorArgs = ['site_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Từ khóa website</label>
                                    <input type="text" name="site_keywords"
                                        class="form-control <?php $__errorArgs = ['site_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('site_keywords', $configs['site_keywords'])); ?>"
                                        placeholder="Nhập từ khóa website: shopacc, lienquan, accgame, ...">
                                    <?php $__errorArgs = ['site_keywords'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Mô tả trang web</label>
                                    <textarea name="site_description"
                                        class="form-control <?php $__errorArgs = ['site_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"
                                        placeholder="Nhập mô tả trang web"><?php echo e(old('site_description', $configs['site_description'])); ?></textarea>
                                    <?php $__errorArgs = ['site_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Email liên hệ <span class="text-danger">*</span></label>
                                    <input type="text" name="email"
                                        class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('email', $configs['email'])); ?>" placeholder="Nhập email liên hệ">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="phone"
                                        class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('phone', $configs['phone'])); ?>" placeholder="Nhập số điện thoại">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Địa chỉ</label>
                                    <input type="text" name="address"
                                        class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('address', $configs['address'])); ?>" placeholder="Nhập địa chỉ">
                                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Logo chính trang web</label>
                                    <div class="image-upload">
                                        <input type="file" name="site_logo"
                                            class="form-control <?php $__errorArgs = ['site_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*"
                                            onchange="previewImage(this, 'preview-logo')">
                                        <?php $__errorArgs = ['site_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="image-uploads">
                                            <img src="<?php echo e(asset('assets/img/icons/upload.svg')); ?>" alt="img">
                                            <h4>Kéo thả hoặc click để tải logo lên</h4>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!empty($configs['site_logo'])): ?>
                                    <div class="form-group mt-3">
                                        <label>Logo hiện tại:</label>
                                        <div>
                                            <img id="preview-logo" src="<?php echo e($configs['site_logo']); ?>" alt="Logo"
                                                class="img-fluid mt-2" style="max-height: 100px;">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group mt-3">
                                        <img id="preview-logo" src="" alt="Logo Preview" class="img-fluid mt-2"
                                            style="max-height: 100px; display: none;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Logo Dark Mode</label>
                                    <div class="image-upload">
                                        <input type="file" name="site_logo_dark"
                                            class="form-control <?php $__errorArgs = ['site_logo_dark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            accept="image/*" onchange="previewImage(this, 'preview-logo-dark')">
                                        <?php $__errorArgs = ['site_logo_dark'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="image-uploads">
                                            <img src="<?php echo e(asset('assets/img/icons/upload.svg')); ?>" alt="img">
                                            <h4>Kéo thả hoặc click để tải logo dark lên</h4>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!empty($configs['site_logo_dark'])): ?>
                                    <div class="form-group mt-3">
                                        <label>Logo Dark hiện tại:</label>
                                        <div>
                                            <img id="preview-logo-dark" src="<?php echo e($configs['site_logo_dark']); ?>" alt="Logo Dark"
                                                class="img-fluid mt-2"
                                                style="max-height: 100px; background: #333; padding: 10px; border-radius: 8px;">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group mt-3">
                                        <img id="preview-logo-dark" src="" alt="Logo Dark Preview" class="img-fluid mt-2"
                                            style="max-height: 100px; display: none; background: #333; padding: 10px; border-radius: 8px;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Logo chân trang</label>
                                    <div class="image-upload">
                                        <input type="file" name="site_logo_footer"
                                            class="form-control <?php $__errorArgs = ['site_logo_footer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            accept="image/jpeg,image/png,image/jpg,image/gif"
                                            onchange="previewImage(this, 'preview-logo-footer')">
                                        <?php $__errorArgs = ['site_logo_footer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="image-uploads">
                                            <img src="<?php echo e(asset('assets/img/icons/upload.svg')); ?>" alt="img">
                                            <h4>Kéo thả hoặc click để tải logo lên</h4>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!empty($configs['site_logo_footer'])): ?>
                                    <div class="form-group mt-3">
                                        <label>Logo chân trang hiện tại:</label>
                                        <div>
                                            <img id="preview-logo-footer" src="<?php echo e($configs['site_logo_footer']); ?>"
                                                alt="Logo Footer" class="img-fluid mt-2" style="max-height: 50px;">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group mt-3">
                                        <img id="preview-logo-footer" src="" alt="Logo Footer Preview" class="img-fluid mt-2"
                                            style="max-height: 50px; display: none;">
                                    </div>
                                <?php endif; ?>
                            </div>


                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Favicon trang web</label>
                                    <div class="image-upload">
                                        <input type="file" name="site_favicon"
                                            class="form-control <?php $__errorArgs = ['site_favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            accept="image/jpeg,image/png,image/jpg,image/gif"
                                            onchange="previewImage(this, 'preview-favicon')">
                                        <?php $__errorArgs = ['site_favicon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="image-uploads">
                                            <img src="<?php echo e(asset('assets/img/icons/upload.svg')); ?>" alt="img">
                                            <h4>Kéo thả hoặc click để tải favicon lên</h4>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!empty($configs['site_favicon'])): ?>
                                    <div class="form-group mt-3">
                                        <label>Favicon hiện tại:</label>
                                        <div>
                                            <img id="preview-favicon" src="<?php echo e($configs['site_favicon']); ?>" alt="Favicon"
                                                class="img-fluid mt-2" style="max-height: 50px;">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group mt-3">
                                        <img id="preview-favicon" src="" alt="Favicon Preview" class="img-fluid mt-2"
                                            style="max-height: 50px; display: none;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Banner trang web</label>
                                    <div class="image-upload">
                                        <input type="file" name="site_banner"
                                            class="form-control <?php $__errorArgs = ['site_banner'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            accept="image/jpeg,image/png,image/jpg,image/gif"
                                            onchange="previewImage(this, 'preview-banner')">
                                        <?php $__errorArgs = ['site_banner'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="image-uploads">
                                            <img src="<?php echo e(asset('assets/img/icons/upload.svg')); ?>" alt="img">
                                            <h4>Kéo thả hoặc click để tải ảnh banner lên</h4>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!empty($configs['site_banner'])): ?>
                                    <div class="form-group mt-3">
                                        <label>Ảnh banner hiện tại:</label>
                                        <div>
                                            <img id="preview-banner" src="<?php echo e($configs['site_banner']); ?>" alt="Banner"
                                                class="img-fluid mt-2" style="max-height: 200px;">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group mt-3">
                                        <img id="preview-banner" src="" alt="Banner Preview" class="img-fluid mt-2"
                                            style="max-height: 200px; display: none;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Ảnh bìa trang web</label>
                                    <div class="image-upload">
                                        <input type="file" name="site_share_image"
                                            class="form-control <?php $__errorArgs = ['site_share_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            accept="image/jpeg,image/png,image/jpg,image/gif"
                                            onchange="previewImage(this, 'preview-share-image')">
                                        <?php $__errorArgs = ['site_share_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <div class="image-uploads">
                                            <img src="<?php echo e(asset('assets/img/icons/upload.svg')); ?>" alt="img">
                                            <h4>Kéo thả hoặc click để tải ảnh bìa lên</h4>
                                        </div>
                                    </div>
                                </div>

                                <?php if(!empty($configs['site_share_image'])): ?>
                                    <div class="form-group mt-3">
                                        <label>Ảnh bìa hiện tại:</label>
                                        <div>
                                            <img id="preview-share-image" src="<?php echo e($configs['site_share_image']); ?>"
                                                alt="Image Share" class="img-fluid mt-2" style="max-height: 200px;">
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="form-group mt-3">
                                        <img id="preview-share-image" src="" alt="Image Share Preview" class="img-fluid mt-2"
                                            style="max-height: 200px; display: none;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-submit me-2">Lưu thay đổi</button>
                                <a href="<?php echo e(route('admin.index')); ?>" class="btn btn-cancel">Hủy bỏ</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + previewId).attr('src', e.target.result);
                    $('#' + previewId).css('display', 'block');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/admin/settings/general.blade.php ENDPATH**/ ?>