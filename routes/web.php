<?php
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\User\CardDepositController;
use App\Http\Controllers\User\GameAccountController;
use App\Http\Controllers\User\GameCategoryController;
use App\Http\Controllers\User\GameServiceController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\LuckyCategoryController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ServiceOrderController;
use App\Http\Controllers\User\RandomCategoryController;
use App\Http\Controllers\User\RandomAccountController;
use App\Http\Controllers\User\WithdrawalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameKeyController;
use App\Http\Controllers\AddKeyVipController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\BuyIosController;
use App\Http\Controllers\DrawController;
use App\Http\Controllers\UgPhoneController;
use App\Http\Controllers\HackGameController;
use App\Http\Controllers\User\FakeIdController;
use App\Http\Controllers\User\FishIdController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Admin\GameHackController;
use App\Http\Controllers\NapGoiController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\Admin\AddCardController;
use App\Http\Controllers\Admin\ServicePackageController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FreeKeyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/api.php';
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tools', [HomeController::class, 'tools'])->name('tools');
Route::delete('/packages/{package}', [ServicePackageController::class, 'destroy'])
    ->name('admin.packages.destroy');

Route::get('/DOWNLOAD/{id}', [HackGameController::class, 'download'])->name('download.hack');
Route::get('/GETKEY/{id}', [HackGameController::class, 'getKey'])->name('get.key');
Route::get('/gift-code', function () {
    return view('user.gift-code');
})->name('gift-code');

Route::get('/fake', [FakeIdController::class, 'index'])->name('fake');
Route::get('/fish', [FishIdController::class, 'index'])->name('user.fish');
Route::post('/user/get-role-name', [\App\Http\Controllers\User\ServiceOrderController::class, 'getRoleName'])
    ->name('service.getRoleName');

Route::prefix('admin/game-hack')->name('admin.game-hack.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [GameHackController::class, 'index'])->name('index');
    Route::get('/create', [GameHackController::class, 'create'])->name('create');
    Route::post('/store', [GameHackController::class, 'store'])->name('store');
    Route::get('/{gameHack}/edit', [GameHackController::class, 'edit'])->name('edit');
    Route::put('/{gameHack}/update', [GameHackController::class, 'update'])->name('update');
    Route::delete('/{gameHack}/destroy', [GameHackController::class, 'destroy'])->name('destroy');

    // === Thêm key ===
    Route::get('/add-key', [GameHackController::class, 'addKey'])->name('add-key');
    Route::post('/store-key', [GameHackController::class, 'storeKey'])->name('store-key');
    Route::post('/toggle-key-mode', [GameHackController::class, 'toggleKeyMode'])->name('toggle-key-mode');
    Route::post('/update-api-configs', [GameHackController::class, 'updateApiConfigs'])->name('update-api-configs');
});

Route::get('/discount/codes', [DiscountController::class, 'getDiscountCodes'])->name('discount.codes');
Route::post('/discount/claim', [DiscountController::class, 'claimReward'])->name('discount.claim');

Route::get('/shop', [NapGoiController::class, 'showForm'])->name('nap-goi.form');
Route::post('/shop', [NapGoiController::class, 'login'])->name('nap-goi.login');
Route::post('/nap-goi/change', [NapGoiController::class, 'changeID'])->name('nap-goi.change');

