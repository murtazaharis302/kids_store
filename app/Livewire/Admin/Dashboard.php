<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', true)->count();
        
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        
        $totalCustomers = User::where('role', 'customer')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        
        $lowStockProducts = ProductVariant::with(['product', 'size', 'color'])
            ->where('stock_quantity', '<=', 15)
            ->orderBy('stock_quantity', 'asc')
            ->take(6)
            ->get();
            
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $topProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total) as total_revenue'))
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalProducts' => $totalProducts,
            'activeProducts' => $activeProducts,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'lowStockProducts' => $lowStockProducts,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
        ])->layout('components.layouts.admin', ['title' => 'Dashboard Overview']);
    }
}
