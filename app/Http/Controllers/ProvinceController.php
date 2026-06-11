<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    // 1. Ambil Semua Data Province
    public function index()
    {
        $provinces = Province::all();

        return response()->json([
            'success' => true,
            'message' => 'Data province berhasil diambil',
            'data' => $provinces,
        ], 200);
    }

    // 2. Buat Data Baru Province
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province_code' => 'required|string|max:10|unique:province,province_code',
            'province_name' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $province = Province::create([
            'province_code' => $request->province_code,
            'province_name' => $request->province_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data province berhasil dibuat',
            'data' => $province,
        ], 201);
    }

    // 3. Ambil Detail Data Province
    public function show($id)
    {
        $province = Province::whereNull('deleted_at')->find($id);

        if (! $province) {
            return response()->json([
                'success' => false,
                'message' => 'Data province tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail province berhasil diambil',
            'data' => $province,
        ], 200);
    }

    // 4. Update Data Province
    public function update(Request $request, $id)
    {
        $province = Province::whereNull('deleted_at')->find($id);

        if (! $province) {
            return response()->json([
                'success' => false,
                'message' => 'Data province tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'province_code' => 'sometimes|required|string|max:10|unique:province,province_code,'.$id.',province_id',
            'province_name' => 'sometimes|required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $province->update($request->only(['province_code', 'province_name']));

        return response()->json([
            'success' => true,
            'message' => 'Data province berhasil diupdate',
            'data' => $province,
        ], 200);
    }

    // 5. Hapus Data Province (Soft Delete)
    public function destroy($id)
    {
        $province = Province::whereNull('deleted_at')->find($id);

        if (! $province) {
            return response()->json([
                'success' => false,
                'message' => 'Data province tidak ditemukan',
            ], 404);
        }

        $province->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data province berhasil dihapus',
        ], 200);
    }
}
