<x-filament-panels::page>

{{-- Sonidos --}}
<audio id="snd-bienvenida" preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
</audio>
<audio id="snd-vencida" preload="auto">
    <source src="https://assets.mixkit.co/active_storage/sfx/2955/2955-preview.mp3" type="audio/mpeg">
</audio>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
.asistencia-card{border-radius:16px;border:1px solid #e5e7eb;background:#fff;overflow:hidden;}
.qr-input{width:100%;padding:16px 20px;font-size:18px;border:2px solid #f59e0b;border-radius:12px;outline:none;text-align:center;letter-spacing:0.05em;color:#111827;background:#fffbeb;}
.qr-input:focus{border-color:#d97706;box-shadow:0 0 0 3px rgba(245,158,11,0.15);}
.estado-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 14px;border-radius:20px;font-size:13px;font-weight:600;}
.badge-activa{background:#dcfce7;color:#16a34a;}
.badge-vencida{background:#fee2e2;color:#dc2626;}
.badge-sin{background:#fef3c7;color:#d97706;}
</style>

<div style="max-width:600px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">

    {{-- Buscador QR --}}
    <div class="asistencia-card">
        <div style="background:#f59e0b;padding:14px 20px;">
            <p style="margin:0;font-size:16px;font-weight:700;color:#fff;">Escanea el QR o ingresa el folio</p>
            <p style="margin:4px 0 0;font-size:13px;color:#fef3c7;">El lector de QR enviará el código automáticamente</p>
        </div>
        <div style="padding:20px;">
            <form wire:submit="buscarSocio">
                <input
                    type="text"
                    wire:model="qrInput"
                    class="qr-input"
                    placeholder="Escanea el QR o escribe el folio..."
                    id="qr-input-field"
                    autofocus
                    autocomplete="off"
                />
                <div style="display:flex;gap:10px;margin-top:12px;">
                    <button type="submit"
                        style="flex:1;padding:12px;border-radius:10px;background:#f59e0b;color:#fff;border:none;font-size:15px;font-weight:600;cursor:pointer;">
                        Verificar
                    </button>
                    <button type="button" onclick="document.getElementById('qr-input-field').focus()"
                        style="padding:12px 16px;border-radius:10px;background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;font-size:13px;cursor:pointer;">
                        Enfocar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Último registro --}}
    @if($resultado)
    <div class="asistencia-card">
        <div style="padding:16px 20px;border-bottom:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;">
            <p style="margin:0;font-size:14px;font-weight:600;color:#374151;">Último registro</p>
            <span class="estado-badge {{ $estado === 'activa' ? 'badge-activa' : ($estado === 'vencida' ? 'badge-vencida' : 'badge-sin') }}">
                {{ $estado === 'activa' ? '✓ Activa' : ($estado === 'vencida' ? '✗ Vencida' : '⚠ Sin membresía') }}
            </span>
        </div>
        <div style="padding:16px 20px;display:flex;align-items:center;gap:16px;">
            @if(!empty($resultado['foto']))
            <div style="width:70px;height:70px;border-radius:50%;overflow:hidden;border:3px solid {{ $estado === 'activa' ? '#16a34a' : '#dc2626' }};flex-shrink:0;">
                <img src="{{ $resultado['foto'] }}" style="width:100%;height:100%;object-fit:cover;"/>
            </div>
            @else
            <div style="width:70px;height:70px;border-radius:50%;background:#f3f4f6;border:3px solid #d1d5db;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <span style="font-size:24px;color:#9ca3af;">?</span>
            </div>
            @endif
            <div>
                <p style="margin:0;font-size:18px;font-weight:700;color:#111827;">{{ $resultado['nombre'] ?? '' }}</p>
                @if(!empty($resultado['plan']))
                <p style="margin:4px 0 0;font-size:13px;color:#6b7280;">Plan: {{ $resultado['plan'] }}</p>
                @endif
                @if(!empty($resultado['dias']))
                <p style="margin:4px 0 0;font-size:13px;color:#16a34a;font-weight:500;">{{ $resultado['dias'] }} días restantes · Vence {{ $resultado['vence'] }}</p>
                @endif
                @if(!empty($resultado['vencio']))
                <p style="margin:4px 0 0;font-size:13px;color:#dc2626;font-weight:500;">Venció el {{ $resultado['vencio'] }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- Historial del día --}}
    
        <div class="asistencia-card">
            <div style="padding:12px 20px;border-bottom:1px solid #f3f4f6;">
                <p style="margin:0;font-size:13px;font-weight:600;color:#374151;">Asistencias hoy — {{ now()->format('d/m/Y') }}</p>
            </div>
            <div style="padding:0;">
                @forelse($historialHoy as $asistencia)
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-bottom:1px solid #f9fafb;">
                    <div>
                        <p style="margin:0;font-size:13px;font-weight:500;color:#111827;">{{ $asistencia['nombre'] }}</p>
                        <p style="margin:0;font-size:11px;color:#9ca3af;">{{ $asistencia['hora'] }}</p>
                    </div>
                    <span class="estado-badge {{ $asistencia['estado'] === 'activa' ? 'badge-activa' : 'badge-vencida' }}" style="font-size:11px;">
                        {{ $asistencia['estado'] === 'activa' ? '✓' : '✗' }}
                    </span>
                </div>
                @empty
                <p style="padding:20px;text-align:center;color:#9ca3af;font-size:13px;margin:0;">Sin registros hoy</p>
                @endforelse
            </div>
        </div>

</div>

<script>
window.addEventListener('alerta-asistencia', event => {
    const { estado, datos } = event.detail[0] ?? event.detail;

    if (estado === 'activa') {
        document.getElementById('snd-bienvenida')?.play().catch(()=>{});

        Swal.fire({
            title: '¡Bienvenido!',
            html: `
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                    ${datos.foto ? `<div style="width:100px;height:100px;border-radius:50%;overflow:hidden;border:4px solid #16a34a;">
                        <img src="${datos.foto}" style="width:100%;height:100%;object-fit:cover;"/>
                    </div>` : ''}
                    <p style="font-size:20px;font-weight:700;color:#111827;margin:0;">${datos.nombre}</p>
                    <span style="background:#dcfce7;color:#16a34a;padding:4px 16px;border-radius:20px;font-size:14px;font-weight:600;">
                        Plan ${datos.plan}
                    </span>
                    <p style="color:#16a34a;font-size:14px;margin:0;">
                        ${datos.dias} días restantes · Vence ${datos.vence}
                    </p>
                </div>
            `,
            icon: 'success',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#f0fdf4',
        }).then(() => {
            document.getElementById('qr-input-field')?.focus();
        });

    } else if (estado === 'vencida') {
        document.getElementById('snd-vencida')?.play().catch(()=>{});

        Swal.fire({
            title: 'MEMBRESÍA VENCIDA',
            html: `
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                    ${datos.foto ? `<div style="width:100px;height:100px;border-radius:50%;overflow:hidden;border:4px solid #dc2626;">
                        <img src="${datos.foto}" style="width:100%;height:100%;object-fit:cover;"/>
                    </div>` : ''}
                    <p style="font-size:20px;font-weight:700;color:#111827;margin:0;">${datos.nombre}</p>
                    <span style="background:#fee2e2;color:#dc2626;padding:4px 16px;border-radius:20px;font-size:14px;font-weight:600;">
                        Venció el ${datos.vencio}
                    </span>
                    <p style="color:#dc2626;font-size:14px;margin:0;font-weight:600;">
                        Por favor renueva tu membresía en recepción
                    </p>
                </div>
            `,
            icon: 'error',
            timer: 6000,
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#dc2626',
            background: '#fef2f2',
        }).then(() => {
            document.getElementById('qr-input-field')?.focus();
        });

    } else if (estado === 'sin_membresia') {
        document.getElementById('snd-vencida')?.play().catch(()=>{});

        Swal.fire({
            title: 'SIN MEMBRESÍA',
            html: `
                <div style="display:flex;flex-direction:column;align-items:center;gap:12px;">
                    ${datos.foto ? `<div style="width:100px;height:100px;border-radius:50%;overflow:hidden;border:4px solid #d97706;">
                        <img src="${datos.foto}" style="width:100%;height:100%;object-fit:cover;"/>
                    </div>` : ''}
                    <p style="font-size:20px;font-weight:700;color:#111827;margin:0;">${datos.nombre}</p>
                    <p style="color:#d97706;font-size:14px;margin:0;">Este socio no tiene membresía activa</p>
                </div>
            `,
            icon: 'warning',
            timer: 5000,
            timerProgressBar: true,
            showConfirmButton: true,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#d97706',
            background: '#fffbeb',
        }).then(() => {
            document.getElementById('qr-input-field')?.focus();
        });

    } else {
        Swal.fire({
            title: 'No encontrado',
            text: 'QR o folio no reconocido',
            icon: 'error',
            timer: 3000,
            showConfirmButton: false,
        }).then(() => {
            document.getElementById('qr-input-field')?.focus();
        });
    }
});

// Mantener el foco en el input siempre
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('qr-input-field')?.focus();
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.swal2-container')) {
        setTimeout(() => document.getElementById('qr-input-field')?.focus(), 100);
    }
});
</script>
</x-filament-panels::page>