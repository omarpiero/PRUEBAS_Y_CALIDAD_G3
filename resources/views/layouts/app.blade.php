<!DOCTYPE html>
<html lang="es">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cursos de Calidad Alimentaria') | JM y JS Alimentos</title>
    <meta name="description" content="@yield('meta_description', 'Capacitación profesional en Buenas Prácticas de Manufactura (BPM), HACCP e ISO para la industria de alimentos y bebidas.')">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
    @stack('styles')
</head>
<body>

<div id="ai-chat"></div>
@vite('resources/js/app.jsx')

<nav id="navbar">
  <a href="{{ route('inicio') }}" class="nav-logo">
    @php
      $brandLogo = file_exists(public_path('img/logo-jmjs.png')) ? asset('img/logo-jmjs.png') : null;
    @endphp

    @if ($brandLogo)
      <img class="logo-image" src="{{ $brandLogo }}" alt="JM y JS Alimentos">
    @else
      <div class="logo-icon">JM</div>
      <div>
      <div class="logo-text">JM y JS</div>
      <div class="logo-sub">Alimentos</div>
      </div>
    @endif
  </a>

  <div class="nav-links">
    <a href="{{ route('inicio') }}" class="{{ request()->routeIs('inicio') ? 'is-active' : '' }}">Inicio</a>
    <a href="{{ route('nosotros') }}" class="{{ request()->routeIs('nosotros') ? 'is-active' : '' }}">Nosotros</a>
    <a href="{{ route('cursos') }}" class="{{ request()->routeIs('cursos') ? 'is-active' : '' }}">Cursos</a>
    <a href="{{ route('contacto') }}#contacto-formulario" class="{{ request()->routeIs('contacto') ? 'is-active' : '' }}">Contacto</a>
    @php $cartCount = count(session()->get('cart', [])); @endphp
    @if ($cartCount > 0 || auth()->check())
      <a href="{{ route('checkout') }}" class="nav-cart {{ request()->routeIs('checkout') ? 'is-active' : '' }}" title="Ver carrito">
          <span class="nav-cart-icon">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                  <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
              </svg>
          </span>
          <span class="nav-cart-label">Carrito</span>
          <span class="cart-count" id="nav-cart-count" style="{{ $cartCount > 0 ? '' : 'display:none' }}">{{ $cartCount ?: '' }}</span>
      </a>
    @endif
    @auth
      <div class="nav-auth">
        @if (auth()->user()->is_admin)
          <a href="{{ route('admin.dashboard') }}" class="nav-admin-pill {{ request()->is('admin*') ? 'active' : '' }}">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Admin
          </a>
        @else
          <a href="{{ route('mi-cuenta') }}" class="nav-mi-cuenta {{ request()->routeIs('mi-cuenta') ? 'active' : '' }}">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Mi Cuenta
          </a>
        @endif
        <div class="nav-user-wrap">
          <div class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
          <span class="nav-user-name">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 14) }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="nav-form">
          @csrf
          <button type="submit" class="nav-logout-btn">Salir</button>
        </form>
      </div>
    @else
      <a href="{{ route('register') }}" class="nav-register-pill">Registrarse</a>
      <a href="{{ route('login') }}" class="btn-nav {{ request()->routeIs('login') ? 'is-active' : '' }}">Login</a>
    @endauth
  </div>
</nav>

@yield('content')

<footer>
  <div class="footer-grid">
    <div class="footer-brand">
      <strong>JM y JS Alimentos</strong>
      <p>Expertos en calidad alimentaria, BPM e ISO para el sector alimentario peruano.</p>
    </div>

    <div class="footer-col">
      <h4>Plataforma</h4>
      <a href="{{ route('cursos') }}">Cursos</a>
    </div>

    <div class="footer-col">
      <h4>Empresa</h4>
      <a href="{{ route('nosotros') }}">Nosotros</a>
      <a href="{{ route('contacto') }}#contacto-formulario">Contacto</a>
      <a href="{{ route('privacidad') }}">Privacidad</a>
      <a href="{{ route('terminos') }}">Terminos</a>
    </div>
  </div>

  <div class="footer-bottom">
    <span>© 2026 JM y JS Alimentos. Huancayo, Perú.</span>
  </div>
</footer>

<div class="toast" id="toast" role="alert" aria-live="polite">
    <div class="toast-accent"></div>
    <div class="toast-body">
        <div class="toast-icon" id="toast-icon"></div>
        <div class="toast-content">
            <div class="toast-title" id="toast-title"></div>
            <div class="toast-msg"   id="toast-msg"></div>
        </div>
        <button class="toast-close" id="toast-close" aria-label="Cerrar">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
    </div>
    <div class="toast-progress">
        <div class="toast-progress-fill" id="toast-fill"></div>
    </div>
