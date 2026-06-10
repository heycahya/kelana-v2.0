<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil customer saat ini.
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        $customer = auth()->user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id_customer' => $customer->id_customer,
                'nama_customer' => $customer->nama_customer,
                'email' => $customer->email,
                'no_telp' => $customer->no_telp,
                'alamat' => $customer->alamat,
                'kontak_darurat' => $customer->kontak_darurat,
            ]
        ], 200);
    }

    /**
     * Perbarui profil customer saat ini.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $customerId = auth()->id();
        
        $validator = Validator::make($request->all(), [
            'nama_customer' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customerId, 'id_customer')
            ],
            'no_telp' => 'nullable|string|regex:/^[0-9]+$/|min:10|max:15',
            'alamat' => 'nullable|string',
            'kontak_darurat' => 'nullable|string|max:255',
        ], [
            'nama_customer.required' => 'Nama customer wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',
            'no_telp.regex' => 'Format nomor telepon tidak valid. Hanya diperbolehkan angka.',
            'no_telp.min' => 'Nomor telepon minimal 10 digit.',
            'no_telp.max' => 'Nomor telepon maksimal 15 digit.',
            'kontak_darurat.max' => 'Kontak darurat maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $messages) {
                $errors[$field] = $messages[0];
            }

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $errors
            ], 422);
        }

        $customer = auth()->user();
        $customer->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => [
                'id_customer' => $customer->id_customer,
                'nama_customer' => $customer->nama_customer,
                'email' => $customer->email,
                'no_telp' => $customer->no_telp,
                'alamat' => $customer->alamat,
                'kontak_darurat' => $customer->kontak_darurat,
            ]
        ], 200);
    }
}
