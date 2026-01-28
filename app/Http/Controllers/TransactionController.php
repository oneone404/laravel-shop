<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MoneyTransaction;
use App\Models\BankDeposit;
use Illuminate\Support\Facades\DB; // For database transactions
use Illuminate\Support\Facades\Log;  // For logging errors
use Illuminate\Support\Facades\Validator; // For input validation
use Carbon\Carbon; // For date parsing
use App\Events\ShowPopupEvent;

class TransactionController extends Controller
{
    public function processTransaction(Request $request)
    {
        // 1. Input Validation
        $validator = Validator::make($request->all(), [
            'transactionDate' => 'required|string', // Will be parsed later
            'creditAmount'    => 'required|numeric|gt:0', // Must be a number greater than 0
            'addDescription'  => 'required|string|max:255',
            'refNo'           => 'required|string|max:100|unique:money_transactions,reference_id', // Unique check in DB
        ], [
            'refNo.unique' => 'DUPLICATE_TRANSACTION_DB_CHECK' // Custom message for unique rule
        ]);

        if ($validator->fails()) {
            // Check if the specific error is due to refNo being a duplicate
            if ($validator->errors()->has('refNo') && $validator->errors()->first('refNo') === 'DUPLICATE_TRANSACTION_DB_CHECK') {
                return response()->json([
                    'code'    => 'DUPLICATE_TRANSACTION',
                    'message' => 'GIAO DỊCH ĐÃ ĐƯỢC XỬ LÝ TRƯỚC ĐÓ!', // More user-friendly
                ], 409);
            }
            return response()->json([
                'code'    => 'VALIDATION_ERROR',
                'message' => 'Dữ liệu không hợp lệ.',
                'errors'  => $validator->errors(),
            ], 422); // Unprocessable Entity
        }

        $validatedData = $validator->validated(); // Get validated data

        $transactionDateInput = $validatedData['transactionDate'];
        $creditAmount = (float)$validatedData['creditAmount']; // Cast after validation
        $addDescription = $validatedData['addDescription'];   // This is "NAPTIEN<userid>" from Python
        $refNo = $validatedData['refNo'];

        // 2. Parse transactionDate
        $parsedTransactionDate = null;
        try {
            // Attempt to parse formats from Python: "Month Day, Year H:MM AM/PM" or "Month Day, Year"
            $parsedTransactionDate = Carbon::parse($transactionDateInput);
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            // Try specific formats if generic parse fails or if you need stricter parsing
            $formatsToTry = [
                'F d, Y g:i A', // e.g., June 05, 2025 9:55 PM
                'F d, Y',       // e.g., June 05, 2025
            ];
            foreach ($formatsToTry as $format) {
                try {
                    $parsedTransactionDate = Carbon::createFromFormat($format, $transactionDateInput);
                    if ($parsedTransactionDate) break;
                } catch (\Carbon\Exceptions\InvalidFormatException $ex) {
                    continue;
                }
            }
            if (!$parsedTransactionDate) {
                 Log::warning('Failed to parse transactionDate from Python', ['date_string' => $transactionDateInput, 'refNo' => $refNo]);
                // Decide: fail the request or default to now()? For bank transactions, actual date is important.
                // For now, let's default to current time if parsing fails, but log it.
                // Or return an error:
                // return response()->json([
                //     'code' => 'INVALID_DATE_FORMAT',
                //     'message' => 'Định dạng transactionDate không hợp lệ.'
                // ], 400);
                $parsedTransactionDate = Carbon::now(); // Fallback, or handle as error
            }
        }


        // 3. Extract userid from addDescription (which is "NAPTIEN<userid>")
        // The Python script now sends addDescription as "NAPTIEN<userid>" directly
        preg_match('/ONEDZ(\d+)/i', $addDescription, $matches); // 'i' for case-insensitive

        if (isset($matches[1])) {
            $userid = $matches[1];
            $user = User::find($userid);

            if ($user) {
                // Duplicate check again here is belt-and-suspenders if DB unique constraint fails,
                // but the `unique` validation rule is better.
                // if (MoneyTransaction::where('reference_id', $refNo)->exists()) {
                // return response()->json(['code' => 'DUPLICATE_TRANSACTION', /*...*/], 409);
                // }

                try {
                    DB::beginTransaction();

                    $balanceBefore = $user->balance;

                    $user->balance += $creditAmount;
                    $user->total_deposited += $creditAmount;
                    $user->save();

                    // 4. Save to money_transactions with parsed date
                    $moneyTransaction = MoneyTransaction::create([
                        'user_id'        => $user->id,
                        'type'           => 'deposit',
                        'amount'         => $creditAmount,
                        'balance_before' => $balanceBefore,
                        'balance_after'  => $user->balance,
                        'description'    => "NẠP TIỀN QUA MBBANK - MÃ GIAO DỊCH: {$refNo}",
                        'reference_id'   => $refNo,
                        // If your MoneyTransaction model uses timestamps (created_at, updated_at)
                        // and you want to set `created_at` to the bank's transaction time:
                        'created_at'     => $parsedTransactionDate,
                        'updated_at'     => $parsedTransactionDate, // Or Carbon::now() if only created_at should be bank time
                    ]);

                    // 5. Save to bank_deposits
                    BankDeposit::create([
                        'transaction_id' => $refNo, // Assuming this links to reference_id
                        'user_id'        => $user->id,
                        'account_number' => config('services.mbbank.deposit_account_number', '805885'), // Get from config
                        'amount'         => $creditAmount,
                        'content'        => $addDescription, // This is the "NAPTIEN<userid>"
                        'bank'           => 'MBBank',
                        // You might also want to store the parsedTransactionDate here
                        // 'deposit_date'    => $parsedTransactionDate, // If you have such a column
                    ]);

                    DB::commit();

                    return response()->json([
                        'code'    => 'SUCCESS',
                        'message' => 'THÀNH CÔNG!',
                        'transaction' => [
                            'user_balance'    => $user->balance,
                            'total_deposited' => $user->total_deposited,
                            'transaction_ref' => $refNo,
                        ],
                    ], 200);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Transaction processing database error for refNo: ' . $refNo, [
                        'user_id'       => $userid,
                        'error_message' => $e->getMessage(),
                        // 'trace' => $e->getTraceAsString() // Potentially too verbose for production logs
                    ]);
                    return response()->json([
                        'code'    => 'TRANSACTION_ERROR',
                        'message' => 'Lỗi xử lý giao dịch. Vui lòng thử lại sau hoặc liên hệ hỗ trợ.',
                    ], 500); // Internal Server Error
                }
            } else {
                return response()->json([
                    'code'    => 'USER_NOT_FOUND',
                    'message' => 'USER KHÔNG TỒN TẠI!',
                ], 404);
            }
        } else {
            // This case means the addDescription (which Python sends as "NAPTIEN<userid>")
            // did not match the expected pattern.
            return response()->json([
                'code'    => 'INVALID_DESCRIPTION_FORMAT',
                'message' => 'Mô tả giao dịch không hợp lệ, không trích xuất được mã người dùng ONEDZ.',
            ], 400);
        }
    }
}