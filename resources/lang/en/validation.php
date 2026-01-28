<?php
// Ghi đè thông báo hệ thống
return [
    'required' => ':attribute Không Được Để Trống',
    'string' => 'Trường :attribute phải là chuỗi ký tự',
    'numeric' => 'Trường :attribute phải là số',
    'integer' => 'Trường :attribute phải là số nguyên',
    'current_password' => 'Mật khẩu hiện tại không đúng',
    'min' => [
        'numeric' => 'Trường :attribute Không Được Nhỏ Hơn :min',
        'string' => 'Trường :attribute Phải Lớn Hơn :min Ký Tự',
    ],
    'max' => [
        'numeric' => 'Trường :attribute Không Được Lớn Hơn :max',
        'string' => 'Trường :attribute Phải Nhỏ Hơn :max Ký Tự',
    ],
    'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ',
    'unique' => ':attribute Đã Tồn Tại',
    'date' => 'Trường :attribute không phải là định dạng của ngày',
    'array' => 'Trường :attribute phải là dạng mảng',
    'boolean' => 'Trường :attribute phải là true hoặc false',
    'confirmed' => 'Trường :attribute xác nhận không khớp',
    'image' => 'Trường :attribute phải là định dạng hình ảnh',
    'in' => 'Giá trị đã chọn trong trường :attribute không hợp lệ',
    'between' => [
        'numeric' => 'Trường :attribute phải nằm giữa :min và :max',
        'string' => 'Trường :attribute phải có độ dài giữa :min và :max ký tự',
    ],


    // Custom cho từng field
    'password' => [
        'confirmed' => 'Mật khẩu nhập lại không khớp',
    ],
    'attributes' => [
        'username' => 'Tài Khoản',
        'password' => 'Password',
        'serial' => 'Seri',
        'pin' => 'Mã Thẻ',
        'telco' => 'Loại Thẻ',
        'amount' => 'Mệnh Giá',
        'id_account' => 'ID Tài Khoản',
        'game_account' => 'Tài Khoản',
        'game_password' => 'Password',
        'package_id' => 'Dịch Vụ',
    ],
 
];