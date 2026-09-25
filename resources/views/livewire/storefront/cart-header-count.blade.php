<a href="{{ route('cart.index') }}" 
   @click.prevent="$dispatch('open-cart-drawer')"
   class="relative flex items-center justify-center w-10 h-10 rounded-2xl bg-white/80 border border-slate-200 text-slate-700 hover:text-rose-600 hover:border-rose-300 transition shadow-2xs group cursor-pointer"
   title="Shopping Cart">
    <svg class="w-5 h-5 text-slate-700 group-hover:text-rose-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
    </svg>
    @if($count > 0)
        <span class="absolute -top-1.5 -right-1.5 flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-rose-600 text-white font-extrabold text-[10px] shadow-sm animate-pulse">
            {{ $count }}
        </span>
    @endif
</a>
