<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameHack;
use Illuminate\Http\Request;
use App\Models\KeyVip;
use Illuminate\Support\Facades\DB;

class GameHackController extends Controller
{
    public function index()
    {
        // Hiển thị mới cập nhật lên trước
        $games = GameHack::orderByDesc('updated_at')->orderByDesc('id')->get();
        return view('admin.game-hack.index', compact('games'));
    }

    public function create()
    {
        return view('admin.game-hack.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                 => ['required','string','max:255'],
            'version'              => ['nullable','string','max:100'],
            'description'          => ['nullable','string'],
            'logo'                 => ['nullable','url','max:1024'],
            'thumbnail'            => ['nullable','url','max:1024'],
            'download_link'        => ['nullable','url','max:2048'],
            'download_link_global' => ['nullable','url','max:2048'],
            'api_hack'             => ['nullable','string','max:100'],
            'api_type'             => ['nullable','string','max:100'],
            'solink'               => ['nullable','integer','min:0'],
            'active'               => ['required','in:0,1'],
            'platform'             => ['nullable','string','max:100'],
            'size'                 => ['nullable','string','max:100'],
            'images'               => ['nullable','string'], // textarea
            'logo_file'            => ['nullable','image','mimes:jpeg,png,jpg,gif','max:2048'],
            'thumbnail_file'       => ['nullable','image','mimes:jpeg,png,jpg,gif','max:4096'],
            'image_files.*'        => ['nullable','image','mimes:jpeg,png,jpg,gif','max:4096'],
        ]);

        $payload = $validated;

        // Xử lý Upload Logo
        if ($request->hasFile('logo_file')) {
            $payload['logo'] = \App\Helpers\UploadHelper::upload($request->file('logo_file'), 'hacks/logos');
        }

        // Xử lý Upload Thumbnail
        if ($request->hasFile('thumbnail_file')) {
            $payload['thumbnail'] = \App\Helpers\UploadHelper::upload($request->file('thumbnail_file'), 'hacks/thumbnails');
        }

        // Xử lý Upload Ảnh chi tiết (thêm vào danh sách hiện tại nếu có)
        $currentImages = $this->parseImages($validated['images'] ?? '');
        if ($request->hasFile('image_files')) {
            $uploadedImages = \App\Helpers\UploadHelper::uploadMultiple($request->file('image_files'), 'hacks/gallery');
            $currentImages = array_merge($currentImages, $uploadedImages);
        }
        $payload['images'] = $currentImages;

        $payload['active'] = (int)($validated['active'] ?? 0);

        GameHack::create($payload);

        return redirect()->route('admin.game-hack.index')->with('success', 'TẠO GAME THÀNH CÔNG!');
    }

    public function edit(GameHack $gameHack)
    {
        return view('admin.game-hack.edit', compact('gameHack'));
    }

    public function update(Request $request, GameHack $gameHack)
    {
        $validated = $request->validate([
            'name'                 => ['required','string','max:255'],
            'version'              => ['nullable','string','max:100'],
            'description'          => ['nullable','string'],
            'logo'                 => ['nullable','url','max:1024'],
            'thumbnail'            => ['nullable','url','max:1024'],
            'download_link'        => ['nullable','url','max:2048'],
            'download_link_global' => ['nullable','url','max:2048'],
            'api_hack'             => ['nullable','string','max:100'],
            'api_type'             => ['nullable','string','max:100'],
            'solink'               => ['nullable','integer','min:0'],
            'active'               => ['required','in:0,1'],
            'platform'             => ['nullable','string','max:100'],
            'size'                 => ['nullable','string','max:100'],
            'images'               => ['nullable','string'], // textarea
            'logo_file'            => ['nullable','image','mimes:jpeg,png,jpg,gif','max:2048'],
            'thumbnail_file'       => ['nullable','image','mimes:jpeg,png,jpg,gif','max:4096'],
            'image_files.*'        => ['nullable','image','mimes:jpeg,png,jpg,gif','max:4096'],
        ]);

        $payload = $validated;

        // Xử lý Upload Logo
        if ($request->hasFile('logo_file')) {
            $payload['logo'] = \App\Helpers\UploadHelper::upload($request->file('logo_file'), 'hacks/logos');
        }

        // Xử lý Upload Thumbnail
        if ($request->hasFile('thumbnail_file')) {
            $payload['thumbnail'] = \App\Helpers\UploadHelper::upload($request->file('thumbnail_file'), 'hacks/thumbnails');
        }

        // Xử lý Upload Ảnh chi tiết (thêm vào danh sách hiện tại)
        $currentImagesArray = $this->parseImages($validated['images'] ?? '');
        if ($request->hasFile('image_files')) {
            $uploadedImages = \App\Helpers\UploadHelper::uploadMultiple($request->file('image_files'), 'hacks/gallery');
            $currentImagesArray = array_merge($currentImagesArray, $uploadedImages);
        }
        $payload['images'] = $currentImagesArray;

        $payload['active'] = (int)($validated['active'] ?? 0);

        $gameHack->update($payload);

        return redirect()->route('admin.game-hack.index')->with('success', 'CẬP NHẬT THÀNH CÔNG');
    }

