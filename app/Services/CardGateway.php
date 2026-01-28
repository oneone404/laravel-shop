<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CardGateway
{
    public function buyCard(string $serviceCode, int $value, int $qty = 1, ?string $requestId = null): array
    {
        $partnerId   = (string) config('card.partner_id');
        $partnerKey  = trim((string) config('card.partner_key')); // KHÔNG log key này
        $wallet      = (string) config('card.wallet_number');
        $baseUrl     = rtrim((string) config('card.base_url'), '/');

        // Chuẩn hoá đầu vào
        $serviceCode = strtoupper($serviceCode);
        if ($qty < 1) $qty = 1;
        if ($value < 1) $value = 1;

        $command   = 'buycard';
        $requestId = $requestId ?: \Illuminate\Support\Str::ulid()->toString();

        // Ký số: md5(partner_key + partner_id + command + request_id)
        $sign      = md5($partnerKey . $partnerId . $command . $requestId);

        // Payload gửi đi (x-www-form-urlencoded)
        $payload = [
            'partner_id'    => $partnerId,
            'command'       => $command,
            'request_id'    => $requestId,
            'service_code'  => $serviceCode,
            'wallet_number' => $wallet,
            'value'         => $value,
            'qty'           => $qty,
            'sign'          => $sign,
        ];

        // ===== LOG tham số trước khi gửi (không lộ bí mật) =====
        $mask = function (?string $s) {
            if (!$s) return $s;
            $n = strlen($s);
            if ($n <= 6) return str_repeat('*', $n);
            return substr($s, 0, 3) . str_repeat('*', $n - 6) . substr($s, -3);
        };
        \Log::info('cardws.buycard.outgoing', [
            'meta' => [
                'url'             => "{$baseUrl}/api/cardws",
                'method'          => 'POST (form-url-encoded)',
                'partner_id'      => $partnerId,
                'command'         => $command,
                'request_id'      => $requestId,
                'service_code'    => $serviceCode,
                'value'           => $value,
                'qty'             => $qty,
                'wallet_number'   => $mask($wallet),
                'sign_preview'    => substr($sign, 0, 6) . '...' . substr($sign, -6),
                'partner_key_len' => strlen($partnerKey),
            ],
            'payload' => $payload,
        ]);

        try {
            $http = \Illuminate\Support\Facades\Http::asForm()
                ->timeout(15)
                ->retry(2, 300)
                ->withOptions([
                    'proxy' => null, // tránh đi qua proxy ngầm
                    'curl'  => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4, // ép IPv4
                    ],
                ])
                ->beforeSending(function ($request, $options) {
                    \Log::info('cardws.buycard.beforeSending', [
                        'url'  => (string) $request->url(),
                        'body' => $options['body'] ?? null, // chuỗi form-encoded thực tế
                    ]);
                });

            $resp = $http->post("{$baseUrl}/api/cardws", $payload);

            // Thử parse JSON; nếu không được thì để body thô
            $json = $resp->json();
            \Log::info('cardws.buycard.response', [
                'http_code' => $resp->status(),
                'json'      => $json ?? $resp->body(),
            ]);

            // Chuẩn hoá đầu ra
            $status  = (int) ($json['status'] ?? -1);
            $message = (string) ($json['message'] ?? '');

            // Chuẩn hoá danh sách thẻ về dạng thống nhất: [['serial'=>..., 'pin'=>..., 'name'=>..., 'expired'=>...], ...]
            $cards = [];
            $data  = $json['data'] ?? null;

            // Một số NCC đặt thẻ trong data.cards, hoặc data là list, hoặc đặt key 'code' cho PIN
            $rawItems = [];

            if (is_array($data) && array_key_exists('cards', $data) && is_array($data['cards'])) {
                $rawItems = $data['cards'];
            } elseif (is_array($data)) {
                // Có thể là 1 item hoặc list item
                $rawItems = isset($data[0]) ? $data : [$data];
            }

            foreach ($rawItems as $it) {
                if (!is_array($it)) continue;

                $serial  = $it['serial']      ?? $it['card_serial'] ?? $it['cardSeri'] ?? null;
                $pin     = $it['pin']         ?? $it['card_pin']    ?? $it['cardCode'] ?? $it['code'] ?? null;
                $name    = $it['name']        ?? $it['service_code'] ?? $it['telco'] ?? null;
                $expired = $it['expired']     ?? $it['expire'] ?? null;

                if ($serial && $pin) {
                    $cards[] = [
                        'serial'  => (string) $serial,
                        'pin'     => (string) $pin,
                        'name'    => $name ? (string) $name : null,
                        'expired' => $expired ? (string) $expired : null,
                    ];
                }
            }

            // Trả về cấu trúc thống nhất
            return [
                'http_code'   => $resp->status(),
                'request_id'  => $requestId,
                'status'      => $status,
                'message'     => $message,
                'cards'       => $cards,  // danh sách thẻ đã chuẩn hoá
                'provider'    => [
                    'time'        => is_array($data ?? null) ? ($data['time'] ?? null) : null,
                    'order_code'  => is_array($data ?? null) ? ($data['order_code'] ?? null) : null,
                    'raw'         => $json, // giữ raw để đối soát khi cần
                ],
            ];
        } catch (\Throwable $e) {
            \Log::error('cardws.buycard.exception', [
                'message'    => $e->getMessage(),
                'request_id' => $requestId,
            ]);

            return [
                'http_code'  => 0,
                'request_id' => $requestId,
                'status'     => -1,
                'message'    => 'Exception: ' . $e->getMessage(),
                'cards'      => [],
                'provider'   => ['raw' => null],
            ];
        }
    }

    public function buildSign(string $partnerKey, string $partnerId, string $command, string $requestId): string
    {
        return md5($partnerKey . $partnerId . $command . $requestId);
    }

    public function redownload(string $requestId, ?string $orderCode = null): array
    {
        $partnerId  = config('card.partner_id');
        $partnerKey = config('card.partner_key');
        $baseUrl    = rtrim(config('card.base_url'), '/');

        $command = 'redownload';
        $sign    = md5($partnerKey . $partnerId . $command . $requestId);

        $payload = [
            'partner_id' => $partnerId,
            'command'    => $command,
            'request_id' => $requestId,
            'sign'       => $sign,
        ];
        if ($orderCode) $payload['order_code'] = $orderCode;

        $resp = Http::asForm()->timeout(15)->retry(3, 300)->post("{$baseUrl}/api/cardws", $payload);
        $json = $resp->json() ?? [];

        // Chuẩn hoá đầu ra
        $cards = [];
        if (!empty($json['data']['cards'])) {
            foreach ($json['data']['cards'] as $c) {
                $cards[] = [
                    'serial'  => $c['serial'] ?? null,
                    'pin'     => $c['pin'] ?? ($c['code'] ?? null),
                    'name'    => $c['name'] ?? null,
                    'expired' => $c['expired'] ?? null,
                ];
            }
        }

        return [
            'http_code' => $resp->status(),
            'status'    => $json['status'] ?? -1,
            'message'   => $json['message'] ?? '',
            'cards'     => $cards,
            'provider'  => [
                'time'       => $json['data']['time'] ?? null,
                'order_code' => $json['data']['order_code'] ?? ($orderCode ?? null),
                'raw'        => $json,
            ],
        ];
    }
}
