<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    
    <!-- Success Banner -->
    <div class="bg-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-md border border-slate-800 relative overflow-hidden">
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
                    We've received your order <span class="font-bold text-rose-300">#{{ $order->order_number }}</span>.
                    @if(in_array($order->payment_method, ['jazzcash', 'easypaisa', 'bank_transfer']) && $order->payment_status !== 'paid')
                        Please complete your payment transfer below using any of our receiving accounts.
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Flash Message Notification -->
    @if($flashMessage)
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $flashMessage }}</span>
            </div>
            <button type="button" wire:click="$set('flashMessage', '')" class="opacity-60 hover:opacity-100">&times;</button>
        </div>
    @endif

    <!-- ADVANCE PAYMENT TRANSFER PORTAL (For Askari Bank, EasyPaisa, JazzCash) -->
    @if(in_array($order->payment_method, ['jazzcash', 'easypaisa', 'bank_transfer']) && isset($paymentMethods[$order->payment_method]))
        @php $methodInfo = $paymentMethods[$order->payment_method]; @endphp
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 space-y-6 shadow-sm" x-data="{ copyText(text) { navigator.clipboard.writeText(text); alert('Copied to clipboard: ' + text); } }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-3">
                <div>
                    <span class="text-xs font-bold text-rose-600 uppercase font-mono tracking-wider">STEP 2 OF 2: COMPLETE PAYMENT</span>
                    <h2 class="text-xl font-extrabold text-slate-900 font-heading">
                        Pay via {{ $methodInfo['name'] }}
                    </h2>
                </div>
                <div class="bg-slate-900 text-white px-4 py-2 rounded-2xl text-right shrink-0">
                    <span class="text-[10px] text-slate-400 font-bold uppercase block">Total Amount to Pay</span>
                    <span class="text-lg font-extrabold text-emerald-400 font-heading">Rs. {{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Official Receiving Accounts Display Card -->
            <div class="p-6 rounded-2xl bg-slate-900 border border-slate-800 text-white space-y-5 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-xs font-bold text-slate-200 uppercase tracking-wider font-mono">
                            OFFICIAL STORE RECEIVING ACCOUNTS
                        </span>
                    </div>
                    <span class="text-[10px] font-extrabold bg-emerald-500 text-white px-2.5 py-0.5 rounded-full uppercase">
                        0% Transfer Fee
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- 1. Askari Commercial Bank Card -->
                    <div class="p-4 rounded-xl bg-slate-800/90 border border-slate-700/80 space-y-2 relative group hover:border-emerald-500/50 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold text-amber-400 uppercase tracking-wider font-mono">Bank Account</span>
                            <span class="text-[10px] font-bold bg-slate-700 text-slate-300 px-2 py-0.5 rounded">Askari Bank</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Bank Name</span>
                            <span class="text-xs font-extrabold text-white block">Askari Commercial Bank</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Account Title</span>
                            <span class="text-xs font-extrabold text-emerald-300 block">Al hayat Garments</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Account Number</span>
                            <div class="flex items-center justify-between gap-1 pt-0.5">
                                <span class="text-xs font-extrabold font-mono text-white">09450200002583</span>
                                <button type="button" @click="copyText('09450200002583')" class="text-[10px] bg-emerald-600 hover:bg-emerald-500 text-white px-2.5 py-1 rounded-md font-bold transition">
                                    Copy
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 2. EasyPaisa Card -->
                    <div class="p-4 rounded-xl bg-slate-800/90 border border-slate-700/80 space-y-2 relative group hover:border-emerald-500/50 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-wider font-mono">EasyPaisa Wallet</span>
                            <span class="text-[10px] font-bold bg-emerald-900/60 text-emerald-300 px-2 py-0.5 rounded">EasyPaisa</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Account Title</span>
                            <span class="text-xs font-extrabold text-emerald-300 block">Khizer hayat</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Mobile / Account No</span>
                            <div class="flex items-center justify-between gap-1 pt-0.5">
                                <span class="text-xs font-extrabold font-mono text-white">03150132001</span>
                                <button type="button" @click="copyText('03150132001')" class="text-[10px] bg-emerald-600 hover:bg-emerald-500 text-white px-2.5 py-1 rounded-md font-bold transition">
                                    Copy
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 3. JazzCash Card -->
                    <div class="p-4 rounded-xl bg-slate-800/90 border border-slate-700/80 space-y-2 relative group hover:border-emerald-500/50 transition">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold text-rose-400 uppercase tracking-wider font-mono">JazzCash Wallet</span>
                            <span class="text-[10px] font-bold bg-rose-950 text-rose-300 px-2 py-0.5 rounded">JazzCash</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Account Title</span>
                            <span class="text-xs font-extrabold text-emerald-300 block">Khizer hayat</span>
                        </div>
                        <div>
                            <span class="text-[10px] text-slate-400 block font-medium">Mobile / Account No</span>
                            <div class="flex items-center justify-between gap-1 pt-0.5">
                                <span class="text-xs font-extrabold font-mono text-white">03249171213</span>
                                <button type="button" @click="copyText('03249171213')" class="text-[10px] bg-emerald-600 hover:bg-emerald-500 text-white px-2.5 py-1 rounded-md font-bold transition">
                                    Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-xs text-slate-300 border-t border-slate-800 pt-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
                    <p class="leading-relaxed">
                        💡 <strong>Instructions:</strong> Please transfer <strong>Rs. {{ number_format($order->total, 2) }}</strong> to any of the official account details above via your Banking, EasyPaisa, or JazzCash app. Once sent, paste your TRX ID / Receipt screenshot below!
                    </p>
                </div>
            </div>

            <!-- TRX ID & Screenshot Upload Form -->
            <form wire:submit.prevent="submitPaymentReference" class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-200 pb-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 font-heading uppercase tracking-wider">
                            Submit Payment Proof / Receipt
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Upload your payment screenshot or enter your TRX ID below</p>
                    </div>
                    
                    <!-- Direct WhatsApp Option -->
                    <a href="https://api.whatsapp.com/send?phone=923249171213&text={{ urlencode('Hi Al Hayat Kids, I have transferred payment of Rs. ' . number_format($order->total, 2) . ' for Order #' . $order->order_number . '. Here is my receipt screenshot.') }}" 
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-xs transition w-fit">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.705 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Send Receipt on WhatsApp</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Upload Screenshot Image File -->
                    <div class="space-y-1 sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-800">
                            Upload Payment Receipt / Screenshot Image (PNG, JPG)
                        </label>
                        <input type="file" 
                               wire:model="payment_proof_file"
                               accept="image/*"
                               class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-slate-900 file:text-white hover:file:bg-rose-600 file:cursor-pointer transition">
                        @error('payment_proof_file') 
                            <span class="text-[11px] font-bold text-rose-600 block mt-1">{{ $message }}</span> 
                        @enderror

                        <!-- Preview Image if newly uploaded or existing -->
                        @if($payment_proof_file)
                            <div class="mt-2 p-2 rounded-xl bg-white border border-slate-200 w-fit">
                                <span class="text-[10px] font-bold text-slate-400 block mb-1">New Image Preview:</span>
                                <img src="{{ $payment_proof_file->temporaryUrl() }}" alt="Receipt Preview" class="h-28 rounded-lg object-contain">
                            </div>
                        @elseif($order->payment && $order->payment->payment_proof_image && file_exists(storage_path('app/public/' . $order->payment->payment_proof_image)))
                            <div class="mt-2 p-2 rounded-xl bg-white border border-slate-200 w-fit">
                                <span class="text-[10px] font-bold text-emerald-600 block mb-1">✓ Currently Uploaded Receipt:</span>
                                <img src="{{ route('orders.receipt-image', $order->id) }}" alt="Current Receipt" class="h-28 rounded-lg object-contain">
                            </div>
                        @endif
                    </div>

                    <!-- Transaction TRX ID -->
                    <div class="space-y-1 sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-800">
                            Transaction Reference / TRX ID
                        </label>
                        <input type="text" 
                               wire:model="reference_number"
                               placeholder="e.g. 012345678987" 
                               class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-mono font-extrabold focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                        @error('reference_number') 
                            <span class="text-[11px] font-bold text-rose-600 block mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Sender Name -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-800">
                            Sender Account Name (Optional)
                        </label>
                        <input type="text" 
                               wire:model="sender_name"
                               placeholder="Your Account Holder Name" 
                               class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    </div>

                    <!-- Payment Notes -->
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-800">
                            Payment Notes (Optional)
                        </label>
                        <input type="text" 
                               wire:model="payment_notes"
                               placeholder="Additional note..." 
                               class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full sm:w-auto px-6 py-3 bg-rose-600 hover:bg-rose-700 disabled:opacity-50 text-white font-extrabold text-xs rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <span wire:loading.remove>Submit Payment Confirmation</span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Uploading & Submitting...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

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
                                @if($item->product && $item->product->primaryImage && !empty($item->product->primaryImage->url))
                                    <img src="{{ $item->product->primaryImage->url }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
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
