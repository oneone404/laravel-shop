<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameService;
use App\Models\ServicePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicePackageController extends Controller
{
    public function index($serviceId = null)
    {
        $title = 'Danh sách gói dịch vụ';

        $packages = ServicePackage::with('service');

        if ($serviceId) {
            $service = GameService::findOrFail($serviceId);
            $packages = $packages->where('game_service_id', $serviceId);
            $title .= ' - ' . $service->name;
        }

        $packages = $packages->orderBy('id', 'DESC')->get();

        return view('admin.packages.index', compact('title', 'packages', 'serviceId'));
    }

    public function create($serviceId = null)
    {
        $title = 'Thêm gói dịch vụ mới';
        $services = GameService::where('active', 1)->get();
        $selectedService = null;

        if ($serviceId) {
            $selectedService = GameService::findOrFail($serviceId);
        }

        return view('admin.packages.create', compact('title', 'services', 'selectedService'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_service_id' => 'required|exists:game_services,id',
            'name'           => 'required|string|max:255',
            'price'          => 'required|integer|min:0',
            'estimated_time' => 'nullable|integer|min:1',
            'description'    => 'nullable|string',
            'active'         => 'required|boolean',
            'thumbnail'      => 'nullable|image|max:2048', // <= ảnh
        ]);

        $data = $request->only([
            'game_service_id','name','price','estimated_time','description','active'
        ]);

        // Lưu ảnh nếu có
        if ($request->hasFile('thumbnail')) {
            // lưu vào storage/app/public/packages
            $path = $request->file('thumbnail')->store('packages', 'public');
            $data['thumbnail'] = $path; // ví dụ: packages/abc.jpg
        }

        ServicePackage::create($data);

        return redirect()
            ->route('admin.packages.index', ['service_id' => $request->game_service_id])
            ->with('success', 'Gói dịch vụ đã được tạo thành công.');
    }

    public function edit($id)
    {
        $title = 'Chỉnh sửa gói dịch vụ';
        $package = ServicePackage::findOrFail($id);
        $services = GameService::where('active', 1)->get();

        return view('admin.packages.edit', compact('title', 'package', 'services'));
    }

    public function update(Request $request, $id)
    {
        $package = ServicePackage::findOrFail($id);

        $request->validate([
            'game_service_id' => 'required|exists:game_services,id',
            'name'           => 'required|string|max:255',
            'price'          => 'required|integer|min:0',
            'estimated_time' => 'nullable|integer|min:1',
            'description'    => 'nullable|string',
            'active'         => 'required|boolean',
            'thumbnail'      => 'nullable|image|max:2048', // <= ảnh
            'remove_thumbnail' => 'nullable|boolean',       // <= xóa ảnh
        ]);

        $data = $request->only([
            'game_service_id','name','price','estimated_time','description','active'
        ]);

        // Xóa ảnh nếu người dùng yêu cầu
        if ($request->boolean('remove_thumbnail') && $package->thumbnail) {
            Storage::disk('public')->delete($package->thumbnail);
            $data['thumbnail'] = null;
        }

        // Thay ảnh mới
        if ($request->hasFile('thumbnail')) {
            if ($package->thumbnail) {
                Storage::disk('public')->delete($package->thumbnail);
            }
            $path = $request->file('thumbnail')->store('packages', 'public');
            $data['thumbnail'] = $path;
        }

        $package->update($data);

        return redirect()
            ->route('admin.packages.index', ['service_id' => $request->game_service_id])
            ->with('success', 'Gói dịch vụ đã được cập nhật thành công.');
    }

    public function destroy(ServicePackage $package)
    {
        $serviceId = $package->game_service_id;
        $package->delete();

        return response()->json(['success' => true, 'service_id' => $serviceId]);
    }
}
