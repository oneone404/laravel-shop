<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\GameAccount;
use App\Models\GameCategory;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RandomAccountController extends Controller
{
    private const UPLOAD_DIR = 'accounts';

    /**
     * Danh sách nhóm random accounts
     */
    public function index(Request $request)
    {
        $sellerId = auth()->id();

        // Lấy danh mục random của seller
        $categories = GameCategory::where(function ($q) use ($sellerId) {
            $q->where('created_by', $sellerId)
              ->orWhere('is_global', true);
        })
        ->where('active', true)
        ->where('type', 'random')
        ->orderBy('id', 'asc')
        ->get();

        // Lấy các nhóm random (Seller thấy nhóm của mình HOẶC nhóm nằm trong danh mục Public)
        $query = GameAccount::with('category')
            ->where(function ($q) use ($sellerId) {
                $q->where('created_by', $sellerId)
                  ->orWhereHas('category', function ($sub) {
                      $sub->where('is_global', true);
                  });
            })
            ->whereHas('category', function ($q) {
                $q->where('type', 'random');
            })
            ->orderByDesc('id');

        // Filter theo category
        if ($request->filled('category_id')) {
            $query->where('game_category_id', $request->category_id);
        }

        // Filter theo status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $randomGroups = $query->paginate(10)->withQueryString();

        return view('seller.accounts.random.index', compact('randomGroups', 'categories'));
    }

    /**
     * Form tạo nhóm random mới
     */
    public function create()
    {
        $sellerId = auth()->id();

        // Chỉ lấy danh mục random
        $categories = GameCategory::where(function ($q) use ($sellerId) {
            $q->where('created_by', $sellerId)
              ->orWhere('is_global', true);
        })
        ->where('active', true)
        ->where('type', 'random')
        ->orderBy('id', 'asc')
        ->get();

        return view('seller.accounts.random.create', compact('categories'));
    }

    /**
     * Lưu nhóm random mới
     * 1 row = 1 nhóm với nhiều accounts trong accounts_data
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'game_category_id' => 'required|exists:game_categories,id',
                'price'            => 'required|numeric|min:0',
                'note'             => 'nullable|string',
                'account_list'     => 'required|string',
                'thumb'            => 'required|image|mimes:jpeg,jpg,png,gif|max:10240',
            ]);

            DB::beginTransaction();

            $sellerId = auth()->id();
            $category = GameCategory::find($request->game_category_id);

            // Kiểm tra quyền
            if (!$category || $category->type !== 'random') {
                throw new \Exception('Danh mục không hợp lệ hoặc không phải loại random.');
            }

            if ($category->created_by !== $sellerId && !$category->is_global) {
                throw new \Exception('Bạn không có quyền thêm vào danh mục này.');
            }

            // Upload ảnh đại diện
            $thumbPath = UploadHelper::upload($request->file('thumb'), self::UPLOAD_DIR . '/thumbnails');

            // Parse danh sách tài khoản thành mảng JSON
            $lines = explode("\n", trim($request->account_list));
            $accountsArray = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || !str_contains($line, '|')) continue;

                [$acc, $pass] = array_map('trim', explode('|', $line, 2));
                if (!$acc || !$pass) continue;

                $accountsArray[] = [
                    'u'   => $acc,
                    'p'   => $pass,
                    't'   => now()->toDateTimeString(),
                    'sid' => $sellerId // Lưu ID của người nạp
                ];
            }

            if (count($accountsArray) === 0) {
                throw new \Exception('Không có tài khoản hợp lệ trong danh sách.');
            }

            // Tạo 1 row cho nhóm random này
            GameAccount::create([
                'game_category_id'  => (int) $category->id,
                'account_name'      => 'RANDOM_GROUP_' . time(), // Tên nhóm
                'password'          => 'N/A',
                'price'             => (float) $request->price,
                'note'              => $request->note,
                'thumb'             => $thumbPath,
                'images'            => null,
                'accounts_data'     => $accountsArray,
                'status'            => 'available',
                'created_by'        => auth()->id(),
                'server'            => 13,
                'registration_type' => 'virtual',
                'planet'            => 'earth',
                'earring'           => true,
                'created_by'        => $sellerId,
            ]);

            DB::commit();

            return redirect()->route('seller.accounts.random.index')
                ->with('success', 'Đã thêm nhóm random với ' . count($accountsArray) . ' tài khoản!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Form sửa nhóm random
     */
    public function edit($id)
    {
        $sellerId = auth()->id();

        $randomGroup = GameAccount::with('category')
            ->where('id', $id)
            ->where(function ($q) use ($sellerId) {
                $q->where('created_by', $sellerId)
                  ->orWhereHas('category', function ($sub) {
                      $sub->where('is_global', true);
                  });
            })
            ->whereHas('category', function ($q) {
                $q->where('type', 'random');
            })
            ->firstOrFail();

        // Chuyển accounts_data thành text để hiển thị (LỌC: chỉ hiện acc của seller này)
        $accountsData = $randomGroup->accounts_data ?? [];
        $accountListLines = [];
        foreach ($accountsData as $item) {
            if (is_array($item)) {
                $ownerId = $item['sid'] ?? $randomGroup->created_by;
                if ($ownerId == $sellerId) {
                    $accountListLines[] = ($item['u'] ?? '') . '|' . ($item['p'] ?? '');
                }
            } else {
                // Hỗ trợ định dạng cũ: mặc định thuộc về người tạo group
                if ($randomGroup->created_by == $sellerId) {
                    $accountListLines[] = (string)$item;
                }
            }
        }
        $accountList = implode("\n", $accountListLines);

        // Lấy danh mục random
        $categories = GameCategory::where(function ($q) use ($sellerId) {
            $q->where('created_by', $sellerId)
              ->orWhere('is_global', true);
        })
        ->where('active', true)
        ->where('type', 'random')
        ->orderBy('id', 'asc')
        ->get();

        return view('seller.accounts.random.edit', compact('randomGroup', 'accountList', 'categories'));
    }

    /**
     * Cập nhật nhóm random
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'game_category_id' => 'required|exists:game_categories,id',
                'price'            => 'required|numeric|min:0',
                'note'             => 'nullable|string',
                'account_list'     => 'required|string',
                'thumb'            => 'nullable|image|mimes:jpeg,jpg,png,gif|max:10240',
            ]);

            DB::beginTransaction();

            $sellerId = auth()->id();

            $randomGroup = GameAccount::where('id', $id)
                ->where(function ($q) use ($sellerId) {
                    $q->where('created_by', $sellerId)
                      ->orWhereHas('category', function ($sub) {
                          $sub->where('is_global', true);
                      });
                })
                ->whereHas('category', function ($q) {
                    $q->where('type', 'random');
                })
                ->firstOrFail();

            $category = GameCategory::find($request->game_category_id);

            if (!$category || $category->type !== 'random') {
                throw new \Exception('Danh mục không hợp lệ.');
            }

            // Upload ảnh mới nếu có
            $thumbPath = $randomGroup->thumb;
            if ($request->hasFile('thumb')) {
                // Xóa ảnh cũ
                if ($randomGroup->thumb) {
                    UploadHelper::deleteByUrl($randomGroup->thumb);
                }
                $thumbPath = UploadHelper::upload($request->file('thumb'), self::UPLOAD_DIR . '/thumbnails');
            }

            // Parse danh sách tài khoản
            $lines = explode("\n", trim($request->account_list));
            $accountsArray = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || !str_contains($line, '|')) continue;

                [$acc, $pass] = array_map('trim', explode('|', $line, 2));
                if (!$acc || !$pass) continue;

                $accountsArray[] = [
                    'u'   => $acc,
                    'p'   => $pass,
                    't'   => now()->toDateTimeString(),
                    'sid' => $sellerId
                ];
            }

            // ⭐ LOGIC TRỘN ACC: Lấy acc cũ (không phải của mình) + acc mới (vừa nhập)
            $oldData = $randomGroup->accounts_data ?? [];
            $mergedData = [];

            foreach ($oldData as $item) {
                $ownerId = is_array($item) ? ($item['sid'] ?? $randomGroup->created_by) : $randomGroup->created_by;
                if ($ownerId != $sellerId) {
                    $mergedData[] = $item;
                }
            }

            // Gộp list acc mới của mình vào
            $finalData = array_merge($mergedData, $accountsArray);

            // Cập nhật
            $updateData = [
                'game_category_id' => (int) $category->id,
                'price'            => (float) $request->price,
                'note'             => $request->note,
                'thumb'            => $thumbPath,
                'accounts_data'    => $finalData,
                'status'           => count($finalData) > 0 ? 'available' : 'sold',
            ];

            // Chỉ chủ nhóm mới được đổi giá và ảnh/note chính
            if ($randomGroup->created_by !== $sellerId) {
                unset($updateData['price'], $updateData['note'], $updateData['thumb']);
            }

            $randomGroup->update($updateData);

            DB::commit();

            return redirect()->route('seller.accounts.random.index')
                ->with('success', 'Đã cập nhật nhóm random với ' . count($accountsArray) . ' tài khoản!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Xóa nhóm random
     */
    public function destroy($id)
    {
        try {
            $sellerId = auth()->id();

            $randomGroup = GameAccount::where('id', $id)
                ->where('created_by', $sellerId)
                ->whereHas('category', function ($q) {
                    $q->where('type', 'random');
                })
                ->firstOrFail();

            // Xóa ảnh
            if ($randomGroup->thumb) {
                UploadHelper::deleteByUrl($randomGroup->thumb);
            }

            $randomGroup->delete();

            return redirect()->route('seller.accounts.random.index')
                ->with('success', 'Đã xóa nhóm random!');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
