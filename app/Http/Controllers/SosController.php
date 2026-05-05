<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Events\SosSignalCreated;

class SosController extends Controller
{
    public function trigger(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id'  => 'required|integer|exists:mountain_devices,id',
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude'  => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $activeHiker = DB::table('mountain_hiker_status')
            ->where('device_id', $validated['device_id'])
            ->where('status', 'active')
            ->first();

        $mountainId = $activeHiker ? $activeHiker->mountain_id : 0;

        DB::table('mountain_sos_signals')->insert([
            'device_id'  => $validated['device_id'],
            'latitude'  => $validated['latitude'],
            'longitude'  => $validated['longitude'],
            'timestamp'  => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        event(new SosSignalCreated([
            'device_id'   => $validated['device_id'],
            'mountain_id' => $mountainId,
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'message'     => 'Butuh bantuan segera!',
        ]));

        return response()->json([
            'status'  => 'success',
            'message' => 'SOS triggered successfully.',
        ]);
    }
}
