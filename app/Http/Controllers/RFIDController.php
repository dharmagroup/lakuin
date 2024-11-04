<?php

namespace App\Http\Controllers;

use App\Models\RFID;
use Illuminate\Http\Request;

class RFIDController extends Controller
{
    public function getUserById(Request $request)
    {
        // Validate the input
        $request->validate([
            'userId' => 'required|string',
        ]);

        // Retrieve userId from request
        $userId = $request->input('userId');

        // Fetch data from the RFID model
        $rfid = RFID::where('userId', $userId)->first();

        if ($rfid) {
            return response()->json([
                'status' => true,
                'ktp_no' => $rfid->ktp_no,
                'userId' => $rfid->userId,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'ktp_no' => null,
                'userId' => $userId,
            ]);
        }
    }

    public function registerOrUpdate(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'userId' => 'required|string',
            'ktp_no' => 'required|string',
        ]);

        // Check if userId already exists
        $rfid = RFID::where('userId', $validatedData['userId'])->first();

        if ($rfid) {
            // If userId exists, update ktp_no
            $rfid->ktp_no = $validatedData['ktp_no'];

            if ($rfid->save()) {
                return response()->json(['status' => true, 'message' => 'Data berhasil diperbarui']);
            } else {
                return response()->json(['status' => false, 'message' => 'Gagal memperbarui data']);
            }
        } else {
            // If userId does not exist, create a new record
            $newRfid = RFID::create([
                'userId' => $validatedData['userId'],
                'ktp_no' => $validatedData['ktp_no'],
            ]);

            if ($newRfid) {
                return response()->json(['status' => true, 'message' => 'Data berhasil disimpan']);
            } else {
                return response()->json(['status' => false, 'message' => 'Gagal menyimpan data']);
            }
        }
    }
}
