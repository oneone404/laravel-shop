<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\GameAccount;
use App\Models\GameCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\UploadHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameAccountController extends Controller
{
    /**
     * Đường dẫn thư mục lưu ảnh
     */
    private const UPLOAD_DIR = 'accounts';

    public function index()
    {
        $title = 'Danh sách tài khoản game';
        $accounts = GameAccount::with(['category', 'buyer'])->orderBy('id', "DESC")->get();
        return view('admin.accounts.index', compact('title', 'accounts'));
    }

    public function create()
    {
        $title = 'Thêm tài khoản game mới';
        $categories = GameCategory::where('active', true)->get();
        return view('admin.accounts.create', compact('title', 'categories'));
    }

    public function store(Request $request)
{
    try {
        // Nếu đang dùng danh sách tài khoản, loại bỏ account_name và password khỏi request
        if ($request->filled('account_list')) {
            $request->request->remove('account_name');
            $request->request->remove('password');
        }

        // Validate dữ liệu
        $request->validate([
            'game_category_id' => 'required|exists:game_categories,id',
            'price' => 'required|numeric|min:0',
            'server' => 'required|integer',
            'registration_type' => 'required|in:virtual,real',
            'planet' => 'required|in:earth,namek,xayda',
            'earring' => 'boolean',
            'note' => 'nullable|string',
            'thumb' => 'required|image|mimes:jpeg,png,jpg,gif',
            'status' => 'required|in:available,sold',
            'account_list' => 'nullable|string',
            'account_name' => 'required_without:account_list|string|max:255',
            'password' => 'required_without:account_list|string|max:255',
        ]);

        DB::beginTransaction();

        // Upload thumbnail
        $thumbPath = UploadHelper::upload($request->file('thumb'), self::UPLOAD_DIR . '/thumbnails');

        // Dữ liệu chung cho tất cả tài khoản
        $commonData = [
            'game_category_id' => $request->game_category_id,
            'price' => $request->price,
            'status' => $request->status,
            'server' => $request->input('server'), // Ép lấy từ input form
            'registration_type' => $request->registration_type,
            'planet' => $request->planet,
            'earring' => $request->earring,
            'thumb' => $thumbPath,
        ];

        // Trường hợp thêm nhiều tài khoản
        if ($request->filled('account_list')) {
            $lines = explode("\n", trim($request->account_list));
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || !str_contains($line, '|')) continue;

                [$acc, $pass] = array_map('trim', explode('|', $line));
                if (!$acc || !$pass) continue;

                GameAccount::create(array_merge($commonData, [
                    'account_name' => $acc,
                    'password' => $pass,
                ]));
            }
        } else {
            // Trường hợp thêm 1 tài khoản
            GameAccount::create(array_merge($commonData, [
                'account_name' => $request->account_name,
                'password' => $request->password,
            ]));
        }

        DB::commit();

        return redirect()->route('admin.accounts.index')->with('success', 'TẠO THÀNH CÔNG');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error creating game account: ' . $e->getMessage());
        return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
    }
}


    public function edit(GameAccount $account)
    {
        $title = 'Chỉnh sửa tài khoản game';
        $categories = GameCategory::where('active', true)->get();
        return view('admin.accounts.edit', compact('title', 'account', 'categories'));
    }

    public function update(Request $request, GameAccount $account)
    {
        try {
            $request->validate([
                'game_category_id' => 'required|exists:game_categories,id',
                'account_name' => 'required|string|max:255',
                'password' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'server' => 'required|integer',
                'registration_type' => 'required|in:virtual,real',
                'planet' => 'required|in:earth,namek,xayda',
                'earring' => 'boolean',
                'note' => 'nullable|string',
                'thumb' => 'nullable|image|mimes:jpeg,png,jpg,gif',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif'
            ]);

            DB::beginTransaction();

            $data = $request->except(['thumb', 'images']);

            if ($request->hasFile('thumb')) {
                // Delete old thumbnail
                if ($account->thumb) {
                    UploadHelper::deleteByUrl($account->thumb);
                }
                $data['thumb'] = UploadHelper::upload($request->file('thumb'), self::UPLOAD_DIR . '/thumbnails');
            }

            if ($request->hasFile('images')) {
                // Delete old images
                if ($account->images) {
                    $oldImages = json_decode($account->images, true);
                    foreach ($oldImages as $image) {
                        UploadHelper::deleteByUrl($image);
                    }
                }

                // Store new images
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = UploadHelper::upload($image, self::UPLOAD_DIR . '/images');
                    $imagePaths[] = $path;
                }
                $data['images'] = json_encode($imagePaths);
            }

            $account->update($data);

            DB::commit();

            return redirect()->route('admin.accounts.index')
                ->with('success', 'SỬA THÀNH CÔNG');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating game account: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy(GameAccount $account)
    {
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

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting game account: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa tài khoản game: ' . $e->getMessage()
            ]);
        }
    }
}
