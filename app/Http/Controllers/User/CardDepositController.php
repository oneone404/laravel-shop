<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\CardDeposit;
use App\Models\MoneyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Events\ShowPopupEvent;

class CardDepositController extends Controller
{
    public function __construct()
    {
        if (!config_get('payment.card.active', true)) {
            abort(403, 'Truy cập không hợp lệ!');
        }
    }

    public function processCardDeposit(Request $request)
    {
        $request->validate([
            'telco' => 'required|string|in:VIETTEL,MOBIFONE,VINAPHONE,VIETNAMOBILE,ZING,GARENA',
            'amount' => 'required|numeric|in:10000,20000,30000,50000,100000,200000,500000,1000000',
            'serial' => 'required|string|min:5|max:20',
            'pin' => 'required|string|min:5|max:20'
        ]);

        if (CardDeposit::where('status', 'processing')->where('user_id', Auth::id())->count() >= 5) {
            return redirect()->route('profile.deposit-card')
                ->with('error', 'QUÁ NHIỀU THẺ CHỜ XỬ LÝ!')->withInput();
        }

        $partnerWeb = config_get('payment.card.partner_website');

        try {
            $partner_id = config_get('payment.card.partner_id', '');
            $partner_key = config_get('payment.card.partner_key', '');
            $request_id = rand(111111111111, 9999999999999);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://$partnerWeb/chargingws/v2", [
                'telco' => $request->telco,
                'code' => $request->pin,
                'serial' => $request->serial,
                'amount' => $request->amount,
                'request_id' => $request_id,
                'partner_id' => $partner_id,
                'sign' => md5($partner_key . $request->pin . $request->serial),
                'command' => 'charging'
            ]);

            if (!$response->successful()) {
                return redirect()->route('profile.deposit-card')
                    ->with('error', 'LỖI KẾT NỐI MÁY CHỦ!');
            }

            $status = $response->json('status');
            if ($status === 3 || $status === 100) {
                $message = $response->json('message');
                switch ($message) {
                    case 'INVALID_CARD':
                        $errorMessage = 'Thẻ Không Hợp Lệ';
                        break;
                    case 'lang.invalid_card_code':
                        $errorMessage = 'Mã Thẻ Không Hợp Lệ';
                        break;
                    case 'lang.invalid_card_code':
                        $errorMessage = 'Seri Không Hợp Lệ';
                        break;
                    default:
                        $errorMessage = 'LỖI: ' . $message;
                        break;
                }
            
                return redirect()->route('profile.deposit-card')
                    ->with('error', $errorMessage)
                    ->withInput();
            }

            $deposit = new CardDeposit();
            $deposit->user_id = Auth::id();
            $deposit->telco = $request->telco;
            $deposit->amount = $request->amount;
            $deposit->received_amount = $request->amount;
            $deposit->serial = $request->serial;
            $deposit->pin = $request->pin;
            $deposit->request_id = $request_id;
            $deposit->status = 'processing';
            $deposit->save();