public function destroy(Request $request, GameHack $gameHack)
{
    try {
        $gameHack->delete();

        // AJAX/JSON → trả JSON 200
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'XOÁ THÀNH CÔNG!',
            ], 200);
        }

        // Form submit thường → redirect + flash
        return redirect()
            ->route('admin.game-hack.index')
            ->with('success', 'XOÁ THÀNH CÔNG!');
    } catch (\Throwable $e) {
        \Log::error('Delete GameHack failed', [
            'id'  => $gameHack->id,
            'err' => $e->getMessage(),
        ]);

        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xóa game hack',
            ], 500);
        }

        return redirect()
            ->route('admin.game-hack.index')
            ->with('error', 'Có lỗi xảy ra khi xóa game hack');
    }
}

    private function parseImages(?string $input): array
    {
        $input = trim((string)$input);
        if ($input === '') return [];

        // Nếu dán JSON array hợp lệ, decode luôn
        if (str_starts_with($input, '[') && str_ends_with($input, ']')) {
            $decoded = json_decode($input, true);
            if (is_array($decoded)) {
                return array_values(array_filter(array_map('trim', $decoded), fn($u) => $u !== ''));
            }
        }

        // Mặc định: mỗi dòng 1 URL
        $lines = preg_split('/\r\n|\r|\n/', $input);
        return array_values(array_filter(array_map('trim', $lines), fn($u) => $u !== ''));
    }

    public function addKey()
    {
        $games = GameHack::orderBy('name')->get();

        // Lấy số key còn lại theo game và thời gian sử dụng
        $keysSummary = KeyVip::select('game', 'time_use', DB::raw('count(*) as total'))
            ->groupBy('game', 'time_use')
            ->get()
            ->groupBy('game'); // nhóm theo game

        // Đọc KEY_MODE từ database thay vì .env
        $keyMode = \App\Helpers\ConfigHelper::get('KEY_MODE', 'db');

        // Lấy các cấu hình API
        $apiConfigs = [
            'HACKVIET_EMAIL' => \App\Helpers\ConfigHelper::get('HACKVIET_EMAIL', ''),
            'HACKVIET_PASSWORD' => \App\Helpers\ConfigHelper::get('HACKVIET_PASSWORD', ''),
            'HACKVIET_BASE_URL' => \App\Helpers\ConfigHelper::get('HACKVIET_BASE_URL', 'https://hackviet.io'),
            'HACKVIET_SHOP_SLUG' => \App\Helpers\ConfigHelper::get('HACKVIET_SHOP_SLUG', 'shop-82-kcvara'),
            'HACKVIET_GAME_SLUG' => \App\Helpers\ConfigHelper::get('HACKVIET_GAME_SLUG', 'play-together'),
            'XLINK_API_URL' => \App\Helpers\ConfigHelper::get('XLINK_API_URL', 'https://xlink.co/api'),
            'XLINK_API_TOKEN' => \App\Helpers\ConfigHelper::get('XLINK_API_TOKEN', ''),
        ];

        return view('admin.game-hack.add-key', compact('games', 'keysSummary', 'keyMode', 'apiConfigs'));
    }

    // Lưu key
    public function storeKey(Request $request)
    {
        $validated = $request->validate([
            'game_type'    => 'required|string', // com.vng.playtogether | com.haegin.playtogether | all
            'key_values'   => 'required|string',
            'time_use'     => 'required|integer|min:1',
            'device_limit' => 'required|integer|min:1',
            'price'        => 'required|numeric|min:0',
        ]);

        $keys = preg_split('/\r\n|\r|\n/', $validated['key_values']);

        foreach($keys as $key) {
            $key = trim($key);
            if ($key === '') continue;

            KeyVip::create([
                'game' => $validated['game_type'],
                'key_value' => $key,
                'time_use' => $validated['time_use'],
                'device_limit' => $validated['device_limit'],
                'price' => $validated['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route('admin.game-hack.add-key')
            ->with('success', 'THÊM KEY GAME THÀNH CÔNG!');
    }

    public function apiAddKeys(Request $request)
    {
        // API Key bảo mật
        $secret = $request->header('X-API-KEY');
        if ($secret !== env('ADD_KEY_SECRET')) {
            return response()->json(['error' => 'Invalid API Key'], 401);
        }

        // Validate input
        $request->validate([
            'game_type' => 'required|string',
            'keys' => 'required|string',
            'time_use' => 'required|integer|min:1',
            'device_limit' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
        ]);

        $gameType = $request->game_type;

        // Tách key mỗi dòng
        $keys = array_filter(array_map('trim', explode("\n", $request->keys)));

        foreach ($keys as $keyValue) {
            \App\Models\KeyVip::create([
                'game'         => $gameType,     // ✔ FIX
                'key_value'    => $keyValue,
                'time_use'     => $request->time_use,
                'device_limit' => $request->device_limit,
                'price'        => $request->price,
                'status'       => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'added_keys' => count($keys),
        ]);
    }

    public function apiStats(Request $request)
    {
        // 🔐 Kiểm tra API key
        $secret = $request->header('X-API-KEY');
        if ($secret !== env('ADD_KEY_SECRET')) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid API Key'
            ], 401);
        }

        // Lấy thống kê key (không check active vì bạn không dùng)
        $keys = DB::table('key_vips')
            ->select(
                'time_use',
                'device_limit',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('time_use', 'device_limit')
            ->orderBy('time_use')
            ->orderBy('device_limit')
            ->get();

        $data = [];

        foreach ($keys as $k) {
            $data[] = [
                "label"        => "Key: {$k->time_use} Ngày",
                "time_use"     => $k->time_use,
                "device_limit" => $k->device_limit,
                "remaining"    => $k->total
            ];
        }

        return response()->json([
            "success"      => true,
            "total_groups" => count($data),
            "data"         => $data
        ]);
    }

    /**
     * Toggle KEY_MODE between 'api' and 'db'
     */
    public function toggleKeyMode(Request $request)
    {
        $currentMode = \App\Helpers\ConfigHelper::get('KEY_MODE', 'db');
        $newMode = ($currentMode === 'api') ? 'db' : 'api';
        
        \App\Helpers\ConfigHelper::set('KEY_MODE', $newMode);
        
        return response()->json([
            'success' => true,
            'mode' => $newMode,
            'message' => 'Đã chuyển sang chế độ ' . strtoupper($newMode)
        ]);
    }
    /**
     * Update API configurations for HackViet and XLink
     */
    public function updateApiConfigs(Request $request)
    {
        $validated = $request->validate([
            'HACKVIET_EMAIL' => 'nullable|string|max:255',
            'HACKVIET_PASSWORD' => 'nullable|string|max:255',
            'HACKVIET_BASE_URL' => 'nullable|url',
            'HACKVIET_SHOP_SLUG' => 'nullable|string|max:255',
            'HACKVIET_GAME_SLUG' => 'nullable|string|max:255',
            'XLINK_API_URL' => 'nullable|url',
            'XLINK_API_TOKEN' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            \App\Helpers\ConfigHelper::set($key, $value ?? '');
        }

        return redirect()
            ->route('admin.game-hack.add-key')
            ->with('success', 'Cập nhật cấu hình API thành công!');
    }
}
