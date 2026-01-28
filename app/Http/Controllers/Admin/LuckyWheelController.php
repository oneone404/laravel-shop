<?php
/**
 * Copyright (c) 2025 FPT University
 *
 * @autor    Phạm Hoàng Tuấn
 * @email     phamhoangtuanqn@gmail.com
 * @facebook  fb.com/phamhoangtuanqn
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LuckyWheel;
use App\Models\LuckyWheelHistory;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LuckyWheelController extends Controller
{
    /**
     * Đường dẫn thư mục lưu ảnh
     */
    private const UPLOAD_DIR = 'lucky-wheels';

    /**
     * Hiển thị danh sách vòng quay may mắn
     */
    public function index()
    {
        $title = 'Quản lý vòng quay may mắn';
        $luckyWheels = LuckyWheel::orderBy('created_at', 'desc')->get();
        return view('admin.lucky-wheels.index', compact('luckyWheels', 'title'));
    }

    /**
     * Hiển thị form tạo mới vòng quay may mắn
     */
    public function create()
    {
        $title = 'Thêm vòng quay may mắn';

        // Tạo sẵn mảng config với 8 phần tử mặc định
        $defaultConfig = [
            ['type' => 'gold', 'content' => 'Trúng 1 tỷ vàng', 'amount' => 1000000000, 'probability' => 10],
            ['type' => 'gold', 'content' => 'Trúng 50 triệu vàng', 'amount' => 50000000, 'probability' => 15],
            ['type' => 'gold', 'content' => 'Trúng 75 triệu vàng', 'amount' => 75000000, 'probability' => 15],
            ['type' => 'gold', 'content' => 'Trúng 100 triệu vàng', 'amount' => 100000000, 'probability' => 15],
            ['type' => 'gold', 'content' => 'Trúng 130 triệu vàng', 'amount' => 130000000, 'probability' => 15],
            ['type' => 'gold', 'content' => 'Trúng 200 triệu vàng', 'amount' => 200000000, 'probability' => 10],
            ['type' => 'gold', 'content' => 'Trúng 250 triệu vàng', 'amount' => 250000000, 'probability' => 10],
            ['type' => 'gold', 'content' => 'Trúng 500 triệu vàng', 'amount' => 500000000, 'probability' => 10],
        ];

        return view('admin.lucky-wheels.create', compact('title', 'defaultConfig'));
    }

    /**
     * Lưu vòng quay may mắn mới
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'price_per_spin' => 'required|numeric|min:1000',
                'thumbnail' => 'nullable|image',
                'wheel_image' => 'nullable|image',
                'description' => 'nullable|string',
                'rules' => 'required|string',
                'active' => 'required|boolean',
                'config' => 'required|array|size:8',
                'config.*.type' => 'required|in:gold,gem',
                'config.*.content' => 'required|string|max:255',
                'config.*.amount' => 'required|numeric|min:0',
                'config.*.probability' => 'required|numeric|min:0|max:100',
            ]);

            if (is_array($request->config)) {
    $totalProbability = 0;
    foreach ($request->config as $item) {
        $totalProbability += $item['probability'];
    }

    if ($totalProbability != 100) {
        return back()->withInput()->withErrors(['config' => 'Tổng xác suất phải bằng 100%']);
    }

    $luckyWheel->config = $request->config;
}


            DB::beginTransaction();

            $luckyWheel = new LuckyWheel();
            $luckyWheel->name = $request->name;
            $luckyWheel->slug = Str::slug($request->name);
            $luckyWheel->price_per_spin = $request->price_per_spin;

            // Xử lý upload ảnh đại diện
            if ($request->hasFile('thumbnail')) {
                $luckyWheel->thumbnail = UploadHelper::upload($request->file('thumbnail'), self::UPLOAD_DIR . '/thumbnails');
            }

            // Xử lý upload ảnh vòng quay
            if ($request->hasFile('wheel_image')) {
                $luckyWheel->wheel_image = UploadHelper::upload($request->file('wheel_image'), self::UPLOAD_DIR . '/wheel-images');
            }

            $luckyWheel->description = $request->description;
            $luckyWheel->rules = $request->rules;
            $luckyWheel->active = $request->active;
            $luckyWheel->config = $request->config;
            $luckyWheel->save();

            DB::commit();

            return redirect()->route('admin.lucky-wheels.index')
                ->with('success', 'Tạo vòng quay may mắn thành công');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return back()->withInput()->withErrors(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    /**
     * Hiển thị form chỉnh sửa vòng quay may mắn
     */
    public function edit(LuckyWheel $luckyWheel)
    {
        $title = 'Chỉnh sửa vòng quay may mắn';

        // Không cần json_decode vì config đã được cast sang array tự động bởi model
        $config = $luckyWheel->config;

        // Đảm bảo config luôn có 8 phần tử
        if (!is_array($config) || count($config) < 8) {
            // Nếu config không phải là array hoặc dưới 8 phần tử, khởi tạo mới
            $config = [];
            for ($i = 0; $i < 8; $i++) {
                $config[] = [
                    'type' => 'gold',
                    'content' => 'Trúng vàng',
                    'amount' => 0,
                    'probability' => 0
                ];
            }
        }

        return view('admin.lucky-wheels.edit', compact('luckyWheel', 'title', 'config'));
    }

    /**
     * Cập nhật vòng quay may mắn
     */
    public function update(Request $request, $id)
{
    try {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'price_per_spin' => 'nullable|numeric|min:1000',
            'thumbnail' => 'nullable|image',
            'wheel_image' => 'nullable|image',
            'description' => 'nullable|string',
            'rules' => 'nullable|string',
            'active' => 'nullable|boolean',
            'config' => 'nullable|array|size:8',
            'config.*.type' => 'required_with:config|in:gold,gem',
            'config.*.content' => 'required_with:config|string|max:255',
            'config.*.amount' => 'required_with:config|numeric|min:0',
            'config.*.probability' => 'required_with:config|numeric|min:0|max:100',
        ]);

        // Mặc định nếu không có config gửi lên
        $defaultConfig = [
            ["type" => "gem", "content" => "Trúng 5000 ngọc", "amount" => 5000, "probability" => 10],
            ["type" => "gem", "content" => "Trúng 50 ngọc", "amount" => 50, "probability" => 15],
            ["type" => "gem", "content" => "Trúng 100 ngọc", "amount" => 100, "probability" => 20],
            ["type" => "gem", "content" => "Trúng 150 ngọc", "amount" => 150, "probability" => 25],
            ["type" => "gem", "content" => "Trúng 200 ngọc", "amount" => 200, "probability" => 10],
            ["type" => "gem", "content" => "Trúng 300 ngọc", "amount" => 300, "probability" => 10],
            ["type" => "gem", "content" => "Trúng 500 ngọc", "amount" => 500, "probability" => 5],
            ["type" => "gem", "content" => "Trúng 1000 ngọc", "amount" => 1000, "probability" => 5],
        ];

        DB::beginTransaction();

        $luckyWheel = LuckyWheel::findOrFail($id);

        // Lấy config từ request nếu có, ngược lại dùng mặc định
        $configData = $request->config ?? $defaultConfig;

        // Kiểm tra tổng xác suất
        if (is_array($configData)) {
            $totalProbability = 0;
            foreach ($configData as $item) {
                $totalProbability += $item['probability'];
            }
            if ($totalProbability != 100) {
                return back()->withInput()->withErrors(['config' => 'Tổng xác suất phải bằng 100%']);
            }
        } else {
            $configData = $defaultConfig;
        }

        // Cập nhật từng trường nếu có trong request, nếu không có giữ nguyên hoặc gán mặc định
        $luckyWheel->name = $request->name ?? $luckyWheel->name;
        $luckyWheel->slug = $request->name ? Str::slug($request->name) : $luckyWheel->slug;
        $luckyWheel->price_per_spin = $request->price_per_spin ?? $luckyWheel->price_per_spin;

        // Xử lý upload ảnh đại diện nếu có
        if ($request->hasFile('thumbnail')) {
            if ($luckyWheel->thumbnail) {
                UploadHelper::deleteByUrl($luckyWheel->thumbnail);
            }
            $luckyWheel->thumbnail = UploadHelper::upload($request->file('thumbnail'), self::UPLOAD_DIR . '/thumbnails');
        }

        // Xử lý upload ảnh vòng quay nếu có
        if ($request->hasFile('wheel_image')) {
            if ($luckyWheel->wheel_image) {
                UploadHelper::deleteByUrl($luckyWheel->wheel_image);
            }
            $luckyWheel->wheel_image = UploadHelper::upload($request->file('wheel_image'), self::UPLOAD_DIR . '/wheel-images');
        }

        $luckyWheel->description = $request->description ?? $luckyWheel->description;
        $luckyWheel->rules = $request->rules ?? $luckyWheel->rules ?? '';
        $luckyWheel->active = $request->active ?? $luckyWheel->active;

        $luckyWheel->config = $configData;

        $luckyWheel->save();

        DB::commit();

        return redirect()->route('admin.lucky-wheels.index')
            ->with('success', 'Cập nhật vòng quay may mắn thành công');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error($e);
        return back()->withInput()->withErrors(['message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
    }
}


    /**
     * Xóa vòng quay may mắn
     */
    public function destroy(LuckyWheel $luckyWheel)
    {
        try {
            DB::beginTransaction();

            // Delete images if exists
            if ($luckyWheel->thumbnail) {
                UploadHelper::deleteByUrl($luckyWheel->thumbnail);
            }
            if ($luckyWheel->wheel_image) {
                UploadHelper::deleteByUrl($luckyWheel->wheel_image);
            }

            // Delete lucky wheel
            $luckyWheel->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Xóa vòng quay may mắn thành công'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting lucky wheel: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hiển thị lịch sử vòng quay
     */
    public function history()
    {
        $title = 'Lịch sử vòng quay may mắn';
        $history = LuckyWheelHistory::with(['user', 'luckyWheel'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.lucky-wheels.history', compact('title', 'history'));
    }
}