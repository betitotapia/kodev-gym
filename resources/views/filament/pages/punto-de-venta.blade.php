<x-filament-panels::page>
<style>
.pdv-card{border-radius:12px;border:1px solid #e5e7eb;background:#fff;}
.dark .pdv-card{border-color:rgba(255,255,255,0.1);background:#1f2937;}
.pdv-label{font-size:11px;font-weight:700;letter-spacing:0.07em;text-transform:uppercase;color:#6b7280;padding:11px 16px;border-bottom:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;}
.dark .pdv-label{color:#9ca3af;border-color:rgba(255,255,255,0.07);}
.pdv-input{display:block;width:100%;border:1.5px solid #d1d5db;background:#fff;border-radius:8px;padding:10px 13px;font-size:14px;color:#111827;outline:none;transition:border-color .15s;box-sizing:border-box;}
.pdv-input:focus{border-color:#f59e0b;}
.dark .pdv-input{border-color:rgba(255,255,255,0.15);background:#374151;color:#f9fafb;}
.pdv-row{display:flex;justify-content:space-between;align-items:center;font-size:14px;}
.metodo-btn{flex:1;padding:10px 6px;border-radius:8px;font-size:13px;font-weight:500;border:1.5px solid #d1d5db;color:#374151;background:#fff;cursor:pointer;transition:all .15s;text-align:center;}
.dark .metodo-btn{border-color:rgba(255,255,255,0.15);color:#d1d5db;background:transparent;}
.metodo-btn.sel-efectivo{background:#16a34a;border-color:#16a34a;color:#fff!important;}
.metodo-btn.sel-transferencia{background:#2563eb;border-color:#2563eb;color:#fff!important;}
.metodo-btn.sel-tarjeta{background:#7c3aed;border-color:#7c3aed;color:#fff!important;}
.qty-btn{width:28px;height:28px;border-radius:50%;border:1.5px solid #d1d5db;background:#fff;color:#374151;font-size:16px;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:background .1s;}
.dark .qty-btn{border-color:rgba(255,255,255,0.15);background:#374151;color:#f9fafb;}
.qty-btn:hover{background:#f9fafb;}
.cobrar-btn{width:100%;padding:14px;border-radius:12px;font-size:15px;font-weight:700;color:#fff;border:none;cursor:pointer;background:#f59e0b;transition:all .15s;}
.cobrar-btn:hover:not([disabled]){background:#d97706;}
.cobrar-btn[disabled]{background:#d1d5db;color:#9ca3af;cursor:not-allowed;}
.ticket-btn{display:block;width:100%;padding:12px;border-radius:12px;font-size:14px;font-weight:500;cursor:pointer;background:#f3f4f6;color:#374151;border:1.5px solid #d1d5db;text-align:center;box-sizing:border-box;}
.ticket-btn:hover{background:#e5e7eb;}
.dark .ticket-btn{background:#374151;color:#f9fafb;border-color:rgba(255,255,255,0.15);}
.cambio-verde{background:#f0fdf4;border:2px solid #86efac;border-radius:10px;padding:12px 16px;display:flex;justify-content:space-between;align-items:center;}
.cambio-rojo{background:#fef2f2;border:2px solid #fca5a5;border-radius:10px;padding:12px 16px;display:flex;justify-content:space-between;align-items:center;}
.total-band{padding:14px 16px;background:#fafafa;border-top:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;border-radius:0 0 12px 12px;}
.dark .total-band{background:#111827;border-color:rgba(255,255,255,0.07);}
.drop-box{position:absolute;z-index:9999;left:14px;right:14px;top:calc(100% - 2px);background:#fff;border-radius:0 0 10px 10px;border:1.5px solid #d1d5db;border-top:none;overflow:hidden;box-shadow:0 12px 30px rgba(0,0,0,0.15);}
.drop-item{width:100%;display:flex;align-items:center;justify-content:space-between;padding:11px 16px;font-size:14px;border:none;background:#fff;cursor:pointer;text-align:left;border-bottom:1px solid #f3f4f6;color:#111827;}
.drop-item:hover{background:#fffbeb;}
.drop-item:last-child{border-bottom:none;}
@media print{body *{visibility:hidden!important;}#ticket-print,#ticket-print *{visibility:visible!important;}#ticket-print{position:fixed!important;left:0;top:0;width:80mm;font-size:11px;color:#000;background:#fff;}}
</style>

{{-- TICKET --}}
<div id="ticket-print" style="display:none;font-family:monospace;width:80mm;padding:8px;font-size:11px;color:#000;background:#fff;">
    <div style="text-align:center;margin-bottom:8px;">
        <div style="font-size:15px;font-weight:bold;">GORILAS GYM</div>
        <div style="font-size:10px;" id="ticket-fecha">{{ now()->format('d/m/Y H:i') }}</div>
        <div style="border-top:1px dashed #000;margin:6px 0;"></div>
    </div>
    <div id="ticket-items"></div>
    <div style="border-top:1px dashed #000;margin:6px 0;"></div>
    <div id="ticket-totales"></div>
    <div style="border-top:1px dashed #000;margin:6px 0;"></div>
    <div style="text-align:center;font-size:10px;margin-top:8px;">¡Gracias por su compra!<br>Vuelva pronto</div>
</div>

<div class="grid grid-cols-1 gap-5 lg:grid-cols-3 items-start">

    {{-- IZQUIERDA --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Buscador --}}
        <div style="border-radius:12px;border:1px solid #e5e7eb;background:#fff;">
            <div class="pdv-label">Buscar producto</div>
            <div style="padding:14px;position:relative;z-index:200;">
                <input
                    type="text"
                    wire:model.live.debounce.350ms="busqueda"
                    placeholder="Nombre del producto o código de barras..."
                    class="pdv-input"
                    autofocus
                    autocomplete="off"
                />
                @if(count($resultados) > 0)
                <div class="drop-box">
                    @foreach($resultados as $r)
                    <button wire:click="seleccionar({{ $r['id'] }})" class="drop-item">
                        <span style="font-weight:500;color:#111827;">{{ $r['nombre'] }}</span>
                        <div style="display:flex;gap:14px;align-items:center;flex-shrink:0;margin-left:12px;">
                            <span style="font-size:12px;color:#9ca3af;">Stock: {{ $r['stock'] }}</span>
                            <span style="font-weight:700;font-size:15px;color:#d97706;">${{ number_format($r['precio'], 2) }}</span>
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Carrito --}}
        <div class="pdv-card">
            <div class="pdv-label">
                <span>Carrito</span>
                @if(!empty($carrito))
                <span style="background:#fef3c7;color:#92400e;padding:3px 12px;border-radius:20px;font-size:12px;font-weight:600;">
                    {{ count($carrito) }} producto(s)
                </span>
                @endif
            </div>

            @if(empty($carrito))
            <div style="padding:48px 24px;text-align:center;">
                <p style="color:#6b7280;font-size:14px;margin:0;">Sin productos en el carrito</p>
                <p style="color:#9ca3af;font-size:13px;margin:6px 0 0;">Busca por nombre o escanea un código de barras</p>
            </div>
            @else
            <table style="width:100%;border-collapse:collapse;font-size:14px;">
                <thead>
                    <tr style="background:#f9fafb;">
                        <th style="padding:10px 16px;text-align:left;font-weight:600;font-size:12px;color:#6b7280;">Producto</th>
                        <th style="padding:10px 8px;text-align:center;font-weight:600;font-size:12px;color:#6b7280;">Cant.</th>
                        <th style="padding:10px 8px;text-align:right;font-weight:600;font-size:12px;color:#6b7280;">Precio</th>
                        <th style="padding:10px 16px;text-align:right;font-weight:600;font-size:12px;color:#6b7280;">Subtotal</th>
                        <th style="padding:10px 8px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carrito as $item)
                    <tr style="border-top:1px solid #f3f4f6;">
                        <td style="padding:13px 16px;color:#111827;font-weight:500;">{{ $item['nombre'] }}</td>
                        <td style="padding:13px 8px;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
                                <button wire:click="decrementar({{ $item['id'] }})" class="qty-btn">−</button>
                                <span style="width:24px;text-align:center;font-weight:700;font-size:15px;color:#111827;">{{ $item['cantidad'] }}</span>
                                <button wire:click="incrementar({{ $item['id'] }})" class="qty-btn">+</button>
                            </div>
                        </td>
                        <td style="padding:13px 8px;text-align:right;color:#6b7280;">${{ number_format($item['precio'], 2) }}</td>
                        <td style="padding:13px 16px;text-align:right;font-weight:700;font-size:15px;color:#111827;">${{ number_format($item['subtotal'], 2) }}</td>
                        <td style="padding:13px 10px;text-align:center;">
                            <button wire:click="eliminarDelCarrito({{ $item['id'] }})"
                                style="color:#ef4444;background:none;border:none;cursor:pointer;font-size:16px;font-weight:700;line-height:1;">×</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>

    {{-- DERECHA --}}
    <div class="space-y-4">

        {{-- Socio --}}
        <div class="pdv-card">
            <div class="pdv-label">Socio (opcional)</div>
            <div style="padding:14px;">
                <select wire:model="socio_id" class="pdv-input" style="cursor:pointer;">
                    <option value="">Cliente general</option>
                    @foreach($this->socios as $socio)
                        <option value="{{ $socio->id }}">{{ $socio->nombre_completo }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Resumen --}}
        <div class="pdv-card">
            <div class="pdv-label">Resumen</div>
            <div style="padding:14px 16px;display:flex;flex-direction:column;gap:14px;">
                <div class="pdv-row">
                    <span style="color:#6b7280;">Subtotal</span>
                    <span style="color:#111827;font-weight:600;">${{ number_format($this->subtotal, 2) }}</span>
                </div>
                <div class="pdv-row">
                    <span style="color:#6b7280;">Descuento ($)</span>
                    <input type="number" wire:model.live="descuento" min="0" step="0.50"
                        style="width:100px;text-align:right;padding:7px 11px;border-radius:8px;border:1.5px solid #d1d5db;background:#fff;font-size:14px;outline:none;color:#111827;"/>
                </div>
            </div>
            <div class="total-band">
                <span style="font-size:15px;font-weight:500;color:#374151;">Total</span>
                {{-- data-total para que JS lo lea siempre actualizado --}}
                <span id="total-display"
                    data-total="{{ $this->total }}"
                    style="font-size:26px;font-weight:700;color:#d97706;">
                    ${{ number_format($this->total, 2) }}
                </span>
            </div>
        </div>

        {{-- Método de pago --}}
        <div class="pdv-card">
            <div class="pdv-label">Método de pago</div>
            <div style="padding:12px 14px;display:flex;gap:8px;">
                @foreach(['efectivo' => 'Efectivo', 'transferencia' => 'Transfer.', 'tarjeta' => 'Tarjeta'] as $val => $label)
                <button wire:click="$set('metodo_pago', '{{ $val }}')"
                    class="metodo-btn {{ $metodo_pago === $val ? 'sel-'.$val : '' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>

            @if($metodo_pago === 'efectivo')
            <div style="padding:0 14px 14px;border-top:1px solid #f3f4f6;padding-top:14px;display:flex;flex-direction:column;gap:12px;">
                <div class="pdv-row">
                    <span style="color:#6b7280;">El cliente paga con</span>
                    <div style="display:flex;align-items:center;gap:5px;">
                        <span style="color:#6b7280;">$</span>
                        <input
                            type="number"
                            id="monto-pagado-input"
                            min="0"
                            step="0.50"
                            placeholder="0.00"
                            oninput="calcularCambio(this.value); $wire.set('monto_pagado', parseFloat(this.value) || 0)"
                            style="width:110px;text-align:right;padding:8px 12px;border-radius:8px;border:1.5px solid #d1d5db;background:#fff;font-size:16px;font-weight:600;outline:none;color:#111827;"/>
                  </div>
                </div>
                <div id="cambio-display" style="display:none;"></div>
            </div>
            @endif

            @if($metodo_pago === 'transferencia')
            <div style="padding:0 14px 14px;border-top:1px solid #f3f4f6;padding-top:14px;display:flex;flex-direction:column;gap:8px;">
                <label style="font-size:13px;font-weight:500;color:#6b7280;">Referencia o folio</label>
                <input type="text" wire:model.live="referencia_pago"
                    placeholder="Ej. REF-001234"
                    maxlength="30"
                    class="pdv-input"/>
            </div>
            @endif

            @if($metodo_pago === 'tarjeta')
            <div style="padding:0 14px 14px;border-top:1px solid #f3f4f6;padding-top:14px;display:flex;flex-direction:column;gap:8px;">
                <label style="font-size:13px;font-weight:500;color:#6b7280;">Últimos 4 dígitos</label>
                <input type="text" wire:model.live="referencia_pago"
                    placeholder="0000"
                    maxlength="4"
                    class="pdv-input"
                    style="letter-spacing:0.3em;font-size:24px;font-weight:700;text-align:center;"/>
                <p style="font-size:12px;color:#9ca3af;margin:0;">Solo para referencia de la transacción</p>
            </div>
            @endif
        </div>

        {{-- Cobrar --}}
        <button wire:click="cobrar"
            wire:confirm="¿Confirmar venta por ${{ number_format($this->total, 2) }}?"
            @if(empty($carrito)) disabled @endif
            class="cobrar-btn">
            @if(empty($carrito))
                Agrega productos al carrito
            @else
                Cobrar ${{ number_format($this->total, 2) }}
            @endif
        </button>

        {{-- Ticket --}}
        <div id="btn-ticket" style="display:none;">
            <button onclick="imprimirTicket()" class="ticket-btn">
                Imprimir ticket
            </button>
        </div>

    </div>
</div>

<script>
function calcularCambio(valorPagado) {
    const totalEl = document.getElementById('total-display');
    const display = document.getElementById('cambio-display');
    if (!totalEl || !display) return;

    const total = parseFloat(totalEl.dataset.total) || 0;
    const pagado = parseFloat(valorPagado) || 0;
    const diff = pagado - total;

    if (pagado <= 0) {
        display.style.display = 'none';
        return;
    }

    display.style.display = 'flex';

    if (diff >= 0) {
        display.className = 'cambio-verde';
        display.innerHTML = `
            <span style="font-size:14px;font-weight:600;color:#16a34a;">Cambio a entregar</span>
            <span style="font-size:22px;font-weight:700;color:#16a34a;">$${diff.toFixed(2)}</span>`;
    } else {
        display.className = 'cambio-rojo';
        display.innerHTML = `
            <span style="font-size:14px;font-weight:600;color:#dc2626;">Falta</span>
            <span style="font-size:22px;font-weight:700;color:#dc2626;">$${Math.abs(diff).toFixed(2)}</span>`;
    }
}

// Recalcular si Livewire actualiza el total (al cambiar descuento)
document.addEventListener('livewire:update', () => {
    const input = document.getElementById('monto-pagado-input');
    if (input && input.value) calcularCambio(input.value);
});

function imprimirTicket() {
    const fecha = new Date().toLocaleString('es-MX');
    document.getElementById('ticket-fecha').textContent = fecha;
    document.getElementById('ticket-print').style.display = 'block';
    window.print();
    setTimeout(() => {
        document.getElementById('ticket-print').style.display = 'none';
    }, 1500);
}

window.addEventListener('venta-registrada', event => {
    const data = event.detail[0];

    // Calcular cambio desde el input JS, no desde PHP
    const input = document.getElementById('monto-pagado-input');
    const montoPagado = parseFloat(input?.value) || 0;
    const cambioReal = montoPagado > 0 ? montoPagado - parseFloat(data.total) : 0;

    let itemsHtml = '';
    data.items.forEach(item => {
        itemsHtml += `<div style="display:flex;justify-content:space-between;margin-bottom:4px;color:#000;">
            <span>${item.nombre} x${item.cantidad}</span>
            <span>$${parseFloat(item.subtotal).toFixed(2)}</span>
        </div>`;
    });
    document.getElementById('ticket-items').innerHTML = itemsHtml;

    let t = `<div style="display:flex;justify-content:space-between;margin-bottom:3px;color:#000;"><span>Subtotal</span><span>$${parseFloat(data.subtotal).toFixed(2)}</span></div>`;
    if (parseFloat(data.descuento) > 0) {
        t += `<div style="display:flex;justify-content:space-between;margin-bottom:3px;color:#000;"><span>Descuento</span><span>-$${parseFloat(data.descuento).toFixed(2)}</span></div>`;
    }
    t += `<div style="display:flex;justify-content:space-between;font-weight:bold;font-size:13px;margin-top:5px;color:#000;"><span>TOTAL</span><span>$${parseFloat(data.total).toFixed(2)}</span></div>`;
    t += `<div style="display:flex;justify-content:space-between;margin-top:4px;color:#000;"><span>Método</span><span>${data.metodo_pago}</span></div>`;
    if (data.referencia) {
        t += `<div style="display:flex;justify-content:space-between;color:#000;"><span>Ref.</span><span>${data.referencia}</span></div>`;
    }
    if (cambioReal > 0) {
        t += `<div style="display:flex;justify-content:space-between;color:#000;"><span>Cambio</span><span>$${cambioReal.toFixed(2)}</span></div>`;
    }
    document.getElementById('ticket-totales').innerHTML = t;

    // Limpiar
    if (input) input.value = '';
    const display = document.getElementById('cambio-display');
    if (display) display.style.display = 'none';

    document.getElementById('btn-ticket').style.display = 'block';
});
</script>
</x-filament-panels::page>