<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\TripLeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Registrasi customer baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_customer' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:customers,email',
            'password' => 'required|string|min:8',
            'no_telp' => 'required|string|max:15',
            'alamat' => 'nullable|string',
        ], [
            'nama_customer.required' => 'Nama customer wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[$field] = $messages[0];
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $errors
            ], 400);
        }

        try {
            $customer = Customer::create([
                'nama_customer' => $request->nama_customer,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil.',
                'data' => [
                    'id_customer' => $customer->id_customer,
                    'nama_customer' => $customer->nama_customer,
                    'email' => $customer->email,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server.'
            ], 500);
        }
    }

    /**
     * Authenticate user and issue API token (Multi-Role).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // A. Cek Admin
        $admin = Admin::where('username', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            $token = $admin->createToken('admin_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil.',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $admin->id_admin,
                        'nama' => $admin->nama_admin,
                        'role' => 'admin'
                    ]
                ]
            ], 200);
        }

        // B. Cek Customer
        $customer = Customer::where('email', $request->email)->first();
        if ($customer && Hash::check($request->password, $customer->password)) {
            $token = $customer->createToken('customer_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil.',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $customer->id_customer,
                        'nama' => $customer->nama_customer,
                        'role' => 'customer'
                    ]
                ]
            ], 200);
        }

        // C. Cek Trip Leader
        $leader = TripLeader::where('email', $request->email)->first();
        if ($leader && Hash::check($request->password, $leader->password)) {
            $token = $leader->createToken('leader_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil.',
                'data' => [
                    'token' => $token,
                    'user' => [
                        'id' => $leader->id_leader,
                        'nama' => $leader->nama_leader,
                        'role' => 'trip_leader'
                    ]
                ]
            ], 200);
        }

        // Return Gagal (Kredensial Tidak Valid)
        return response()->json([
            'status' => 'error',
            'message' => 'Email/Username atau password tidak valid.'
        ], 401);
    }
}
