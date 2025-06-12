<?php

namespace App\Soap\Handlers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class WalletService
{
    public function registerClient($document, $name, $email, $phone)
    {
        try {
            if (empty($document)) return json_encode($this->errorResponse('01', 'Missing field: document'));
            if (empty($name)) return json_encode($this->errorResponse('01', 'Missing field: name'));
            if (empty($email)) return json_encode($this->errorResponse('01', 'Missing field: email'));
            if (empty($phone)) return json_encode($this->errorResponse('01', 'Missing field: phone'));

            if (DB::table('clients')->where('document', $document)->exists()) {
                return json_encode($this->errorResponse('02', 'Client already exists'));
            }

            DB::table('clients')->insert([
                'document' => $document,
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'balance' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return json_encode($this->successResponse('Client registered successfully'));
        } catch (\Throwable $e) {
            Log::error('registerClient error', ['exception' => $e->getMessage()]);
            return json_encode($this->errorResponse('99', 'Internal server error'));
        }
    }

    public function loadWallet($document, $phone, $amount)
    {
        try {
            if (empty($document)) return json_encode($this->errorResponse('01', 'Missing field: document'));
            if (empty($phone)) return json_encode($this->errorResponse('01', 'Missing field: phone'));
            if (empty($amount)) return json_encode($this->errorResponse('01', 'Missing field: amount'));

            $client = DB::table('clients')
                ->where('document', $document)
                ->where('phone', $phone)
                ->first();

            if (!$client) {
                return json_encode($this->errorResponse('03', 'Client not found'));
            }

            DB::table('clients')
                ->where('id', $client->id)
                ->update(['balance' => $client->balance + $amount]);

            return json_encode($this->successResponse('Wallet recharged'));
        } catch (\Throwable $e) {
            Log::error('loadWallet error', ['exception' => $e->getMessage()]);
            return json_encode($this->errorResponse('99', 'Internal server error'));
        }
    }

    public function makePurchase($document, $phone)
    {
        try {
            if (empty($document)) return json_encode($this->errorResponse('01', 'Missing field: document'));
            if (empty($phone)) return json_encode($this->errorResponse('01', 'Missing field: phone'));

            $client = DB::table('clients')
                ->where('document', $document)
                ->where('phone', $phone)
                ->first();

            if (!$client) {
                return json_encode($this->errorResponse('03', 'Client not found'));
            }

            $token = rand(100000, 999999);
            $sessionId = (string) Str::uuid();

            DB::table('payment_tokens')->insert([
                'client_id' => $client->id,
                'session_id' => $sessionId,
                'token' => $token,
                'created_at' => now(),
            ]);

            Mail::raw("Your confirmation token is: $token", function ($message) use ($client) {
                $message->to($client->email)->subject('Purchase Confirmation');
            });

            return json_encode([
                'success' => true,
                'cod_error' => '00',
                'message_error' => 'Token sent to email',
                'data' => ['session_id' => $sessionId],
            ]);
        } catch (\Throwable $e) {
            Log::error('makePurchase error', ['exception' => $e->getMessage()]);
            return json_encode($this->errorResponse('99', 'Internal server error'));
        }
    }

    public function confirmPayment($session_id, $token)
    {
        try {
            if (empty($session_id)) return json_encode($this->errorResponse('01', 'Missing field: session_id'));
            if (empty($token)) return json_encode($this->errorResponse('01', 'Missing field: token'));

            $row = DB::table('payment_tokens')->where('session_id', $session_id)->first();

            if (!$row || $row->token != $token) {
                return json_encode($this->errorResponse('04', 'Invalid session or token'));
            }

            $client = DB::table('clients')->where('id', $row->client_id)->first();

            if ($client->balance <= 0) {
                return json_encode($this->errorResponse('05', 'Insufficient balance'));
            }

            DB::table('clients')->where('id', $client->id)->update([
                'balance' => $client->balance - 1,
            ]);

            DB::table('payment_tokens')->where('session_id', $session_id)->delete();

            return json_encode($this->successResponse('Payment confirmed'));
        } catch (\Throwable $e) {
            Log::error('confirmPayment error', ['exception' => $e->getMessage()]);
            return json_encode($this->errorResponse('99', 'Internal server error'));
        }
    }

    public function checkBalance($document, $phone)
    {
        try {
            if (empty($document)) return json_encode($this->errorResponse('01', 'Missing field: document'));
            if (empty($phone)) return json_encode($this->errorResponse('01', 'Missing field: phone'));

            $client = DB::table('clients')
                ->where('document', $document)
                ->where('phone', $phone)
                ->first();

            if (!$client) {
                return json_encode($this->errorResponse('03', 'Client not found'));
            }

            return json_encode([
                'success' => true,
                'cod_error' => '00',
                'message_error' => 'Balance retrieved',
                'data' => [
                    'balance' => $client->balance,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('checkBalance error', ['exception' => $e->getMessage()]);
            return json_encode($this->errorResponse('99', 'Internal server error'));
        }
    }

    private function errorResponse(string $code, string $message): array
    {
        return [
            'success' => false,
            'cod_error' => $code,
            'message_error' => $message,
            'data' => null,
        ];
    }

    private function successResponse(string $message): array
    {
        return [
            'success' => true,
            'cod_error' => '00',
            'message_error' => $message,
            'data' => null,
        ];
    }
}
