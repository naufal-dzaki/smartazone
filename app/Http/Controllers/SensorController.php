<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Validation\Rule;

class SensorController extends Controller
{
    /**
     * 📌 REGISTER HIKER
     */
    public function registerHiker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id'   => 'required|integer|exists:mountain_devices,id',
            'booking_id'  => 'required|integer|exists:mountain_bookings,id',
            'mountain_id' => 'required|integer|exists:mountains,id',
            'hiker_name'  => 'required|string|max:100',
            'hiker_nik'   => 'nullable|string|max:50',
            'hiker_phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        try {
            $validated = $validator->validated();

            $active = DB::table('mountain_hiker_status')
                ->where('device_id', $validated['device_id'])
                ->where('status', 'active')
                ->first();

            if ($active) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Device masih digunakan oleh pendaki lain.',
                ], 409);
            }

            $id = DB::table('mountain_hiker_status')->insertGetId([
                'device_id'   => $validated['device_id'],
                'booking_id'  => $validated['booking_id'],
                'mountain_id' => $validated['mountain_id'],
                'hiker_name'  => $validated['hiker_name'],
                'hiker_nik'   => $validated['hiker_nik'] ?? null,
                'hiker_phone' => $validated['hiker_phone'] ?? null,
                'status'      => 'active',
                'started_at'  => now(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $data = DB::table('mountain_hiker_status')->where('id', $id)->first();

            return response()->json([
                'status'  => 'success',
                'message' => 'Pendaki berhasil terdaftar dan perangkat diaktifkan.',
                'data'    => $data,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mendaftarkan pendaki.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 📊 UPDATE HIKER LOG
     */
    public function updateHikerLog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id'    => 'required|integer|exists:mountain_devices,id',
            'heart_rate'   => 'nullable|numeric|min:0|max:250',
            'stress_level' => 'nullable|numeric|min:0|max:100',
            'spo2'         => 'nullable|numeric|min:0|max:100',
            'latitude'     => 'nullable|numeric|between:-90,90',
            'longitude'    => 'nullable|numeric|between:-180,180',
            'timestamp'    => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        try {
            $validated = $validator->validated();

            $active = DB::table('mountain_hiker_status')
                ->where('device_id', $validated['device_id'])
                ->where('status', 'active')
                ->first();

            if (!$active) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Device tidak terdaftar atau pendaki sudah tidak aktif.',
                ], 404);
            }

            $logId = DB::table('mountain_hiker_logs')->insertGetId([
                'device_id'    => $validated['device_id'],
                'heart_rate'   => $validated['heart_rate'] ?? null,
                'stress_level' => $validated['stress_level'] ?? null,
                'spo2'         => $validated['spo2'] ?? null,
                'latitude'     => $validated['latitude'] ?? null,
                'longitude'    => $validated['longitude'] ?? null,
                'timestamp'    => $validated['timestamp'] ?? now(),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            if ($request->has('battery_level')) {
                DB::table('mountain_devices')
                    ->where('id', $validated['device_id'])
                    ->update(['battery_level' => $request->battery_level, 'updated_at' => now()]);
            }

            $data = DB::table('mountain_hiker_logs')->where('id', $logId)->first();

            return response()->json([
                'status'  => 'success',
                'message' => 'Data log sensor berhasil disimpan.',
                'data'    => $data,
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal menyimpan data sensor.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 🔴 UNREGISTER HIKER
     */
    public function unregisterHiker(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|integer|exists:mountain_devices,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        try {
            $deviceId = $request->input('device_id');

            $updated = DB::table('mountain_hiker_status')
                ->where('device_id', $deviceId)
                ->where('status', 'active')
                ->update([
                    'status'     => 'inactive',
                    'ended_at'   => now(),
                    'updated_at' => now(),
                ]);

            if (!$updated) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Tidak ada pendaki aktif yang menggunakan device ini.',
                ], 404);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Pendaki berhasil di-unregister dan perangkat dinonaktifkan.',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal melakukan unregister pendaki.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
