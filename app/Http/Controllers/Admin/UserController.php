<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MoneyTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Danh sách user
    public function index()
    {
        $title = 'Danh Sách Người Dùng';
        $users = User::orderBy('id', 'DESC')->get();
        return view('admin.users.index', compact('title', 'users'));
    }

    public function edit($id)
    {
        $title = 'Sửa Người Dùng #' . $id;
        $user = User::findOrFail($id);
        $transactions = MoneyTransaction::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.users.edit', compact('title', 'user', 'transactions'));
    }

public function update(Request $request, $id)
{
    try {
        $user = User::findOrFail($id);
        $oldBalance = $user->balance;

        if (!$request->filled('email')) {
            $request->merge([
                'email' => 'oneone' . $user->id . '@gmail.com'
            ]);
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:member,admin,seller',
            'balance' => 'required|numeric|min:0',
            'banned' => 'required|in:0,1',
            'password' => 'nullable|string|min:6|confirmed'
        ], [
            // Thông báo lỗi giữ nguyên
        ]);

        DB::beginTransaction();

        $balanceDifference = $validated['balance'] - $oldBalance;

        $updateData = [
            'email' => $validated['email'],
            'role' => $validated['role'],
            'balance' => $validated['balance'],
            'banned' => $validated['banned'],
        ];

        // Nếu có nhập mật khẩu thì hash và update
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        // Nếu số dư tăng → cộng thêm vào total_deposited
        if ($balanceDifference > 0) {
            $updateData['total_deposited'] = $user->total_deposited + $balanceDifference;
        }

        $user->update($updateData);

        // Ghi lịch sử giao dịch nếu có thay đổi số dư
        if ($balanceDifference != 0) {
            $status = $balanceDifference > 0 ? 'deposit' : 'withdraw';
            MoneyTransaction::create([
                'user_id' => $user->id,
                'type' => $status,
                'amount' => abs($balanceDifference),
                'balance_before' => $oldBalance,
                'balance_after' => $validated['balance'],
                'description' => 'ADMIN CẬP NHẬT SỐ DƯ'
            ]);
        }

        DB::commit();
        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật thông tin người dùng thành công!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()
            ->with('error', 'Có lỗi xảy ra khi cập nhật thông tin: ' . $e->getMessage());
    }
}

    public function destroy($id)
    {
        // Prevent deleting own account
        if ($id == auth()->id()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa tài khoản của chính mình!'
                ]);
            }
        }

        $user = User::findOrFail($id);
        $user->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa thành viên thành công!'
            ]);
        }
    }
}