Route::middleware(['auth', 'check.user.status'])->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name(name: 'index');
        Route::get('/change-password', [ProfileController::class, 'viewChangePassword'])->name('change-password');
        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password.update');
        Route::get('/services-history', [ProfileController::class, 'servicesHistory'])->name('services-history');
        Route::get('/transaction-history', [ProfileController::class, 'transactionHistory'])->name('transaction-history');
        Route::get('/purchased-accounts', [ProfileController::class, 'purchasedAccounts'])->name('purchased-accounts');

        // Đổi lại thành random
        Route::get('/purchased-random-accounts', [ProfileController::class, 'purchasedRandomAccounts'])->name('purchased-random-accounts');





        Route::get('/withdraw-gold', [ProfileController::class, 'withdrawGold'])->name('withdraw-gold');
        Route::post('/withdraw-gold', [ProfileController::class, 'processWithdrawGold']);
        Route::get('/withdraw-gem', [ProfileController::class, 'withdrawGem'])->name('withdraw-gem');
        Route::post('/withdraw-gem', [ProfileController::class, 'processWithdrawGem']);
        Route::get('/withdrawal-history/{id}', [ProfileController::class, 'getWithdrawalDetail'])
            ->name('withdrawal.detail');

        Route::get('/service-history/{id}', [ProfileController::class, 'getServiceDetail'])
            ->name('service.detail');
        Route::get('/wheels-history', [ProfileController::class, 'luckyWheelHistory'])->name('wheels-history');
        Route::get('/wheel-history/{id}', [ProfileController::class, 'getLuckyWheelDetail'])
            ->name('wheel.detail');

        Route::prefix('withdraw')->name('withdraw.')->group(function () {
            Route::get('/', [WithdrawalController::class, 'create'])->name('create');
            Route::post('/', [WithdrawalController::class, 'store'])->name('store');
            Route::get('/history', [WithdrawalController::class, 'history'])->name('history');
        });

    });
    // Routes for lucky wheel categories
    Route::prefix('lucky')->name('lucky.')->group(function () {
        Route::get('/', [LuckyCategoryController::class, 'showAll'])->name('show-all');
        Route::get('/wheel/{slug}', function ($slug) {
            if (filter_var(env('WHEEL_MAINTENANCE'), FILTER_VALIDATE_BOOLEAN)) {
                return view('user.404'); // hoặc view('user.baotri')
            }
            return app(LuckyCategoryController::class)->index($slug);
        })->name('index');
        Route::post('/wheel/{slug}/spin', [LuckyCategoryController::class, 'spin'])->name('spin');

    });

    Route::get('/pay/card', [ProfileController::class, 'depositCard'])->name('profile.deposit-card');
    Route::get('/pay/bank', [ProfileController::class, 'depositAtm'])->name('profile.deposit-atm');
    Route::post('/pay/card', [CardDepositController::class, 'processCardDeposit']);
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    // Trang lịch sử dịch vụ
    Route::get('/history/services', [ServiceController::class, 'index'])->name('history.services');

    // Cập nhật trạng thái dịch vụ
    Route::put('/services/update-status/{id}', [ServiceController::class, 'updateStatus'])->name('services.updateStatus');

});

Route::prefix('show')->name('category.')->group(function () {
    Route::get('/', [GameCategoryController::class, 'showAll'])->name('show-all');
    Route::get('/{slug}', [GameCategoryController::class, 'index'])->name('index');
});

Route::get('/user/balance', function () {
    return response()->json([
        'balance' => Auth::user()->balance
    ]);
});

Route::prefix('acc')->name('account.')->group(function () {
    Route::get('/{id}', [GameAccountController::class, 'show'])->name(name: 'show');
    Route::post('/{id}/purchase', [GameAccountController::class, 'purchase'])->name('purchase');
    Route::post('/{id}/view', [GameCategoryController::class, 'increaseView']);
});

// Random Purchase - Mua ngẫu nhiên tài khoản từ category
Route::post('/category/{categoryId}/random-purchase', [GameAccountController::class, 'randomPurchase'])
    ->middleware(['auth', 'check.user.status'])
    ->name('category.random-purchase');

Route::prefix('service')->name('service.')->group(function () {
    Route::get('/', [GameServiceController::class, 'showAll'])->name('show-all');
    Route::get('/{slug}', [GameServiceController::class, 'show'])->name('show');
    Route::post('/{slug}/order', [ServiceOrderController::class, 'processOrder'])->name('order');
});

// Routes for random categories
Route::prefix('random')->name('random.')->group(function () {
    Route::get('/', [RandomCategoryController::class, 'showAll'])->name('show-all');
    Route::get('/account/{id}', [RandomAccountController::class, 'show'])->name('account.show');
    Route::post('/account/{id}/purchase', [RandomAccountController::class, 'purchase'])->name('account.purchase');
    Route::get('/{slug}', [RandomCategoryController::class, 'index'])->name('index');
});

// Các route KHÔNG cần đăng nhập
Route::post('/check-discount', [\App\Http\Controllers\GameKeyController::class, 'checkDiscount'])->name('discount.check');

Route::get('/muakey', [GameKeyController::class, 'showForm'])->name('gamekey.form');
Route::post('/ajax/get-device-info', [GameKeyController::class, 'ajaxGetDeviceInfo'])->name('user.ajax.get-device-info');
Route::get('/nhankey', [GameKeyController::class, 'getKey'])
    ->name('gamekey.getkey');
Route::get('/buy-ios', [BuyIosController::class, 'showForm'])->name('buy-ios');
Route::post('/buy-ios', [BuyIosController::class, 'purchase'])->name('buy-ios.purchase');

// 🔒 Routes CẦN đăng nhập + Rate limiting cho mua key
Route::middleware(['auth', 'check.user.status'])->group(function () {
    Route::post('/buy-key', [GameKeyController::class, 'createKey'])->middleware('throttle:10,1')->name('gamekey.create');
    Route::post('/user/reset-key', [GameKeyController::class, 'resetKey'])->name('user.reset-key');
    Route::post('/ajax/get-key-details', [GameKeyController::class, 'ajaxGetKeyDetails'])->name('user.ajax.get-key-details');
    Route::post('/ajax/reset-devices', [GameKeyController::class, 'ajaxResetDevicesWithPayment'])->name('user.ajax.reset-devices');
    Route::post('/ajax/delete-device', [GameKeyController::class, 'ajaxDeleteDevice'])->name('user.ajax.delete-device');
});

