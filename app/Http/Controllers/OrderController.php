<?php

namespace App\Http\Controllers;

use App\Models\RFID;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'userId' => 'required|string',
            'senderName' => 'required|string',
            'itemName' => 'required|string',
            'senderAddress' => 'required|string',
            'senderCity' => 'required|string',
            'senderPhone' => 'required|string',
            'receiverName' => 'required|string',
            'receiverAddress' => 'required|string',
            'receiverCity' => 'required|string',
            'receiverPhone' => 'required|string',
        ]);

        // List of cities in Jabodetabek
        $jabodetabek = ["Jakarta", "Bogor", "Depok", "Tangerang", "Bekasi"];

        // Generate a UUID
        $uuid = (string) Str::uuid();

        // Determine shipping cost based on the area
        $cost = (in_array($validatedData['senderCity'], $jabodetabek) && in_array($validatedData['receiverCity'], $jabodetabek)) ? 9000 : 20000;

        // Create a new order
        $order = Order::create(array_merge($validatedData, [
            'uuid' => $uuid,
            'cost' => $cost,
        ]));

        if ($order) {
            return response()->json(['status' => true, 'uuid' => $uuid, 'message' => 'Berhasil dikirim!']);
        } else {
            return response()->json(['status' => false, 'message' => 'Gagal mengirim']);
        }
    }

    public function getOrdersByUserId(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'userId' => 'required|string',
        ]);

        // Retrieve the userId from the request
        $userId = $request->input('userId');

        // Fetch orders associated with the userId
        $orders = Order::where('userId', $userId)->get();

        // Return the orders in JSON format
        return response()->json($orders);
    }

    public function getOrdersByShipper(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'uuid' => 'required|string',
        ]);

        // Retrieve the userId (shipper) from the request
        $shipperId = $request->input('uuid');

        // Fetch orders associated with the shipper
        $orders = Order::where('uuid', $shipperId)->first();

        // Return the orders in JSON format
        return response()->json($orders);
    }

    public function verifyKTP($ktpNumber): JsonResponse
    {
        // Mengambil data dari tabel ktp_verification
        $data = RFID::
            where('ktp_no', $ktpNumber)
            ->first();

        // Memeriksa apakah data ditemukan
        if ($data) {
            return response()->json(['verified' => true]);
        } else {
            return response()->json(['verified' => false]);
        }
    }

    public function updateStatus(Request $request, $uuid): JsonResponse
    {
        // Validasi input
        $validated = $request->validate([
            'shipper' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        // Mengupdate status
        $order = Order::where('uuid', $uuid)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->status = $validated['status'];
        $order->shipper = 'delivered';

        if ($order->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => 'Failed to update status'], 500);
        }
    }
}
