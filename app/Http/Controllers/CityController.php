<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    // 1. Ambil Semua Data City
    public function index()
    {
        $cities = City::with('province')->whereNull('deleted_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data city berhasil diambil',
            'data' => $cities,
        ], 200);
    }

    // 2. Ambil Semua Data City berdasarkan ID Province
    public function getByProvince($province_id)
    {
        $cities = City::with('province')
            ->whereNull('deleted_at')
            ->where('province_id', $province_id)
            ->get();

        if ($cities->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data city untuk province ini tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data city berdasarkan province berhasil diambil',
            'data' => $cities,
        ], 200);
    }

    // 3. Buat Data Baru City
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province_id' => 'required|integer|exists:province,province_id',
            'city_code' => 'required|string|max:10|unique:city,city_code',
            'city_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $city = City::create([
            'province_id' => $request->province_id,
            'city_code' => $request->city_code,
            'city_name' => $request->city_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data city berhasil dibuat',
            'data' => $city->load('province'),
        ], 201);
    }

    // 4. Ambil Detail Data City
    public function show($id)
    {
        $city = City::with('province')->whereNull('deleted_at')->find($id);

        if (! $city) {
            return response()->json([
                'success' => false,
                'message' => 'Data city tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail city berhasil diambil',
            'data' => $city,
        ], 200);
    }

    // 5. Update Data City
    public function update(Request $request, $id)
    {
        $city = City::whereNull('deleted_at')->find($id);

        if (! $city) {
            return response()->json([
                'success' => false,
                'message' => 'Data city tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'province_id' => 'sometimes|required|integer|exists:province,province_id',
            'city_code' => 'sometimes|required|string|max:10|unique:city,city_code,'.$id.',city_id',
            'city_name' => 'sometimes|required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $city->update($request->only(['province_id', 'city_code', 'city_name']));

        return response()->json([
            'success' => true,
            'message' => 'Data city berhasil diupdate',
            'data' => $city->load('province'),
        ], 200);
    }

    // 6. Hapus Data City (Soft Delete)
    public function destroy($id)
    {
        $city = City::whereNull('deleted_at')->find($id);

        if (! $city) {
            return response()->json([
                'success' => false,
                'message' => 'Data city tidak ditemukan',
            ], 404);
        }

        $city->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data city berhasil dihapus',
        ], 200);
    }
}
