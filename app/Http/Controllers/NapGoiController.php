<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GoiNapshop;

class NapGoiController extends Controller
{
    public function showForm(Request $request)
    {
        $status = $request->query('status');

        $gois = GoiNapshop::where('active', 1);

        if ($status && in_array($status, ['hot_item', 'goi_special', 'goi_promotion'])) {
            $gois->where('status', $status);
        }

        $gois = $gois->get();

        if (session()->has('jtoken') && session()->has('userID')) {
            $response = Http::asForm()->post('https://billing.vnggames.com/fe/api/store/getRoles', [
                'jtoken'    => session('jtoken'),
                'userID'    => session('userID'),
                'serverID'  => '',
                'loginType' => '9',
                'lang'      => 'VI',
            ]);

            $data = $response->json();

            if ($data['returnCode'] === 1) {
                $roleID = array_key_first($data['data']);
                $role = $data['data'][$roleID];

                return view('user.nap-goi', [
                    'loggedIn' => true,
                    'roleID' => $role['roleID'],
                    'roleName' => $role['roleName'],
                    'gois' => $gois,
                    'status' => $status,
                ]);
            }
        }

        return view('user.nap-goi', [
            'loggedIn' => false,
            'gois' => $gois,
            'status' => $status,
        ]);
    }


    public function login(Request $request)
    {
        $roleID = $request->input('roleID');

        $response = Http::asForm()->post('https://billing.vnggames.com/fe/api/auth/quick', [
            'platform'   => 'mobile',
            'clientKey'  => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjIjoxMDY2MSwiYSI6MTA2NjEsInMiOjF9.B08-6v9oP3rNxrvImC-WBO-AN0mru77ZNLOgqosNIjA',
            'loginType'  => '9',
            'lang'       => 'VI',
            'jtoken'     => '',
            'userID'     => '',
            'roleID'     => $roleID,
            'roleName'   => $roleID,
            'serverID'   => '',
            'getVgaId'   => '1',
        ]);

        $data = $response->json();

        if ($data['returnCode'] === 1) {
            session([
                'jtoken' => $data['data']['jtoken'],
                'userID' => $data['data']['userID'],
            ]);
            return redirect()->route('nap-goi.form');
        }

        return redirect()->back()->withErrors(['login' => 'Đăng nhập thất bại: '.$data['returnMessage']]);
    }

    public function changeID()
    {
        session()->forget(['jtoken', 'userID']);
        return redirect()->route('nap-goi.form');
    }
}
