<?php

namespace App\Http\Controllers;

use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DistrictController extends Controller
{
    // 1. Ambil Semua Data District
    public function index()
    {
        $districts = District::with('city.province')->whereNull('deleted_at')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data district berhasil diambil',
            'data' => $districts,
        ], 200);
    }

    // 2. Ambil Semua Data District berdasarkan ID City
    public function getByCity($city_id)
    {
        $districts = District::with('city.province')
            ->whereNull('deleted_at')
            ->where('city_id', $city_id)
            ->get();

        if ($districts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data district untuk city ini tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data district berdasarkan city berhasil diambil',
            'data' => $districts,
        ], 200);
    }

    // 3. Buat Data Baru District
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|integer|exists:city,city_id',
            'district_code' => 'required|string|max:10|unique:district,district_code',
            'district_name' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $district = District::create([
            'city_id' => $request->city_id,
            'district_code' => $request->district_code,
            'district_name' => $request->district_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data district berhasil dibuat',
            'data' => $district->load('city.province'),
        ], 201);
    }

    // 4. Ambil Detail Data District
    public function show($id)
    {
        $district = District::with('city.province')->whereNull('deleted_at')->find($id);

        if (! $district) {
            return response()->json([
                'success' => false,
                'message' => 'Data district tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail district berhasil diambil',
            'data' => $district,
        ], 200);
    }

    // 5. Update Data District
    public function update(Request $request, $id)
    {
        $district = District::whereNull('deleted_at')->find($id);

        if (! $district) {
            return response()->json([
                'success' => false,
                'message' => 'Data district tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'city_id' => 'sometimes|required|integer|exists:city,city_id',
            'district_code' => 'sometimes|required|string|max:10|unique:district,district_code,'.$id.',district_id',
            'district_name' => 'sometimes|required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $district->update($request->only(['city_id', 'district_code', 'district_name']));

        return response()->json([
            'success' => true,
            'message' => 'Data district berhasil diupdate',
            'data' => $district->load('city.province'),
        ], 200);
    }

    // 6. Hapus Data District (Soft Delete)
    public function destroy($id)
    {
        $district = District::whereNull('deleted_at')->find($id);

        if (! $district) {
            return response()->json([
                'success' => false,
                'message' => 'Data district tidak ditemukan',
            ], 404);
        }

        $district->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data district berhasil dihapus',
        ], 200);
    }
}
