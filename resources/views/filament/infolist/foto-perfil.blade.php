@php
    $path = storage_path('app/private/' . $getState());
    $src  = '';
    if ($getState() && file_exists($path)) {
        $base64 = base64_encode(file_get_contents($path));
        $ext    = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime   = in_array($ext, ['jpg','jpeg']) ? 'image/jpeg' : 'image/png';
        $src    = "data:{$mime};base64,{$base64}";
    }
@endphp

@if($src)
<div style="display:flex;align-items:center;gap:16px;padding:8px 0;">
    <div style="width:100px;height:100px;border-radius:50%;overflow:hidden;border:3px solid #f59e0b;flex-shrink:0;">
        <img src="{{ $src }}" style="width:100%;height:100%;object-fit:cover;" alt="Foto del socio"/>
    </div>
</div>
@else
<div style="width:100px;height:100px;border-radius:50%;background:#f3f4f6;border:3px dashed #d1d5db;display:flex;align-items:center;justify-content:center;">
    <svg style="width:40px;height:40px;color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
</div>
@endif