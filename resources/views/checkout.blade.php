@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<style>
    body > #navbar, body > footer { display: none; }
    #ai-chat { position: relative; z-index: 100000; display: block !important; }
    .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    .checkout-shell { min-height: 100vh; background: #eaf7ff; color: #0b2538; font-family: "Poppins", system-ui, sans-serif; }
    .checkout-header { position: sticky; top: 0; z-index: 50; padding: 16px clamp(18px,5vw,32px); border-bottom: 1px solid rgba(21,101,142,.16); background: rgba(248,252,255,.96); backdrop-filter: blur(14px); }
    .checkout-header-inner { width: min(1200px,100%); margin: 0 auto; display: flex; align-items: center; justify-content: space-between; gap: 20px; }
    .checkout-brand { display: flex; align-items: center; gap: 10px; color: #0b2538; font-size: 18px; font-weight: 800; text-decoration: none; }
    .checkout-brand img { height: 36px; }
    .checkout-secure { display: inline-flex; align-items: center; gap: 8px; color: #0284c7; font-size: 12px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
    .checkout-main { width: min(1200px,100%); margin: 0 auto; padding: 40px clamp(18px,5vw,32px) 64px; display: grid; grid-template-columns: minmax(0,7fr) minmax(360px,5fr); gap: 64px; }
    .checkout-column { display: flex; flex-direction: column; gap: 28px; }
    .checkout-title h1 { margin: 0 0 6px; color: #0b2538; font-size: 24px; font-weight: 700; }
    .checkout-title p { margin: 0; color: #587082; font-size: 14px; line-height: 1.5; }
    .payment-methods { display: grid; gap: 12px; }
    .payment-method { padding: 20px 24px; display: flex; align-items: flex-start; gap: 16px; border: 1px solid #0284c7; border-radius: 10px; background: rgba(248,252,255,.96); box-shadow: 0 8px 24px rgba(2,132,199,.1); }
    .payment-method input { width: 18px; height: 18px; margin-top: 3px; accent-color: #0284c7; }
    .payment-method strong { display: block; margin-bottom: 3px; color: #0b2538; font-size: 15px; font-weight: 600; }
    .payment-method span span { display: block; color: #587082; font-size: 13px; line-height: 1.45; }
    .payment-method .material-symbols-outlined { margin-left: auto; color: #0284c7; font-size: 26px; }
    .card-panel { border: 1px solid rgba(21,101,142,.16); border-radius: 10px; background: rgba(248,252,255,.96); padding: 28px; box-shadow: 0 8px 24px rgba(24,38,33,.06); display: grid; gap: 16px; }
    .notice { padding: 12px 14px; border-radius: 8px; font-size: 13px; line-height: 1.5; }
    .notice-info { background: #e0f2fe; color: #075985; }
    .notice-error { background: #fee2e2; border: 1px solid #fecaca; color: #dc2626; }
    .ack-box { display: flex; align-items: flex-start; gap: 10px; padding: 14px; border: 1px solid rgba(21,101,142,.18); border-radius: 8px; background: #fff; }
    .ack-box input { width: 18px; height: 18px; margin-top: 2px; accent-color: #0284c7; }
    .ack-box label { color: #0b2538; font-size: 14px; line-height: 1.5; }
    .field-error { color: #ef4444; font-size: 12px; }
    .summary-card { position: sticky; top: 100px; overflow: hidden; border: 1px solid rgba(21,101,142,.16); border-radius: 12px; background: rgba(248,252,255,.96); box-shadow: 0 14px 34px rgba(24,38,33,.08); }
    .summary-head { padding: 22px 24px; border-bottom: 1px solid rgba(21,101,142,.12); }
    .summary-title { margin: 0 0 4px; color: #0b2538; font-size: 20px; font-weight: 700; }
    .summary-head p { margin: 0; font-size: 13px; color: #587082; }
    .cart-items { max-height: 320px; overflow-y: auto; }
    .cart-item { display: flex; gap: 14px; align-items: flex-start; padding: 16px 24px; border-bottom: 1px solid rgba(21,101,142,.08); }
    .cart-item:last-child { border-bottom: none; }
    .cart-thumb { width: 56px; height: 56px; border-radius: 8px; background: #dff3ff; overflow: hidden; flex-shrink: 0; border: 1px solid rgba(21,101,142,.12); }
    .cart-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .cart-info { flex: 1; min-width: 0; }
    .cart-info strong { display: block; font-size: 13.5px; font-weight: 600; color: #0b2538; line-height: 1.35; }
    .cart-info .cart-level { font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; display: inline-block; margin-top: 4px; }
    .cart-level.basico { background: #dcfce7; color: #15803d; }
    .cart-level.intermedio { background: #fef3c7; color: #92400e; }
    .cart-level.avanzado { background: #fee2e2; color: #991b1b; }
    .cart-price { font-size: 14px; font-weight: 700; color: #0b2538; white-space: nowrap; }
    .cart-remove { background: none; border: none; cursor: pointer; color: #94a3b8; font-size: 18px; padding: 0; line-height: 1; }
    .cart-remove:hover { color: #ef4444; }
    .cart-empty { padding: 40px 24px; text-align: center; color: #587082; font-size: 14px; }
    .cart-empty a, .checkout-back { color: #075985; font-size: 13.5px; font-weight: 600; text-decoration: none; }
    .cart-empty a:hover, .checkout-back:hover { text-decoration: underline; }
    .summary-totals { padding: 20px 24px; background: #f8fcff; display: grid; gap: 10px; }
    .summary-row, .summary-total { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .summary-row { color: #587082; font-size: 13.5px; }
    .summary-divider { height: 1px; background: rgba(21,101,142,.12); margin: 4px 0; }
    .summary-total { color: #0b2538; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
    .coupon-box { margin: 8px 0; border: 1px dashed rgba(21,101,142,.15); padding: 12px; border-radius: 8px; }
    .coupon-input-row { display: flex; gap: 8px; }
    .coupon-input-row input { flex: 1; min-width: 0; padding: 8px 12px; border: 1px solid rgba(21,101,142,.18); border-radius: 6px; font-size: 13px; outline: none; text-transform: uppercase; }
    .coupon-input-row button { padding: 8px 16px; background: #0284c7; color: #fff; border: none; border-radius: 6px; font-size: 13px; font-weight: 700; cursor: pointer; }
    .pay-button { width: 100%; min-height: 52px; padding: 14px 20px; display: inline-flex; align-items: center; justify-content: center; gap: 8px; border: none; border-radius: 10px; cursor: pointer; color: #fff; background: #0284c7; font-size: 13px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; font-family: inherit; transition: background .2s, transform .2s, box-shadow .2s; }
    .pay-button:hover:not(:disabled) { background: #075985; transform: translateY(-2px); box-shadow: 0 16px 34px rgba(2,132,199,.22); }
    .pay-button:disabled { opacity: .55; cursor: not-allowed; }
    .secure-badges { margin-top: 14px; text-align: center; color: #587082; }
    .secure-badge-row { display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 8px; opacity: .85; }
    .secure-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
    .secure-badges p { margin: 0 auto; font-size: 11.5px; line-height: 1.5; max-width: 320px; }
    @media (max-width: 980px) { .checkout-main { grid-template-columns: 1fr; gap: 36px; } .summary-card { position: static; } }
    @media (max-width: 560px) { .checkout-header-inner { flex-direction: column; align-items: flex-start; } .coupon-input-row { flex-direction: column; } }
</style>
@endpush

@section('content')
<div class="checkout-shell">
    <header class="checkout-header">
        <div class="checkout-header-inner">
            <a href="{{ route('cursos') }}" class="checkout-brand">
                @if (file_exists(public_path('img/logo-jmjs.png')))
                    <img src="{{ asset('img/logo-jmjs.png') }}" alt="JM y JS">
                @endif
                JM y JS Alimentos
            </a>
            <div class="checkout-secure">
                <span class="material-symbols-outlined">lock</span>
                Pago seguro con Stripe
            </div>
        </div>
    </header>

    <main class="checkout-main">
        <section class="checkout-column">
            <div class="checkout-title">
                <h1>Confirmar inscripcion</h1>
                <p>Seras redirigido a Stripe Checkout para completar el pago. JM y JS Alimentos no recibe ni almacena datos de tarjeta.</p>
            </div>

            @if ($errors->any())
                <div class="notice notice-error">{{ $errors->first() }}</div>
            @endif

            @if (session('status'))
                <div class="notice notice-info">{{ session('status') }}</div>
            @endif

            <div class="payment-methods">
                <label class="payment-method" id="pm-stripe">
                    <input checked name="payment_method_preview" type="radio" value="stripe" disabled>
                    <span>
                        <strong>Tarjeta de credito o debito</strong>
                        <span>Procesado fuera de esta plataforma mediante la pagina segura de Stripe Checkout.</span>
                    </span>
                    <span class="material-symbols-outlined">credit_card</span>
                </label>
            </div>

            @if (!empty($cart))
                <form id="payment-form" method="POST" action="{{ route('pago.procesar') }}">
                    @csrf
                    <div class="card-panel">
                        <div class="notice notice-info">
                            Stripe puede operar en modo prueba o produccion segun las llaves configuradas en el servidor. Verifica las credenciales antes de aceptar pagos reales.
                        </div>
                    </div>
                </form>
            @endif

            <a class="checkout-back" href="{{ route('cursos') }}">Volver a cursos</a>
        </section>

        <aside class="summary-wrap">
            <div class="summary-card">
                <div class="summary-head">
                    <h2 class="summary-title">Resumen de compra</h2>
                    <p>{{ count($cart) }} {{ count($cart) === 1 ? 'curso' : 'cursos' }} en tu carrito</p>
                </div>

                @if (empty($cart))
                    <div class="cart-empty">
                        <p>Tu carrito esta vacio.</p>
                        <a href="{{ route('cursos') }}">Explorar cursos</a>
                    </div>
                @else
                    <div class="cart-items" id="cart-items">
                        @foreach ($cart as $item)
                            <div class="cart-item" id="item-{{ $item['course_id'] }}">
                                <div class="cart-thumb">
                                    <img src="https://images.unsplash.com/photo-1550583724-b2692b85b150?w=120&h=120&fit=crop" alt="">
                                </div>
                                <div class="cart-info">
                                    <strong>{{ $item['course_name'] }}</strong>
                                    <span class="cart-level {{ strtolower($item['level']) }}">{{ ucfirst($item['level']) }}</span>
                                </div>
                                <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;">
                                    <span class="cart-price">S/ {{ number_format($item['price'], 0) }}</span>
                                    <button class="cart-remove" title="Quitar" onclick="removeItem({{ $item['course_id'] }})">x</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $subtotalPrice = collect($cart)->sum('price');
                        $discountPrice = $discount ?? 0;
                        $totalPrice = max(0.00, $subtotalPrice - $discountPrice);
                        $sinIgv = $totalPrice / 1.18;
                        $soloIgv = $totalPrice - $sinIgv;
                    @endphp

                    <div class="summary-totals">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>S/ {{ number_format($subtotalPrice, 2) }}</span>
                        </div>

                        <div id="coupon-row" class="summary-row" style="{{ $discountPrice > 0 ? '' : 'display:none;' }}">
                            <span id="coupon-label">Descuento (Cupon: {{ $coupon->code ?? '' }})</span>
                            <span id="coupon-value" style="color:#16a34a;">-S/ {{ number_format($discountPrice, 2) }}</span>
                        </div>

                        <div class="coupon-box">
                            <div class="coupon-input-row" id="coupon-input-group">
                                <input type="text" id="coupon_code" placeholder="CODIGO DE CUPON" value="{{ $coupon->code ?? '' }}" {{ $discountPrice > 0 ? 'disabled' : '' }}>
                                <button type="button" id="coupon_apply_btn" onclick="{{ $discountPrice > 0 ? 'removeCoupon()' : 'applyCoupon()' }}">
                                    {{ $discountPrice > 0 ? 'Quitar' : 'Aplicar' }}
                                </button>
                            </div>
                            <div id="coupon_msg" style="font-size:12px;margin-top:6px;display:none;font-weight:600;"></div>
                        </div>

                        <div class="summary-divider"></div>
                        <div class="summary-row">
                            <span>Base imponible</span>
                            <span id="summary-sin-igv">S/ {{ number_format($sinIgv, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>IGV (18%)</span>
                            <span id="summary-solo-igv">S/ {{ number_format($soloIgv, 2) }}</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span id="summary-total">S/ {{ number_format($totalPrice, 2) }}</span>
                        </div>

                        <button class="pay-button" type="submit" form="payment-form" id="pay-btn">
                            <span class="material-symbols-outlined">check_circle</span>
                            <span id="pay-btn-text">Continuar a Stripe S/ {{ number_format($totalPrice, 2) }}</span>
                        </button>

                        <div class="secure-badges">
                            <div class="secure-badge-row">
                                <div class="secure-badge">
                                    <span class="material-symbols-outlined">shield</span> Stripe Checkout
                                </div>
                                <div class="secure-badge">
                                    <span class="material-symbols-outlined">verified_user</span> Sin datos de tarjeta
                                </div>
                            </div>
                            <p>El pago se completa en Stripe; esta plataforma no almacena datos de tarjeta.</p>
                        </div>
                    </div>
                @endif
            </div>
        </aside>
    </main>
</div>
@endsection

@push('scripts')
<script>
async function removeItem(courseId) {
    const res = await fetch('{{ route("cart.remove") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ course_id: courseId }),
    });
    const data = await res.json();
    if (data.ok) {
        document.getElementById('item-' + courseId)?.remove();
        if (data.count === 0) location.reload();
        document.querySelectorAll('.cart-count').forEach(el => el.textContent = data.count > 0 ? data.count : '');
    }
}

document.getElementById('payment-form')?.addEventListener('submit', function () {
    const btn = document.getElementById('pay-btn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined">hourglass_top</span> Redirigiendo...';
    }
});

async function applyCoupon() {
    const codeInput = document.getElementById('coupon_code');
    const msgDiv = document.getElementById('coupon_msg');
    const btn = document.getElementById('coupon_apply_btn');
    const code = codeInput.value.trim();

    if (!code) {
        msgDiv.style.display = 'block';
        msgDiv.style.color = '#ef4444';
        msgDiv.textContent = 'Ingresa un codigo de cupon.';
        return;
    }

    btn.disabled = true;
    msgDiv.style.display = 'none';

    try {
        const res = await fetch('{{ route("cart.coupon.apply") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ code: code })
        });

        const data = await res.json();

        if (res.status === 200 && data.ok) {
            msgDiv.style.display = 'block';
            msgDiv.style.color = '#16a34a';
            msgDiv.textContent = data.msg;
            codeInput.disabled = true;
            btn.textContent = 'Quitar';
            btn.setAttribute('onclick', 'removeCoupon()');
            btn.style.background = '#dc2626';
            document.getElementById('coupon-row').style.display = 'flex';
            document.getElementById('coupon-label').textContent = 'Descuento (Cupon: ' + data.code + ')';
            document.getElementById('coupon-value').textContent = '-S/ ' + Number(data.discount).toFixed(2);
            updateTotals(Number(data.total));
        } else {
            msgDiv.style.display = 'block';
            msgDiv.style.color = '#ef4444';
            msgDiv.textContent = data.msg || 'Cupon invalido.';
        }
    } catch (err) {
        msgDiv.style.display = 'block';
        msgDiv.style.color = '#ef4444';
        msgDiv.textContent = 'Ocurrio un error al aplicar el cupon.';
    } finally {
        btn.disabled = false;
    }
}

async function removeCoupon() {
    const codeInput = document.getElementById('coupon_code');
    const msgDiv = document.getElementById('coupon_msg');
    const btn = document.getElementById('coupon_apply_btn');

    btn.disabled = true;
    msgDiv.style.display = 'none';

    try {
        const res = await fetch('{{ route("cart.coupon.remove") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const data = await res.json();

        if (data.ok) {
            msgDiv.style.display = 'block';
            msgDiv.style.color = '#16a34a';
            msgDiv.textContent = data.msg;
            codeInput.disabled = false;
            codeInput.value = '';
            btn.textContent = 'Aplicar';
            btn.setAttribute('onclick', 'applyCoupon()');
            btn.style.background = '#0284c7';
            document.getElementById('coupon-row').style.display = 'none';
            updateTotals(Number(data.total));
        }
    } catch (err) {
        msgDiv.style.display = 'block';
        msgDiv.style.color = '#ef4444';
        msgDiv.textContent = 'Ocurrio un error al remover el cupon.';
    } finally {
        btn.disabled = false;
    }
}

function updateTotals(total) {
    const sinIgv = total / 1.18;
    const soloIgv = total - sinIgv;
    document.getElementById('summary-sin-igv').textContent = 'S/ ' + sinIgv.toFixed(2);
    document.getElementById('summary-solo-igv').textContent = 'S/ ' + soloIgv.toFixed(2);
    document.getElementById('summary-total').textContent = 'S/ ' + total.toFixed(2);
    document.getElementById('pay-btn-text').textContent = 'Continuar a Stripe S/ ' + total.toFixed(2);
}
</script>
@endpush
