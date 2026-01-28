<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\GameService;
use Illuminate\Http\Request;
use App\Models\ServiceHistory;
use Illuminate\Support\Facades\Auth;

class GameServiceController extends Controller
{

    public function show($slug)
    {
        $service = GameService::with('packages')->where('slug', $slug)->firstOrFail();
        $title = $service->name;
    
        $user = Auth::user();
    
        if ($service->type === 'pay-game' && $user && $user->role === 'seller') {
            foreach ($service->packages as $package) {
                if (in_array($package->id, [22, 30])) {
                    $package->price_after_discount = max(0, $package->price - 5000);
                } else {
                    $package->price_after_discount = $package->price;
                }
            }
        } else {
            foreach ($service->packages as $package) {
                $package->price_after_discount = $package->price;
            }
        }
    
        return view('user.service.show', compact('service', 'title'));
    }

    public function showAll()
    {
        $title = 'Dịch vụ thuê';
    
        // Dịch vụ thuê (có packages)
        $services = GameService::where('active', 1)
            ->with('packages')
            ->get();
    
        // Dịch vụ cày thuê (có orderCount)
        $boostingServices = GameService::where('active', 1)
            ->orderBy('updated_at', 'desc')
            ->get();
    
        foreach ($boostingServices as $service) {
            $service->orderCount = ServiceHistory::where('game_service_id', $service->id)->count();
        }
    
        return view('user.service.show-all', compact('title', 'services', 'boostingServices'));
    }
}
