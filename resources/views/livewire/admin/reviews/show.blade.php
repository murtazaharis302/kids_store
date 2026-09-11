<div class="space-y-6">
    <!-- Top Navigation & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-700 transition mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to All Reviews
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold text-slate-900 font-heading">Review #{{ $review->id }}</h1>
                @if($review->status)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        Published / Approved
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                        Pending Moderation
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">Submitted on {{ $review->created_at ? $review->created_at->format('F d, Y \a\t h:i A') : 'N/A' }}</p>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center gap-3">
            <button wire:click="toggleStatus" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sm transition shadow-xs {{ $review->status ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white' }}">
                @if($review->status)
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Unpublish / Reject
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Approve & Publish
                @endif
            </button>

            <button wire:click="confirmDelete" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl font-semibold text-sm transition border border-rose-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete Review
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Review Content & Product Card -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Review Content Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-bold text-slate-900 font-heading">Customer Feedback</h2>
                    <!-- Rating Stars -->
                    <div class="flex items-center gap-1.5 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'fill-amber-400 text-amber-400' : 'fill-slate-200 text-slate-200' }}" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.363 1.118l1.07 3.292c.3.921-.755 1.688-1.538 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.783.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="text-sm font-bold text-amber-800 ml-1">{{ $review->rating }} / 5</span>
                    </div>
                </div>

                <div class="prose prose-slate max-w-none">
                    <blockquote class="p-4 bg-slate-50 rounded-xl border-l-4 border-rose-500 text-slate-800 italic text-base leading-relaxed">
                        "{{ $review->comment }}"
                    </blockquote>
                </div>
            </div>

            <!-- Product Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Reviewed Product</h2>

                @if($review->product)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        @if($review->product->primaryImage && $review->product->primaryImage->image_path)
                            <img src="{{ asset('storage/' . $review->product->primaryImage->image_path) }}" 
                                 alt="{{ $review->product->name }}" 
                                 class="w-20 h-20 rounded-xl object-cover border border-slate-200 shrink-0">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 text-slate-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif

                        <div class="space-y-1 flex-1">
                            <h3 class="font-bold text-slate-900 text-base">{{ $review->product->name }}</h3>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                <span><strong>SKU:</strong> {{ $review->product->sku ?? 'N/A' }}</span>
                                <span><strong>Price:</strong> Rs. {{ number_format($review->product->price, 2) }}</span>
                                @if($review->product->sale_price)
                                    <span class="text-rose-600 font-semibold">Sale: Rs. {{ number_format($review->product->sale_price, 2) }}</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('admin.products.edit', $review->product) }}" 
                               class="inline-flex items-center gap-1 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-xs transition">
                                Edit Product
                            </a>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-xl text-slate-500 italic text-sm border border-slate-200/60">
                        Product no longer available in the store catalog.
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Customer Info & Order Link -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Customer Profile</h2>

                @if($review->user)
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-rose-400 to-pink-500 text-white font-bold text-base flex items-center justify-center shrink-0 shadow-xs">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="font-bold text-slate-900 text-base truncate">{{ $review->user->name }}</h3>
                            <p class="text-xs text-slate-500 truncate">{{ $review->user->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-2.5 pt-2 border-t border-slate-100 text-xs">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Phone:</span>
                            <span class="font-semibold text-slate-800">{{ $review->user->phone ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Role:</span>
                            <span class="font-semibold text-slate-800 uppercase tracking-wider text-[10px] bg-slate-100 px-2 py-0.5 rounded-md">{{ $review->user->role }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Account Status:</span>
                            <span class="font-semibold {{ $review->user->status ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $review->user->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    @if($review->user->role === 'customer')
                        <div class="pt-2">
                            <a href="{{ route('admin.customers.show', $review->user) }}" 
                               class="w-full inline-flex justify-center items-center gap-1 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-xs transition">
                                View Customer Profile
                            </a>
                        </div>
                    @endif
                @else
                    <div class="p-4 bg-slate-50 rounded-xl text-slate-500 italic text-sm border border-slate-200/60">
                        Customer Record Unavailable.
                    </div>
                @endif
            </div>

            <!-- Order Information Card -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
                <h2 class="text-lg font-bold text-slate-900 font-heading border-b border-slate-100 pb-3">Associated Order</h2>

                @if($review->order)
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Order Number:</span>
                            <a href="{{ route('admin.orders.show', $review->order) }}" class="font-bold text-rose-600 hover:underline text-sm font-heading">
                                {{ $review->order->order_number }}
                            </a>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Total Amount:</span>
                            <span class="font-bold text-slate-900">Rs. {{ number_format($review->order->total, 2) }}</span>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Order Status:</span>
                            <span class="font-semibold text-slate-800 capitalize">{{ $review->order->order_status }}</span>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Payment Status:</span>
                            <span class="font-semibold capitalize {{ $review->order->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $review->order->payment_status }}
                            </span>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('admin.orders.show', $review->order) }}" 
                               class="w-full inline-flex justify-center items-center gap-1 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-xs transition">
                                View Order Details
                            </a>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 rounded-xl text-slate-500 italic text-xs border border-slate-200/60">
                        No associated order (Direct Product Review).
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingReviewDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="cancelDelete"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 sm:mx-0 sm:h-10 sm:w-10 text-rose-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-bold text-slate-900 font-heading" id="modal-title">Delete Review #{{ $review->id }}</h3>
                                <div class="mt-2 space-y-2 text-sm text-slate-500">
                                    <p>Are you sure you want to permanently delete this customer review?</p>
                                    <p class="text-xs bg-amber-50 border border-amber-200 text-amber-800 p-2.5 rounded-lg font-medium">
                                        <strong>Safety Note:</strong> Deleting this review will only remove the review record itself. Associated Customer, Product, and Order records will remain completely intact.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                        <button type="button" 
                                wire:click="deleteReview" 
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-xs px-4 py-2.5 bg-rose-600 text-base font-semibold text-white hover:bg-rose-700 focus:outline-none sm:w-auto sm:text-sm transition">
                            Permanently Delete Review
                        </button>
                        <button type="button" 
                                wire:click="cancelDelete" 
                                class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-xs px-4 py-2.5 bg-white text-base font-semibold text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
