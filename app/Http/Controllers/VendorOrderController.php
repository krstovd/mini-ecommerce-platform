<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $orderItems = OrderItem::with(['order.user', 'product'])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->get();

        return view('vendor.orders.index', compact('orderItems'));
    }

    public function updateStatus(Request $request, OrderItem $orderItem)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor || $orderItem->vendor_id !== $vendor->id) {
            abort(403);
        }

        $request->validate([
            'status' => ['required', 'in:pending,paid,shipped,delivered'],
        ]);

        $current = $orderItem->status;
        $next = $request->status;

        $allowedTransitions = [
            'pending' => ['paid', 'shipped', 'delivered'],
            'paid' => ['shipped', 'delivered'],
            'shipped' => ['delivered'],
            'delivered' => [],
        ];

        if (!in_array($next, $allowedTransitions[$current] ?? [], true) && $next !== $current) {
            return back()->with('error', 'Invalid status transition.');
        }

        $orderItem->update([
            'status' => $next,
        ]);

        return back()->with('success', 'Order item status updated.');
    }
}
