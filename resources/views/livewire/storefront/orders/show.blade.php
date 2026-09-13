<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Success Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-rose-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5 text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-bold text-2xl shrink-0 shadow-lg shadow-emerald-500/20">
                ✓
            </div>
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-1">
                    Order Placed Successfully
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold font-heading text-white">
                    Thank You for Your Order!
                </h1>
                <p class="text-slate-300 text-xs sm:text-sm font-medium">
                    We've received your order <span class="font-bold text-rose-300">#{{ $order->order_number }}</span> and are processing it for shipping.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Order Details Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden divide-y divide-slate-100">
        
        <!-- Order Header Meta -->
        <div class="p-6 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Order Reference</span>
                <h2 class="text-xl font-extrabold text-slate-900 font-heading">#{{ $order->order_number }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="text-right">
                    <span class="text-[11px] font-bold text-slate-500 uppercase block">Order Status</span>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase {{ $order->order_status === 'completed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-[11px] font-bold text-slate-500 uppercase block">Payment Status</span>
                    @if($order->payment_status === 'paid')
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-500 text-white shadow-xs uppercase">
                            ✓ Paid & Verified
                        </span>
                    @elseif($order->payment_status === 'pending_verification')
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-amber-400 text-amber-950 border border-amber-500 uppercase">
                            ⏳ Verification Pending
                        </span>
                    @else
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200 uppercase">
                            {{ ucfirst($order->payment_status) }} ({{ strtoupper($order->payment_method) }})
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="p-6 space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 font-heading uppercase tracking-wider">
                Order Line Items
            </h3>

            <div class="divide-y divide-slate-100">
                @foreach($order->items as $item)
                    <div class="py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 shrink-0 flex items-center justify-center overflow-hidden">
                                @if($item->product && $item->product->primaryImage)
                                    <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-[10px] font-bold text-slate-400">AH Kids</span>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-extrabold text-slate-900 text-sm font-heading">{{ $item->product_name }}</h4>
                                <div class="flex flex-wrap items-center gap-2 mt-1 text-xs text-slate-500">
                                    @if($item->sku)
                                        <span class="bg-slate-100 px-1.5 py-0.5 rounded font-mono text-[11px]">SKU: {{ $item->sku }}</span>
                                    @endif
                                    @if($item->size)
                                        <span class="bg-rose-50 text-rose-700 px-1.5 py-0.5 rounded font-semibold text-[11px]">Size: {{ $item->size }}</span>
                                    @endif
                                    @if($item->color)
                                        <span class="bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-semibold text-[11px]">Color: {{ $item->color }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 mt-1">
                                    Rs. {{ number_format($item->unit_price, 2) }} &times; {{ $item->quantity }}
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="font-extrabold text-slate-900 text-sm font-heading">
                                Rs. {{ number_format($item->total, 2) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Totals Summary & Address -->
        <div class="p-6 bg-slate-50/50 grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
            
            <!-- Customer Shipping Info & Payment Reference Card -->
            <div class="space-y-4">
                <div class="space-y-2">
                    <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider font-heading">
                        Delivery Address
                    </h3>
                    @if($shippingAddress)
                        <div class="text-xs text-slate-600 space-y-1 font-medium bg-white p-4 rounded-2xl border border-slate-200/80">
                            <p class="font-bold text-slate-900 text-sm">{{ $shippingAddress->first_name }} {{ $shippingAddress->last_name }}</p>
                            <p>{{ $shippingAddress->address_line_1 }}</p>
                            @if($shippingAddress->address_line_2)<p>{{ $shippingAddress->address_line_2 }}</p>@endif
                            <p>{{ $shippingAddress->city }}{{ $shippingAddress->state ? ', ' . $shippingAddress->state : '' }} {{ $shippingAddress->postal_code }}</p>
                            <p class="font-mono pt-1 text-slate-500">Phone: {{ $shippingAddress->phone }}</p>
                        </div>
                    @else
                        <p class="text-xs text-slate-500 font-medium">Standard Home Delivery across Pakistan</p>
                    @endif
                </div>

                <!-- Payment Reference Record -->
                @if($order->payment)
                    <div class="p-4 rounded-2xl bg-white border border-slate-200/80 space-y-2 text-xs">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="font-extrabold text-slate-900 uppercase font-heading text-[11px]">Payment Gateway Reference</span>
                            <span class="font-mono font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[11px] uppercase">
                                {{ strtoupper($order->payment->method) }}
                            </span>
                        </div>

                        @if($order->payment->reference_number)
                            <div class="flex justify-between text-slate-600">
                                <span>TRX ID / Reference:</span>
                                <span class="font-mono font-extrabold text-slate-900">{{ $order->payment->reference_number }}</span>
                            </div>
                        @endif

                        @if($order->payment->sender_name)
                            <div class="flex justify-between text-slate-600">
                                <span>Sender Name:</span>
                                <span class="font-bold text-slate-900">{{ $order->payment->sender_name }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-slate-600">
                            <span>Payment Status:</span>
                            <span class="font-extrabold uppercase {{ $order->payment->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $order->payment->status === 'paid' ? 'Verified & Paid' : 'Verification Pending' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Price Breakdown -->
            <div class="space-y-3 bg-white p-4 rounded-2xl border border-slate-200/80 text-xs">
                <h3 class="text-xs font-extrabold text-slate-900 uppercase tracking-wider font-heading border-b border-slate-100 pb-2">
                    Payment Breakdown
                </h3>
                <div class="flex justify-between text-slate-600">
                    <span>Subtotal</span>
                    <span class="font-bold text-slate-900 font-heading">Rs. {{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-600 font-bold">
                        <span>Discount</span>
                        <span>- Rs. {{ number_format($order->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-slate-600">
                    <span>Shipping Fee</span>
                    <span class="font-bold text-slate-900 font-heading">Rs. {{ number_format($order->shipping_cost, 2) }}</span>
                </div>
                <div class="border-t border-slate-100 pt-2 flex justify-between items-baseline font-extrabold text-sm text-slate-900">
                    <span>Total Paid/Due</span>
                    <span class="text-rose-600 text-base font-heading">Rs. {{ number_format($order->total, 2) }}</span>
                </div>
            </div>

        </div>

    </div>

    <!-- Continue Shopping / Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('shop') }}" class="text-xs font-bold text-rose-600 hover:text-rose-700 transition flex items-center gap-1">
            &larr; Return to Storefront
        </a>
        <a href="{{ route('home') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-extrabold text-xs rounded-2xl transition shadow-xs">
            Back to Home
        </a>
    </div>

</div>
