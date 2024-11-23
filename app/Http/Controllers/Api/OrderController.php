<?php
namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // Menampilkan semua pesanan
    public function index()
    {
        $orders = Order::with('user')->get();
        return response()->json([
            'status' => true,
            'message' => 'Orders retrieved successfully',
            'data' => $orders
        ]);
    }

    // Membuat pesanan baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validasi user
            'total_price' => 'required|numeric',
            'status' => 'required|string',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'total_price' => $request->total_price,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order created successfully',
            'data' => $order
        ]);
    }

    // Menampilkan detail pesanan berdasarkan ID
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        return response()->json([
            'status' => true,
            'message' => 'Order retrieved successfully',
            'data' => $order
        ]);
    }

    // Mengupdate status pesanan
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string', // Update status pesanan
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully',
            'data' => $order
        ]);
    }

    // Menghapus pesanan
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order deleted successfully',
            'data' => null
        ]);
    }
}