</div>

<script>
window.addEventListener('scroll', () => {
    document.getElementById('navbar')?.classList.toggle('scrolled', window.scrollY > 20);
});

const obs = new IntersectionObserver((entries)=>{
    entries.forEach(entry=>{
        if(entry.isIntersecting) entry.target.classList.add('visible');
    });
},{threshold:.1});

document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));

function applyCurtainText(selector){
    const curtainTexts = document.querySelectorAll(selector);

    curtainTexts.forEach((textBlock) => {
        if (textBlock.dataset.curtainApplied === 'true') return;

        const text = textBlock.textContent;
        const chars = Array.from(text);
        const midpoint = (chars.length - 1) / 2;

        textBlock.dataset.curtainApplied = 'true';
        textBlock.classList.add('curtain-text');
        textBlock.setAttribute('aria-label', text.trim());
        textBlock.textContent = '';

        let charIndex = 0;

        text.match(/\S+|\s+/g)?.forEach((token) => {
            if (/^\s+$/.test(token)) {
                textBlock.appendChild(document.createTextNode(' '));
                charIndex += token.length;
                return;
            }

            const word = document.createElement('span');
            word.className = 'curtain-word';
            word.setAttribute('aria-hidden', 'true');

            Array.from(token).forEach((char) => {
                const span = document.createElement('span');
                const fromLeft = charIndex <= midpoint;
                const distance = Math.abs(charIndex - midpoint);
                const delay = Math.max(0, 560 - distance * 22);

                span.className = 'curtain-char';
                span.textContent = char;
                span.style.setProperty('--curtain-from', fromLeft ? '-72px' : '72px');
                span.style.setProperty('--curtain-delay', `${delay}ms`);

                word.appendChild(span);
                charIndex++;
            });

            textBlock.appendChild(word);
        });

        textBlock.classList.add('curtain-ready');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    applyCurtainText('.page > section:first-child .eyebrow, .page > section:first-child h1, .page > section:first-child .lead, .curtain-text');
});

function showToast(msg, duration = 3800) {
    const t     = document.getElementById('toast');
    const icon  = document.getElementById('toast-icon');
    const title = document.getElementById('toast-title');
    const body  = document.getElementById('toast-msg');
    const fill  = document.getElementById('toast-fill');
    if (!t) return;

    // Detectar tipo por prefijo del mensaje
    let type = 'info', titleText = 'Aviso', iconHtml = '💬', clean = msg;

    if (msg.startsWith('✅') || msg.startsWith('🎉')) {
        type = 'success'; titleText = '¡Listo!';
        iconHtml = '<svg width="18" height="18" fill="none" stroke="#16a34a" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>';
        clean = msg.replace(/^[✅🎉]\s*/, '');
    } else if (msg.startsWith('❌')) {
        type = 'error'; titleText = 'Error';
        iconHtml = '<svg width="18" height="18" fill="none" stroke="#dc2626" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
        clean = msg.replace(/^❌\s*/, '');
    } else if (msg.startsWith('🛒')) {
        type = 'cart'; titleText = 'Agregado al carrito';
        iconHtml = '<svg width="18" height="18" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>';
        clean = msg.replace(/^🛒\s*/, '');
    } else if (msg.startsWith('ℹ️') || msg.startsWith('ℹ')) {
        type = 'info'; titleText = 'Información';
        iconHtml = '<svg width="18" height="18" fill="none" stroke="#0284c7" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
        clean = msg.replace(/^ℹ️?\s*/, '');
    }

    // Aplicar tipo
    t.className = 'toast toast-' + type;
    icon.innerHTML  = iconHtml;
    title.textContent = titleText;
    body.innerHTML  = clean;   // innerHTML para soportar links

    // Barra de progreso
    fill.style.animation = 'none';
    void fill.offsetWidth; // reflow
    fill.style.animation = `toast-drain ${duration}ms linear forwards`;

    // Mostrar
    t.classList.add('show');
    clearTimeout(window._toastTimer);
    window._toastTimer = setTimeout(() => t.classList.remove('show'), duration);
}

// Cerrar con botón
document.getElementById('toast-close')?.addEventListener('click', () => {
    document.getElementById('toast')?.classList.remove('show');
    clearTimeout(window._toastTimer);
});
</script>

@stack('scripts')
</body>
</html>
