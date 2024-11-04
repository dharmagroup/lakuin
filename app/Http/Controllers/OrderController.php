<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;

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
            'userId' => 'required|string',
        ]);

        // Retrieve the userId (shipper) from the request
        $shipperId = $request->input('userId');

        // Fetch orders associated with the shipper
        $orders = Order::where('shipper', $shipperId)->get();

        // Return the orders in JSON format
        return response()->json($orders);
    }
}