// Các route CẦN đăng nhập (được bảo vệ bởi middleware 'auth')
Route::middleware(['auth', 'check.user.status'])->group(function () {
    Route::get('/draw', function () {
        if (env('DRAW_MAINTENANCE')) {
            return view('user.404');
        }
        return app(DrawController::class)->index();
    })->name('draw.index');
    Route::post('/draw/spin', [DrawController::class, 'spin'])->name('draw.spin');
    Route::get('/draw/result', [DrawController::class, 'result'])->name('draw.result');
    Route::get('admin/add-key', [AddKeyVipController::class, 'showForm'])->name('add-key.show');
    Route::post('admin/add-key', [AddKeyVipController::class, 'store'])->name('add-key.store');
    Route::get('/api/check-history', function () {
        // Chỉ cần gửi request tới API mà không trả về gì
        $url = 'https://oneone.io.vn/api/auto-bank-deposit';
        Http::get($url);
        return response()->json(['status' => 'Request sent']);
    });

    Route::middleware(['auth', 'check.user.status'])->group(function () {
        Route::get('/ug-phone', [UgPhoneController::class, 'index'])->name('ug-phone.index');
        Route::post('/ug-phone/purchase', [UgPhoneController::class, 'purchase'])->name('ug-phone.purchase');
    });
});

Route::get('/lucky/history/{slug}', [LuckyCategoryController::class, 'getHistoryHtml'])->name('lucky.history');
// Discount code routes
Route::post('/discount-code/validate', [DiscountCodeController::class, 'validateCode'])->name('discount.validate');

Route::get('/lsgd', function () {
    $path = app_path('Console/Commands/lsgd.json');

    if (!File::exists($path)) {
        abort(404, 'File lsgd.json không tồn tại');
    }

    return Response::file($path, [
        'Content-Type' => 'application/json',
    ]);
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/add-card', [AddCardController::class, 'index'])->name('cards.index');
    Route::post('/add-card', [AddCardController::class, 'store'])->name('cards.store');

    // AJAX check số thẻ
    Route::get('/add-card/check', [AddCardController::class, 'checkCount'])->name('cards.check');
});

Route::get('/downloads/{hack}', [DownloadController::class, 'latestByHack'])
    ->name('download.hack.json');

Route::get('/check', [\App\Http\Controllers\DownloadController::class, 'activeDownloads']);

Route::get('/hacks', [HackGameController::class, 'index'])->name('hacks.index');
Route::get('/hacks/{hack}', [HackGameController::class, 'show'])->name('hacks.show');
Route::get('/hacks/{hack}/download', [HackGameController::class, 'download'])->name('download.hack');
Route::get('/hacks/{hack}/download-global', [HackGameController::class, 'downloadGlobal'])->name('download.hack.global');
Route::get('/hacks/{hack}/get-key', [HackGameController::class, 'getKey'])->name('get.key');
Route::get('/hacks/{hack}/free-key', [HackGameController::class, 'freeKey'])->name('hacks.free-key');

// Free Key page - Hiển thị key miễn phí cho user
Route::get('/keyfree', [FreeKeyController::class, 'show'])->name('keyfree.show');
Route::get('/keyfree/session/{token}', [HackGameController::class, 'showActivatePage'])->name('keyfree.confirm');
Route::post('/keyfree/session/{token}', [HackGameController::class, 'activateFreeKey'])->name('keyfree.activate');

// ========== Direct Payment Routes (Thanh toán trực tiếp) ==========
use App\Http\Controllers\DirectPaymentController;

Route::prefix('pay/order')->name('direct-payment.')->group(function () {
    // Tạo đơn hàng - cho cả guest và user - Giới hạn 3 đơn/phút
    Route::post('/account/{accountId}', [DirectPaymentController::class, 'createAccountOrder'])->name('create-account');
    Route::post('/random/{categoryId}', [DirectPaymentController::class, 'createRandomOrder'])->name('create-random');

    // Xem trang thanh toán QR
    Route::get('/{orderCode}', [DirectPaymentController::class, 'show'])->name('show');

    // API check status (polling)
    Route::get('/{orderCode}/check', [DirectPaymentController::class, 'checkStatus'])->name('check');

    // Xem kết quả
    Route::get('/{orderCode}/success', [DirectPaymentController::class, 'result'])->name('result');

    // Tải file TXT
    Route::get('/{orderCode}/download', [DirectPaymentController::class, 'downloadTxt'])->name('download');
});