            return redirect()->route('profile.deposit-card')
                ->with('success', 'ĐANG XỬ LÝ');

        } catch (\Exception $e) {
            \Log::error('CardDeposit error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('profile.deposit-card')
                ->with('error', 'VUI LÒNG THỬ LẠI SAU!')->withInput();
        }
    }
    
    public function handleCallback(Request $request)
    {
        $logPath = __DIR__ . '/card_callback.log';
        File::append($logPath, "[" . now() . "] CALLBACK RECEIVED: " . json_encode($request->all()) . "\n");

        try {
            $request->merge([
                'status' => (int) $request->input('status'),
                'declared_value' => (int) $request->input('declared_value'),
                'card_value' => (int) $request->input('card_value'),
                'value' => (int) $request->input('value'),
                'amount' => (int) $request->input('amount'),
            ]);

            $validated = $request->validate([
                'status' => 'required|integer',
                'message' => 'nullable|string',
                'request_id' => 'required|string',
                'declared_value' => 'required|integer',
                'card_value' => 'required|integer',
                'value' => 'required|integer',
                'amount' => 'required|integer',
                'code' => 'required|string',
                'serial' => 'required|string',
                'telco' => 'required|string',
                'trans_id' => 'required|string',
                'callback_sign' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            File::append($logPath, "[" . now() . "] VALIDATION ERROR: " . json_encode($e->validator->errors()) . "\n");
            return response()->json(['error' => $e->validator->errors()], 422);
        }

        $statusCode = (int) $validated['status'];
        $statusMapping = [
            1 => 'success',
            2 => 'success_smg',
            3 => 'error',
            4 => 'error',
            99 => 'processing',
            100 => 'error',
        ];
        $status = $statusMapping[$statusCode] ?? 'error';

        $cardDeposit = CardDeposit::with('user')->where('request_id', $validated['request_id'])->first();

        if (!$cardDeposit) {
            File::append($logPath, "[" . now() . "] NOT FOUND: Request ID {$validated['request_id']}\n");
            return response()->json(['message' => 'KHÔNG TÌM THẤY THẺ!'], 200);
        }

        if (in_array($cardDeposit->status, ['success', 'error'])) {
            File::append($logPath, "[" . now() . "] ALREADY PROCESSED: {$validated['request_id']} - STATUS: {$cardDeposit->status}\n");
            return response()->json(['message' => 'THẺ ĐÃ ĐƯỢC XỬ LÝ!'], 200);
        }

        if (!$cardDeposit->user) {
            File::append($logPath, "[" . now() . "] USER NOT FOUND: Request ID {$validated['request_id']}\n");
            return response()->json(['message' => 'NGƯỜI DÙNG KHÔNG TỒN TẠI!'], 404);
        }

        DB::beginTransaction();
        try {
            $amount = 0;

            if ($statusCode == 2) {
                $amount = (int) $validated['amount'];
                $description = 'NẠP SAI MỆNH GIÁ | CỘNG THEO CALLBACK: ' . number_format($amount) . ' VND';
            } elseif ($statusCode == 1) {
                // Thành công, chiết khấu theo từng nhà mạng
                $telco = strtoupper($validated['telco']);
                $discountMap = [
                    'VIETTEL' => 15,
                    'VINAPHONE' => 20,
                    'MOBIFONE' => 20,
                    'GARENA' => 10,
                    'ZING' => 10,
                ];
            
                $discount = $discountMap[$telco] ?? 50;
            
                $amount = (int) ($validated['card_value'] - ($validated['card_value'] * $discount / 100));
                $description = 'NẠP THẺ ' . $validated['telco'] . ' THÀNH CÔNG (' . number_format($amount) . ' VND)';
            }
        
            $cardDeposit->update([
                'received_amount' => $amount,
                'status' => $status,
                'response' => json_encode($validated, JSON_UNESCAPED_UNICODE),
            ]);
        
            File::append($logPath, "[" . now() . "] DB UPDATED: {$validated['request_id']} - STATUS: {$status}\n");
        
            if (in_array($status, ['success', 'success_smg'])) {
                $user = $cardDeposit->user;
                $previousBalance = $user->balance;
                $user->balance += $amount;
                $user->total_deposited += $amount;
                $user->save();
        
                $description = 'NẠP THẺ ' . $validated['telco'] . ' THÀNH CÔNG (' . number_format($amount) . ' VND)';
                if ($statusCode == 2) {
                    $description = 'SAI MỆNH GIÁ - CHỈ CỘNG 50%. ' . $description;
                }
        
                MoneyTransaction::create([
                    'user_id' => $user->id,
                    'type' => 'deposit',
                    'amount' => $amount,
                    'balance_before' => $previousBalance,
                    'balance_after' => $user->balance,
                    'description' => $description,
                    'reference_id' => $cardDeposit->id
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'ĐÃ XỬ LÝ CALLBACK. UPDATE DB'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            File::append($logPath, "[" . now() . "] EXCEPTION: {$e->getMessage()}\n");
            return response()->json(['message' => 'LỖI XỬ LÝ CALLBACK: ' . $e->getMessage()], 500);
        }
    }
}
