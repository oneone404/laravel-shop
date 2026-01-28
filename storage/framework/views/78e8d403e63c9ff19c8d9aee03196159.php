<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="<?php echo e(request()->routeIs('admin.index') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.index')); ?>"><img src="<?php echo e(asset('assets/img/icons/dashboard.svg')); ?>"
                            alt="img"><span>Quản Trị Hệ Thống</span></a>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/product.svg')); ?>"
                            alt="img"><span>Danh Mục</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.categories.index')); ?>"
                                class="<?php echo e(request()->routeIs('admin.categories.index') ? 'active' : ''); ?>">Danh Sách Danh Mục</a></li>
                        <li><a href="<?php echo e(route('admin.categories.create')); ?>"
                                class="<?php echo e(request()->routeIs('admin.categories.create') ? 'active' : ''); ?>">Thêm Danh Mục</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/gamev2.svg')); ?>"
                            alt="img"><span>Game</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.game-hack.index')); ?>"
                                class="<?php echo e(request()->routeIs('admin.game-hack.index') ? 'active' : ''); ?>">Quản Lý Game</a></li>

                        <li><a href="<?php echo e(route('admin.game-hack.add-key')); ?>"
                                class="<?php echo e(request()->routeIs('admin.game-hack.add-key') ? 'active' : ''); ?>">Thêm Key Game</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/product.svg')); ?>"
                            alt="img"><span>Tài Khoản</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.accounts.index')); ?>"
                                class="<?php echo e(request()->routeIs('admin.accounts.index') ? 'active' : ''); ?>">Danh Sách Tài Khoản</a></li>
                        <li><a href="<?php echo e(route('admin.accounts.create')); ?>"
                                class="<?php echo e(request()->routeIs('admin.accounts.create') ? 'active' : ''); ?>">Thêm Tài Khoản</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/product.svg')); ?>"
                            alt="img"><span>Dịch Vụ</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.services.index')); ?>"
                                class="<?php echo e(request()->routeIs('admin.services.index') ? 'active' : ''); ?>">Danh Sách Dịch Vụ</a></li>
                        <li><a href="<?php echo e(route('admin.services.create')); ?>"
                                class="<?php echo e(request()->routeIs('admin.services.create') ? 'active' : ''); ?>">Thêm Dịch Vụ</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/product.svg')); ?>"
                            alt="img"><span>Gói Dịch Vụ</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.packages.index')); ?>"
                                class="<?php echo e(request()->routeIs('admin.packages.index') ? 'active' : ''); ?>">Danh Sách Gói Dịch Vụ</a></li>
                        <li><a href="<?php echo e(route('admin.packages.create')); ?>"
                                class="<?php echo e(request()->routeIs('admin.packages.create') ? 'active' : ''); ?>">Thêm Gói Dịch Vụ</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/users1.svg')); ?>"
                            alt="img"><span>Người Dùng</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.users.index')); ?>"
                                class="<?php echo e(request()->routeIs('admin.users.index') ? 'active' : ''); ?>">Danh Sách Người Dùng</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/dollar-square.svg')); ?>"
                            alt="img"><span>Ngân Hàng</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.bank-accounts.index')); ?>"
                                class="<?php echo e(request()->routeIs('admin.bank-accounts.index') ? 'active' : ''); ?>">Danh Sách Ngân Hàng</a></li>
                        <li><a href="<?php echo e(route('admin.bank-accounts.create')); ?>"
                                class="<?php echo e(request()->routeIs('admin.bank-accounts.create') ? 'active' : ''); ?>">Thêm Ngân Hàng</a></li>
                    </ul>
                </li>


                <!--<li class="submenu">-->
                <!--    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/product.svg')); ?>"-->
                <!--            alt="img"><span>Random</span><span class="menu-arrow"></span></a>-->
                <!--    <ul>-->
                <!--        <li><a href="<?php echo e(route('admin.random-categories.index')); ?>"-->
                <!--                class="<?php echo e(request()->routeIs('admin.random-categories.index') ? 'active' : ''); ?>">Danh-->
                <!--                sách danh mục random</a></li>-->
                <!--        <li><a href="<?php echo e(route('admin.random-categories.create')); ?>"-->
                <!--                class="<?php echo e(request()->routeIs('admin.random-categories.create') ? 'active' : ''); ?>">Thêm-->
                <!--                danh mục random</a></li>-->
                <!--    </ul>-->
                <!--</li>-->

                <!--<li class="submenu">-->
                <!--    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/product.svg')); ?>"-->
                <!--            alt="img"><span>Tài Khoản Random</span><span class="menu-arrow"></span></a>-->
                <!--    <ul>-->
                <!--        <li><a href="<?php echo e(route('admin.random-accounts.index')); ?>"-->
                <!--                class="<?php echo e(request()->routeIs('admin.random-accounts.index') ? 'active' : ''); ?>">Danh-->
                <!--                sách tài khoản random</a></li>-->
                <!--        <li><a href="<?php echo e(route('admin.random-accounts.create')); ?>"-->
                <!--                class="<?php echo e(request()->routeIs('admin.random-accounts.create') ? 'active' : ''); ?>">Thêm-->
                <!--                tài khoản random</a></li>-->
                <!--    </ul>-->
                <!--</li>-->

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/sales1.svg')); ?>"
                            alt="img"><span>Mã Giảm Giá</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.discount-codes.index')); ?>"
                                class="<?php echo e(request()->routeIs('admin.discount-codes.index') ? 'active' : ''); ?>">Danh Sách Mã Giảm Giá</a></li>
                        <li><a href="<?php echo e(route('admin.discount-codes.create')); ?>"
                                class="<?php echo e(request()->routeIs('admin.discount-codes.create') ? 'active' : ''); ?>">Thêm Mã Giảm Giá</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><img src="<?php echo e(asset('assets/img/icons/time.svg')); ?>"
                            alt="img"><span>Lịch Sử</span><span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.history.transactions')); ?>"
                                class="<?php echo e(request()->routeIs('admin.history.transactions') ? 'active' : ''); ?>">Lịch Sử Giao Dịch</a></li>
                        <li><a href="<?php echo e(route('admin.history.accounts')); ?>"
                                class="<?php echo e(request()->routeIs('admin.history.accounts') ? 'active' : ''); ?>">Lịch Sử Mua Tài Khoản</a></li>
                        <!--<li><a href="<?php echo e(route('admin.history.random-accounts')); ?>"-->
                        <!--        class="<?php echo e(request()->routeIs('admin.history.random-accounts') ? 'active' : ''); ?>">Lịch-->
                        <!--        sử mua random</a></li>-->
                        <li><a href="<?php echo e(route('admin.history.services')); ?>"
                                class="<?php echo e(request()->routeIs('admin.history.services') ? 'active' : ''); ?>">Lịch Sử Dịch Vụ</a></li>
                        <li><a href="<?php echo e(route('admin.history.deposits.bank')); ?>"
                                class="<?php echo e(request()->routeIs('admin.history.deposits.bank') ? 'active' : ''); ?>">Lịch Sử Nạp Bank</a></li>
                        <!--<li><a href="<?php echo e(route('admin.withdrawals.index')); ?>"-->
                        <!--        class="<?php echo e(request()->routeIs('admin.withdrawals.index') ? 'active' : ''); ?>">Lịch-->
                        <!--        sử rút tiền</a></li>-->
                        <!--<li><a href="<?php echo e(route('admin.withdrawals.resources.index')); ?>"-->
                        <!--        class="<?php echo e(request()->routeIs('admin.withdrawals.resources.index') ? 'active' : ''); ?>">Lịch-->
                        <!--        sử rút vàng/ngọc</a></li>-->
                        <li><a href="<?php echo e(route('admin.history.deposits.card')); ?>"
                                class="<?php echo e(request()->routeIs('admin.history.deposits.card') ? 'active' : ''); ?>">Lịch Sử Nạp Card</a></li>
                        <li><a href="<?php echo e(route('admin.history.discount-usages')); ?>"
                                class="<?php echo e(request()->routeIs('admin.history.discount-usages') ? 'active' : ''); ?>">Lịch Sử Mã Giảm Giá</a></li>
                    </ul>
                </li>

                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="settings"></i><span>Hệ Thống</span><span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="<?php echo e(route('admin.settings.general')); ?>"
                                class="<?php echo e(request()->routeIs('admin.settings.general') ? 'active' : ''); ?>">CÀI ĐẶT CHUNG</a></li>
                        <li><a href="<?php echo e(route('admin.settings.social')); ?>"
                                class="<?php echo e(request()->routeIs('admin.settings.social') ? 'active' : ''); ?>">MẠNG XÃ HỘI $ THÔNG BÁO</a></li>
                        <li><a href="<?php echo e(route('admin.settings.email')); ?>"
                                class="<?php echo e(request()->routeIs('admin.settings.email') ? 'active' : ''); ?>">CÀI ĐẶT MAIL</a></li>
                        <li><a href="<?php echo e(route('admin.settings.payment')); ?>"
                                class="<?php echo e(request()->routeIs('admin.settings.payment') ? 'active' : ''); ?>">CÀI ĐẶT NẠP THẺ</a></li>
                        <li><a href="<?php echo e(route('admin.settings.login')); ?>"
                                class="<?php echo e(request()->routeIs('admin.settings.login') ? 'active' : ''); ?>">CÀI ĐẶT ĐĂNG NHẬP</a></li>
                        <li><a href="<?php echo e(route('admin.settings.notifications')); ?>"
                                class="<?php echo e(request()->routeIs('admin.settings.notifications') ? 'active' : ''); ?>">QUẢN LÝ THÔNG BÁO</a></li>
                    </ul>
                </li>

                <hr>
                <li>

                </li>

            </ul>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\LEGION\Documents\Sources\shop\resources\views/layouts/admin/sidebar.blade.php ENDPATH**/ ?>