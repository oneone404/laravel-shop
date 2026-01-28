<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\GameAccount;
use App\Models\GameCategory;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class GameAccountController extends Controller
{
    private const UPLOAD_DIR = 'accounts';

    // 🟩 INDEX - Danh sách tài khoản (Play + Clone)
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        // ✅ Lấy danh mục seller tạo hoặc danh mục chung (không phải random)
        $categories = GameCategory::where(function ($q) use ($sellerId) {
            $q->where('created_by', $sellerId)
            ->orWhere('is_global', true);
        })
        ->where('active', 1)
        ->whereIn('type', ['play', 'clone'])
        ->orderBy('id', 'asc')
        ->get();

        // ✅ Lấy danh sách tài khoản thường (không phải random) của seller
        $query = GameAccount::with('category')
            ->where('created_by', $sellerId)
            ->whereHas('category', function ($q) {
                $q->whereIn('type', ['play', 'clone']);
            })
            ->orderByDesc('id');

        if ($request->filled('categories')) {
            $query->whereIn('game_category_id', $request->categories);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $accounts = $query->paginate(10)->withQueryString();

        return view('seller.accounts.index', compact('accounts', 'categories'));
    }

    // 🟩 INDEX PLAY - Chỉ hiển thị tài khoản Play
    public function indexPlay(Request $request)
    {
        $sellerId = auth()->id();

        $categories = GameCategory::where(function ($q) use ($sellerId) {
            $q->where('created_by', $sellerId)->orWhere('is_global', true);
        })
        ->where('active', 1)
        ->where('type', 'play')
        ->orderBy('id', 'asc')
        ->get();

        $query = GameAccount::with('category')
            ->where('created_by', $sellerId)
            ->whereHas('category', fn($q) => $q->where('type', 'play'))
            ->orderByDesc('id');

        if ($request->filled('categories')) {
            $query->whereIn('game_category_id', $request->categories);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $accounts = $query->paginate(10)->withQueryString();
        $accountType = 'play';

        return view('seller.accounts.index', compact('accounts', 'categories', 'accountType'));
    }

    // 🟩 INDEX CLONE - Chỉ hiển thị tài khoản Clone
    public function indexClone(Request $request)
    {
        $sellerId = auth()->id();

        $categories = GameCategory::where(function ($q) use ($sellerId) {
            $q->where('created_by', $sellerId)->orWhere('is_global', true);
        })
        ->where('active', 1)
        ->where('type', 'clone')
        ->orderBy('id', 'asc')
        ->get();

        $query = GameAccount::with('category')
            ->where('created_by', $sellerId)
            ->whereHas('category', fn($q) => $q->where('type', 'clone'))
            ->orderByDesc('id');

        if ($request->filled('categories')) {
            $query->whereIn('game_category_id', $request->categories);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $accounts = $query->paginate(10)->withQueryString();
        $accountType = 'clone';

        return view('seller.accounts.index', compact('accounts', 'categories', 'accountType'));
    }

    // 🟨 CREATE - Trang thêm tài khoản (Play + Clone)
    public function create()
    {
        $title = 'Thêm tài khoản game mới';

        // ✅ Seller chỉ thấy danh mục Play/Clone
        $categories = GameCategory::where('active', true)
            ->where(function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhere('is_global', true);
            })
            ->whereIn('type', ['play', 'clone'])
            ->orderBy('id', 'asc')
            ->get();

        return view('seller.accounts.create', compact('title', 'categories'));
    }

    // 🟨 CREATE PLAY - Chỉ thêm tài khoản Play
    public function createPlay()
    {
        $title = 'Thêm tài khoản Play';

        $categories = GameCategory::where('active', true)
            ->where(function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhere('is_global', true);
            })
            ->where('type', 'play')
            ->orderBy('id', 'asc')
            ->get();

        $accountType = 'play';

        return view('seller.accounts.create', compact('title', 'categories', 'accountType'));
    }

    // 🟨 CREATE CLONE - Chỉ thêm tài khoản Clone
    public function createClone()
    {
        $title = 'Thêm tài khoản Clone';

        $categories = GameCategory::where('active', true)
            ->where(function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhere('is_global', true);
            })
            ->where('type', 'clone')
            ->orderBy('id', 'asc')
            ->get();

        $accountType = 'clone';

        return view('seller.accounts.create', compact('title', 'categories', 'accountType'));
    }

    // 🟦 STORE - Lưu tài khoản mới
    public function store(Request $request)
    {
        try {
            // Nếu có account_list thì bỏ acc/pass cá nhân
            if ($request->filled('account_list')) {
                $request->request->remove('account_name');
                $request->request->remove('password');
            }

            // Kiểm tra category type để xác định validation rules
            $category = GameCategory::find($request->game_category_id);
            $isRandomCategory = $category && $category->type === 'random';

            // Base validation rules
            $rules = [
                'game_category_id' => 'required|exists:game_categories,id',
                'price' => 'required|numeric|min:0',
                'server' => 'required|integer',
                'registration_type' => 'required|in:virtual,real',
                'planet' => 'required|in:earth,namek,xayda',
                'earring' => 'nullable|boolean',
                'note' => 'nullable|string',
                'status' => 'required|in:available,sold',
                'account_list' => 'nullable|string',
                'account_name' => 'required_without:account_list|string|max:255',
                'password' => 'required_without:account_list|string|max:255',
            ];

            // Random category không cần ảnh
            if ($isRandomCategory) {
                $rules['thumb'] = 'nullable|image|mimes:jpeg,jpg,png,gif|max:10240';
                $rules['images'] = 'nullable|array';
                $rules['images.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480';
            } else {
                $rules['thumb'] = 'required|image|mimes:jpeg,jpg,png,gif|max:10240';
                $rules['images'] = 'required|array|min:1';
                $rules['images.*'] = 'required|image|mimes:jpeg,png,jpg,gif|max:20480';
            }

            $request->validate($rules);

            DB::beginTransaction();

            // 🔒 Kiểm tra danh mục hợp lệ:
            if (!$category) {
                throw new \Exception('Danh mục không tồn tại.');
            }

            // ✅ Cho phép thêm nếu là danh mục của seller hoặc danh mục chung
            if ($category->created_by !== auth()->id() && !$category->is_global) {
                throw new \Exception('Danh mục này không thuộc về bạn và không phải danh mục chung.');
            }

            // ✅ Upload ảnh (hoặc dùng ảnh category cho random)
            $thumbPath = null;
            if ($request->hasFile('thumb')) {
                $thumbPath = UploadHelper::upload($request->file('thumb'), self::UPLOAD_DIR . '/thumbnails');
            } elseif ($isRandomCategory && $category->thumbnail) {
                // Random category dùng ảnh của category
                $thumbPath = $category->thumbnail;
            }

            // ✅ Upload nhiều ảnh nếu có
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imagePaths[] = UploadHelper::upload($img, self::UPLOAD_DIR . '/images');
                }
            }

            // ✅ Dữ liệu cơ bản
            $commonData = [
                'game_category_id'  => (int) $category->id,
                'price'             => $isRandomCategory ? (float)$category->price : (float)$request->input('price'),
                'status'            => (string) $request->input('status'),
                'server'            => (int) $request->input('server'),
                'registration_type' => (string) $request->input('registration_type'),
                'planet'            => (string) $request->input('planet'),
                'earring'           => (bool) $request->input('earring', false),
                'thumb'             => $thumbPath,
                'images'            => !empty($imagePaths) ? $imagePaths : null,
                'note'              => $isRandomCategory ? $category->description : $request->input('note'), // Random dùng mô tả category
                'created_by'        => auth()->id(),
            ];

            // ✅ Tạo nhiều tài khoản
            if ($request->filled('account_list')) {
                $lines = explode("\n", trim($request->account_list));
                $insertCount = 0;

                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line) || !str_contains($line, '|')) continue;

                    [$acc, $pass] = array_map('trim', explode('|', $line));
                    if (!$acc || !$pass) continue;

                    GameAccount::create(array_merge($commonData, [
                        'account_name' => $acc,
                        'password'     => $pass,
                    ]));
                    $insertCount++;
                }

                if ($insertCount === 0) {
                    throw new \Exception('Không có tài khoản hợp lệ trong danh sách.');
                }
            } else {
                // ✅ Thêm 1 tài khoản
                GameAccount::create(array_merge($commonData, [
                    'account_name' => (string) $request->input('account_name'),
                    'password'     => (string) $request->input('password'),
                ]));
            }

            DB::commit();

            return redirect()->route('seller.accounts.index')
                ->with('success', 'Thêm Tài Khoản Thành Công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error creating game account: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(GameAccount $account)
    {
        if ($account->status === 'sold' && auth()->user()->role === 'seller') {
            return redirect()->route('seller.accounts.index')->with('error', 'Không Thể Sửa Tài Khoản Đã Bán');
        }
        // ✅ Kiểm tra quyền truy cập
        if ($account->created_by !== auth()->id()) {
            return redirect()->route('seller.accounts.index')
                ->with('error', 'Không Thể Sửa Tài Khoản Seller Khác');
        }

        $title = 'Chỉnh sửa tài khoản game';

        // ✅ Seller thấy được category của họ hoặc category dùng chung
        $categories = GameCategory::where('active', true)
            ->where(function ($q) {
                $q->where('created_by', auth()->id())
                ->orWhere('is_global', true);
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('seller.accounts.edit', compact('title', 'account', 'categories'));
    }

    // 🟧 UPDATE - Sửa tài khoản
    public function update(Request $request, GameAccount $account)
{
    // 🔒 Chặn sửa tài khoản của seller khác
    if ($account->created_by !== auth()->id()) {
        return redirect()->route('seller.accounts.index')
            ->with('error', 'Không Thể Sửa Tài Khoản Của Seller Khác');
    }

    // 🔒 Seller không được sửa tài khoản đã bán
    if ($account->status === 'sold' && auth()->user()->role === 'seller') {
        return redirect()->route('seller.accounts.index')
            ->with('error', 'Không Thể Sửa Tài Khoản Đã Bán');
    }

    try {
        $request->validate([
            'game_category_id'   => 'required|exists:game_categories,id',
            'account_name'       => 'required|string|max:255',
            'password'           => 'required|string|max:255',
            'price'              => 'required|numeric|min:0',
            'server'             => 'required|integer',
            'registration_type'  => 'required|in:virtual,real',
            'planet'             => 'required|in:earth,namek,xayda',
            'earring'            => 'boolean',
            'note'               => 'nullable|string',
            'thumb' => 'nullable|mimes:jpeg,jpg,png,gif|max:10240',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20480',
            'keep_images'        => 'nullable|array', // 👈 danh sách ảnh cũ giữ lại
        ]);

        DB::beginTransaction();

        // ✅ Dữ liệu cập nhật cơ bản
        $data = $request->except(['thumb', 'images', 'keep_images']);

        // 🔒 Nếu acc đã bán => không cho sửa giá & status
        if ($account->status === 'sold') {
            unset($data['price'], $data['status']);
        }

        // ✅ Kiểm tra danh mục hợp lệ
        $category = GameCategory::find($request->game_category_id);
        if (!$category) {
            throw new \Exception('Danh Mục Không Tồn Tại');
        }

        if ($category->created_by !== auth()->id() && !$category->is_global) {
            throw new \Exception('Không Thể Dùng Danh Mục Này');
        }

        // ✅ Upload thumbnail mới (nếu có)
        if ($request->hasFile('thumb')) {
            if ($account->thumb) {
                UploadHelper::deleteByUrl($account->thumb);
            }
            $data['thumb'] = UploadHelper::upload(
                $request->file('thumb'),
                self::UPLOAD_DIR . '/thumbnails'
            );
        }

        // ✅ Xử lý danh sách ảnh chi tiết
        $keepImages = $request->input('keep_images', []); // Ảnh cũ giữ lại
        $newImages = [];

        // Upload ảnh mới (nếu có)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $newImages[] = UploadHelper::upload($img, self::UPLOAD_DIR . '/images');
            }
        }

        // Gộp ảnh giữ lại + ảnh mới
        $finalImages = array_merge($keepImages, $newImages);

        // Nếu user xoá hết ảnh cũ & không upload ảnh mới → null
        $data['images'] = !empty($finalImages) ? array_values($finalImages) : null;

        // ✅ Cập nhật database
        $account->update($data);

        DB::commit();

        return redirect()->route('seller.accounts.index')
            ->with('success', 'Sửa Tài Khoản Thành Công');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ Error updating game account: ' . $e->getMessage());
        return back()->withInput()->with('error', $e->getMessage());
    }
}

    public function exportSelected(Request $request)
    {
        $ids = json_decode($request->input('ids'), true);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Không Có Tài Khoản Được Chọn');
        }

        $idsString = implode(',', $ids);

        $accounts = \App\Models\GameAccount::with('category')
            ->whereIn('id', $ids)
            ->orderByRaw("FIELD(id, $idsString)") // ❌ bỏ DB::raw()
            ->get(['id', 'account_name', 'password', 'game_category_id']);

        if ($accounts->isEmpty()) {
            return redirect()->back()->with('error', 'Không Có Tài Khoản Hợp Lệ');
        }

        $firstCategory = $accounts->first()->category->name ?? 'Accounts';
        $safeName = preg_replace('/[^A-Za-z0-9_-]/', '', str_replace(' ', '_', $firstCategory));
        $timestamp = Carbon::now()->format('Y-m-d_H-i');
        $filename = "{$safeName}_{$timestamp}.csv";

        $response = new StreamedResponse(function() use ($accounts) {
            $handle = fopen('php://output', 'w');

            foreach ($accounts as $acc) {
                fputcsv($handle, [$acc->account_name . '|' . $acc->password]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

        public function destroy(GameAccount $account)
        {
            if ($account->created_by !== auth()->id()) {
                return redirect()->back()->with('error', 'Không Thể Xoá Tài Khoản Seller Khác');
            }
            try {
                DB::beginTransaction();

                // Delete thumbnail if exists
                if ($account->thumb) {
                    UploadHelper::deleteByUrl($account->thumb);
                }

                // Delete additional images if exists
                if ($account->images) {
                    $images = json_decode($account->images, true);
                    foreach ($images as $image) {
                        UploadHelper::deleteByUrl($image);
                    }
                }

                // Delete the account record
                $account->delete();

                DB::commit();

                return redirect()->route('seller.accounts.index')
                ->with('success', 'Xoá Tài Khoản Thành Công');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error deleting game account: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xóa tài khoản game: ' . $e->getMessage()
                ]);
            }
        }
        public function destroyMultiple(Request $request)
    {
        $ids = json_decode($request->input('ids', '[]'), true);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Không Có Tài Khoản Được Chọn');
        }

        $accounts = GameAccount::whereIn('id', $ids)
            ->where('created_by', auth()->id())
            ->get();

        if ($accounts->isEmpty()) {
            return redirect()->back()->with('error', 'Không Thể Thực Hiện');
        }

        try {
            DB::beginTransaction();

            foreach ($accounts as $acc) {
                if ($acc->thumb) UploadHelper::deleteByUrl($acc->thumb);
                if ($acc->images) {
                    foreach (json_decode($acc->images, true) as $img) {
                        UploadHelper::deleteByUrl($img);
                    }
                }
                $acc->delete();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Xoá Thành Công ' . count($accounts) . ' Tài Khoản');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi khi xoá: ' . $e->getMessage());
        }
    }

    // 🟪 EDIT RANDOM - Trang sửa tài khoản random theo category
    public function editRandom($categoryId)
    {
        $sellerId = auth()->id();

        // Lấy category
        $category = GameCategory::where('id', $categoryId)
            ->where('type', 'random')
            ->where(function ($q) use ($sellerId) {
                $q->where('created_by', $sellerId)
                  ->orWhere('is_global', true);
            })
            ->firstOrFail();

        // Lấy tất cả accounts trong category này của seller
        $accounts = GameAccount::where('game_category_id', $categoryId)
            ->where('created_by', $sellerId)
            ->where('status', 'available')
            ->get();

        // Lấy thông tin chung (giá lấy từ category, note lấy từ category description)
        $price = $category->price;
        $note = $category->description;

        // Tạo account_list từ các accounts
        $accountList = $accounts->map(function ($acc) {
            return $acc->account_name . '|' . $acc->password;
        })->implode("\n");

        // Lấy danh sách categories cho dropdown
        $categories = GameCategory::where(function ($q) use ($sellerId) {
            $q->where('created_by', $sellerId)
              ->orWhere('is_global', true);
        })
        ->where('active', 1)
        ->orderBy('id', 'asc')
        ->get();

        return view('seller.accounts.edit-random', compact(
            'category',
            'accounts',
            'price',
            'note',
            'accountList',
            'categories'
        ));
    }

    // 🟪 UPDATE RANDOM - Cập nhật tài khoản random
    public function updateRandom(Request $request, $categoryId)
    {
        try {
            $sellerId = auth()->id();

            $request->validate([
                'account_list' => 'required|string',
            ]);

            $category = GameCategory::where('id', $categoryId)
                ->where('type', 'random')
                ->firstOrFail();

            DB::beginTransaction();

            // Xoá tất cả accounts cũ trong category
            $oldAccounts = GameAccount::where('game_category_id', $categoryId)
                ->where('created_by', $sellerId)
                ->where('status', 'available')
                ->get();

            foreach ($oldAccounts as $acc) {
                if ($acc->thumb && $acc->thumb !== $category->thumbnail) {
                    UploadHelper::deleteByUrl($acc->thumb);
                }
                if ($acc->images) {
                    foreach (json_decode($acc->images, true) as $img) {
                        UploadHelper::deleteByUrl($img);
                    }
                }
                $acc->delete();
            }

            // Thêm accounts mới
            $lines = explode("\n", trim($request->account_list));
            $insertCount = 0;

            // Note lấy từ mô tả category
            $commonData = [
                'game_category_id'  => (int) $categoryId,
                'price'             => (float) $category->price,
                'status'            => 'available',
                'server'            => 13,
                'registration_type' => 'virtual',
                'planet'            => 'earth',
                'earring'           => true,
                'thumb'             => $category->thumbnail,
                'images'            => null,
                'note'              => $category->description, // Badge lấy từ mô tả danh mục
                'created_by'        => $sellerId,
            ];

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || !str_contains($line, '|')) continue;

                [$acc, $pass] = array_map('trim', explode('|', $line));
                if (!$acc || !$pass) continue;

                GameAccount::create(array_merge($commonData, [
                    'account_name' => $acc,
                    'password'     => $pass,
                ]));
                $insertCount++;
            }

            if ($insertCount === 0) {
                throw new \Exception('Không có tài khoản hợp lệ trong danh sách.');
            }

            DB::commit();

            return redirect()->route('seller.accounts.index')
                ->with('success', 'Cập Nhật Random Thành Công: ' . $insertCount . ' tài khoản');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error updating random accounts: ' . $e->getMessage());
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    // 🟥 DELETE ALL BY CATEGORY - Xoá tất cả accounts trong category (không xoá category)
    public function deleteAllByCategory($categoryId)
    {
        try {
            $sellerId = auth()->id();

            DB::beginTransaction();

            $accounts = GameAccount::where('game_category_id', $categoryId)
                ->where('created_by', $sellerId)
                ->where('status', 'available')
                ->get();

            if ($accounts->isEmpty()) {
                return redirect()->back()->with('error', 'Không có tài khoản nào để xoá');
            }

            $count = $accounts->count();

            foreach ($accounts as $acc) {
                // Chỉ xoá ảnh nếu không phải ảnh của category
                $category = GameCategory::find($categoryId);
                if ($acc->thumb && $acc->thumb !== ($category->thumbnail ?? '')) {
                    UploadHelper::deleteByUrl($acc->thumb);
                }
                if ($acc->images) {
                    foreach (json_decode($acc->images, true) as $img) {
                        UploadHelper::deleteByUrl($img);
                    }
                }
                $acc->delete();
            }

            DB::commit();

            return redirect()->back()->with('success', 'Đã xoá tất cả ' . $count . ' tài khoản trong danh mục');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error deleting all accounts by category: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi khi xoá: ' . $e->getMessage());
        }
    }

}
