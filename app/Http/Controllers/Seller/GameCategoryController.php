<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\GameCategory;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameCategoryController extends Controller
{
    private const UPLOAD_DIR = 'categories';

    // 🟩 INDEX - Danh sách danh mục
    public function index()
    {
        $title = "Danh Sách Danh Mục Game";

        $query = GameCategory::with('creator')->orderByDesc('id');

        // Seller chỉ xem danh mục của mình hoặc danh mục dùng chung
        if (auth()->user()->role === 'seller') {
            $query->where(function ($q) {
                $q->where('created_by', auth()->id())
                  ->orWhere('is_global', true);
            });
        }

        $categories = $query->paginate(10);

        return view('seller.categories.index', compact('title', 'categories'));
    }

    // 🟨 CREATE - Form thêm mới
    public function create()
    {
        $title = "Thêm danh mục game mới";
        return view('seller.categories.create', compact('title'));
    }

    // 🟦 STORE - Lưu danh mục mới
    public function store(Request $request)
    {
        try {
            $request->validate([
                'type'        => 'required|in:play,clone,random',
                'name'        => 'required|string|max:255',
                'description' => 'required|string|max:500',
                'thumbnail'   => 'required|image|max:10240',
                'active'      => 'required|boolean',
                'is_global'   => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            $data = [
                'type'        => $request->type,
                'name'        => $request->name,
                'slug'        => Str::slug($request->name),
                'description' => $request->description,
                'active'      => $request->boolean('active'),
                'created_by'  => auth()->id(),
                'is_global'   => auth()->user()->role === 'admin' && $request->boolean('is_global'),
            ];

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = UploadHelper::upload($request->file('thumbnail'), self::UPLOAD_DIR);
            }

            GameCategory::create($data);

            DB::commit();

            return redirect()->route('seller.categories.index')
                ->with('success', 'Thêm Danh Mục Thành Công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error creating game category: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // 🟧 EDIT - Hiển thị form chỉnh sửa
    public function edit(GameCategory $category)
    {
        // Seller chỉ bị chặn nếu không phải danh mục của họ và không phải is_global
        if (auth()->user()->role === 'seller' &&
            $category->created_by !== auth()->id() && !$category->is_global) {
            return redirect()->route('seller.categories.index')
                ->with('error', 'Bạn Không Có Quyền Sửa Danh Mục Này');
        }

        $title = 'Chỉnh sửa danh mục game';
        return view('seller.categories.edit', compact('title', 'category'));
    }

    // 🟦 UPDATE - Cập nhật danh mục
    public function update(Request $request, GameCategory $category)
    {
        // Seller chỉ được sửa danh mục của mình hoặc is_global (dùng chung)
        if (auth()->user()->role === 'seller' &&
            $category->created_by !== auth()->id() && !$category->is_global) {
            return redirect()->route('seller.categories.index')
                ->with('error', 'Bạn Không Có Quyền Sửa Danh Mục Này');
        }

        try {
            $request->validate([
                'type'        => 'required|in:play,clone,random',
                'name'        => 'required|string|unique:game_categories,name,' . $category->id,
                'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'description' => 'required|string',
                'active'      => 'required|boolean',
                'is_global'   => 'nullable|boolean',
            ]);

            DB::beginTransaction();

            $data = [
                'type'        => $request->input('type'),
                'name'        => $request->input('name'),
                'slug'        => Str::slug($request->input('name')),
                'description' => $request->input('description'),
                'active'      => $request->boolean('active'),
            ];

            // Chỉ admin được thay đổi is_global
            if (auth()->user()->role === 'admin') {
                $data['is_global'] = $request->boolean('is_global');
            }

            if ($request->hasFile('thumbnail')) {
                if ($category->thumbnail) {
                    UploadHelper::deleteByUrl($category->thumbnail);
                }
                $data['thumbnail'] = UploadHelper::upload($request->file('thumbnail'), self::UPLOAD_DIR);
            }

            $category->update($data);

            DB::commit();

            return redirect()->route('seller.categories.index')
                ->with('success', 'Sửa Danh Mục Thành Công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating game category: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // 🟥 DESTROY - Xoá danh mục
    public function destroy(GameCategory $category)
    {
        // Seller không thể xoá danh mục dùng chung
        if (auth()->user()->role === 'seller' && ($category->is_global || $category->created_by !== auth()->id())) {
            return redirect()->route('seller.categories.index')
                ->with('error', 'Bạn Không Có Quyền Xoá Danh Mục Này');
        }

        try {
            DB::beginTransaction();

            if ($category->thumbnail) {
                UploadHelper::deleteByUrl($category->thumbnail);
            }

            $category->delete();

            DB::commit();

            return redirect()->route('seller.categories.index')
                ->with('success', 'Xoá Danh Mục Thành Công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting game category: ' . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
