@props(['title' => 'Nothing here yet', 'description' => '', 'actionText' => '', 'actionUrl' => ''])

<div style="padding:64px 24px;text-align:center;background:#F7F4EF;border:1px solid #E0DBD3;">
    <div style="width:56px;height:56px;border:1px solid #E0DBD3;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;color:#C9A96E;">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    </div>
    <h3 style="font-family:'Cormorant Garamond',serif;font-size:22px;font-weight:600;color:#0D0D0D;margin-bottom:8px;">{{ $title }}</h3>
    @if($description)
        <p style="font-size:13px;color:#888;line-height:1.7;max-width:360px;margin:0 auto 24px;">{{ $description }}</p>
    @endif
    @if($actionText && $actionUrl)
        <a href="{{ $actionUrl }}"
           style="display:inline-flex;align-items:center;padding:12px 28px;background:#0D0D0D;color:#FFFFFF;font-size:11px;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;text-decoration:none;border:1px solid #0D0D0D;transition:background 0.2s;"
           onmouseover="this.style.background='transparent';this.style.color='#0D0D0D'"
           onmouseout="this.style.background='#0D0D0D';this.style.color='#FFFFFF'">
            {{ $actionText }}
        </a>
    @endif
</div>
