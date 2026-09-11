<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 via-slate-800 to-rose-950 p-6 md:p-8 rounded-3xl text-white shadow-xl shadow-slate-900/10">
        <div class="space-y-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Live Store Intelligence</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold font-heading">Welcome to Al Hayat Kids Admin</h1>
            <p class="text-slate-300 text-sm">Real-time overview of products, stock inventory, customers, and order activity.</p>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <span class="text-xs text-slate-400 block font-medium">System Status</span>
                <span class="text-xs font-bold text-emerald-400 flex items-center gap-1.5 justify-end">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    All Services Operational
                </span>
            </div>
        </div>
    </div>

    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Revenue</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-bold text-slate-900 font-heading">PKR {{ number_format($totalRevenue, 2) }}</h3>
                <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                    <span class="text-emerald-600 font-semibold">Confirmed & Paid</span> revenue
                </p>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Orders</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-bold text-slate-900 font-heading">{{ number_format($totalOrders) }}</h3>
                <div class="mt-1 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Pending action:</span>
                    <span class="font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60">{{ $pendingOrders }} Orders</span>
                </div>
            </div>
        </div>

        <!-- Products Catalog Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Products Catalog</span>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-bold text-slate-900 font-heading">{{ number_format($totalProducts) }}</h3>
                <div class="mt-1 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Active status:</span>
                    <span class="font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">{{ $activeProducts }} Active</span>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Customers</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <h3 class="text-2xl font-bold text-slate-900 font-heading">{{ number_format($totalCustomers) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Registered customer accounts</p>
            </div>
        </div>
    </div>

    <!-- Main Content Section: Low Stock & Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 font-heading">Recent Orders</h2>
                    <p class="text-xs text-slate-500">Latest customer orders placed on Al Hayat Kids.</p>
                </div>
                <span class="text-xs text-slate-400 font-medium">Live Feed</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3.5 px-6">Order No</th>
                            <th class="py-3.5 px-6">Customer</th>
                            <th class="py-3.5 px-6">Payment Method</th>
                            <th class="py-3.5 px-6">Total</th>
                            <th class="py-3.5 px-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    {{ $order->order_number }}
                                </td>
                                <td class="py-4 px-6">
                                    {{ $order->user->name ?? 'Guest Customer' }}
                                </td>
                                <td class="py-4 px-6 uppercase text-[11px] font-semibold text-slate-500">
                                    {{ $order->payment_method }}
                                </td>
                                <td class="py-4 px-6 font-bold text-slate-900">
                                    PKR {{ number_format($order->total, 2) }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider
                                        {{ $order->order_status === 'delivered' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                        {{ $order->order_status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                        {{ $order->order_status === 'processing' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                        {{ $order->order_status === 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}">
                                        {{ $order->order_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-slate-400">
                                    No recent orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Alert Sidebar (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between space-y-6">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 font-heading">Low Stock Alerts</h2>
                        <p class="text-xs text-slate-500">Garment variants needing restock.</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse($lowStockProducts as $variant)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <div class="space-y-0.5 overflow-hidden">
                                <h4 class="text-xs font-bold text-slate-900 truncate">{{ $variant->product->name ?? 'Product' }}</h4>
                                <p class="text-[11px] text-slate-500">
                                    Size: <span class="font-semibold text-slate-700">{{ $variant->size->name ?? 'N/A' }}</span> | 
                                    Color: <span class="font-semibold text-slate-700">{{ $variant->color->name ?? 'N/A' }}</span>
                                </p>
                                <span class="text-[10px] font-mono text-slate-400 block truncate">SKU: {{ $variant->sku }}</span>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                    {{ $variant->stock_quantity }} left
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            All garment variant stock levels are healthy!
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-orange-50 p-4 rounded-xl border border-amber-200/60">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <div>
                        <h4 class="text-xs font-bold text-amber-900 font-heading">Stock Threshold Notice</h4>
                        <p class="text-[11px] text-amber-700 mt-0.5">Inventory alert triggers automatically when stock falls below 15 units.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
