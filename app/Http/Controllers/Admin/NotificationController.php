<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\UploadHelper;

class NotificationController extends Controller
{
    /**
     * Hiển thị danh sách thông báo
     */
    private const UPLOAD_DIR = 'config';

    public function index()
    {
        $title = 'Quản lý thông báo';
        $notifications = Notification::orderBy('id', 'desc')->get();

        return view('admin.settings.notifications', compact('title', 'notifications'));
    }

    /**
     * Hiển thị form tạo thông báo
     */
    public function create()
    {
        $title = 'Thêm thông báo mới';

        return view('admin.settings.notifications-form', compact('title'));
    }

    /**
     * Lưu thông báo mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'class_favicon' => 'nullable|string|max:255',
            'content' => 'required|string|max:255',
            'thumb'   => 'nullable|image|max:2048', // không bắt buộc
        ]);

        // ✅ Bắt buộc phải có 1 trong 2: fa hoặc ảnh
        if (!$request->class_favicon && !$request->hasFile('thumb')) {
            return back()->with('error', 'Vui lòng nhập icon (fa-*) hoặc upload ảnh biểu tượng')->withInput();
        }

        try {
            DB::beginTransaction();

            // Xử lý class biểu tượng, đảm bảo bắt đầu với "fa-"
            $class_favicon = $request->class_favicon;
            if (!str_starts_with($class_favicon, 'fa-')) {
                $class_favicon = 'fa-' . $class_favicon;
            }

            $thumbPath = null;
            if ($request->file('thumb')) {
                $thumbPath = UploadHelper::upload(
                    $request->file('thumb'),
                    self::UPLOAD_DIR . '/thumbnails'
                );
            }

            Notification::create([
                'class_favicon' => $class_favicon,
                'content' => $request->content,
                'thumbnail' => $thumbPath,
            ]);

            DB::commit();

            return redirect()->route('admin.settings.notifications')
                ->with('success', 'Thêm thông báo mới thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi thêm thông báo: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi thêm thông báo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hiển thị form chỉnh sửa thông báo
     */
    public function edit($id)
    {
        $title = 'Chỉnh sửa thông báo';
        $notification = Notification::findOrFail($id);

        return view('admin.settings.notifications-form', compact('title', 'notification'));
    }

    /**
     * Cập nhật thông báo
     */
public function update(Request $request, $id)
{
    $request->validate([
        'class_favicon' => 'nullable|string|max:255',
        'content' => 'required|string|max:255',
        'thumb'   => 'nullable|image|max:2048', // không bắt buộc
    ]);

    // ✅ Bắt buộc phải có 1 trong 2: fa hoặc ảnh
    if (!$request->class_favicon && !$request->hasFile('thumb')) {
        return back()->with('error', 'Vui lòng nhập icon (fa-*) hoặc upload ảnh biểu tượng')->withInput();
    }


    try {
        DB::beginTransaction();

        // ⚠️ LẤY THÔNG BÁO TRƯỚC
        $notification = Notification::findOrFail($id);

        // Xử lý class biểu tượng, đảm bảo bắt đầu với "fa-"
        $class_favicon = $request->class_favicon;
        if (!str_starts_with($class_favicon, 'fa-')) {
            $class_favicon = 'fa-' . $class_favicon;
        }

        // Ảnh icon: nếu không upload mới thì giữ lại ảnh cũ
        $thumbPath = $notification->thumbnail;

        if ($request->hasFile('thumb')) {
            $thumbPath = UploadHelper::upload(
                $request->file('thumb'),
                self::UPLOAD_DIR . '/thumbnails'
            );
        }

        // Cập nhật
        $notification->update([
            'class_favicon' => $class_favicon,
            'content'       => $request->content,
            'thumbnail'     => $thumbPath,
        ]);

        DB::commit();

        return redirect()->route('admin.settings.notifications')
            ->with('success', 'Cập nhật thông báo thành công');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Lỗi cập nhật thông báo: ' . $e->getMessage());

        return redirect()->back()
            ->with('error', 'Đã xảy ra lỗi khi cập nhật thông báo: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * Xóa thông báo
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Tìm và xóa thông báo
            $notification = Notification::findOrFail($id);
            $notification->delete();

            DB::commit();

            return redirect()->route('admin.settings.notifications')
                ->with('success', 'Xóa thông báo thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xóa thông báo: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa thông báo: ' . $e->getMessage());
        }
    }
}
