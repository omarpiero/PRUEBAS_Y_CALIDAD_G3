@extends('layouts.app')

@section('title', $course->name)
@section('meta_description', $course->meta_description ?? $course->short_description)

@section('content')
<main class="page">
    {{-- ── Hero del Detalle ── --}}
    <section class="cd-hero" style="position: relative; background: #07172e; color: #fff; overflow: hidden; padding: 80px 0;">
        <div class="cd-overlay" style="position: absolute; inset: 0; background: linear-gradient(115deg, rgba(5,15,50,.95) 0%, rgba(10,30,90,.8) 100%); z-index: 1;"></div>
        
        <div class="container" style="position: relative; z-index: 2; max-width: 1280px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 400px; gap: 48px; align-items: center;">
            <div class="cd-hero-left">
                <span class="cd-category" style="display: inline-block; background: rgba(37, 99, 235, 0.2); border: 1px solid rgba(96, 165, 250, 0.3); color: #93c5fd; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px;">
                    {{ $course->category?->name }}
                </span>
                <h1 style="font-family: 'Noto Serif', serif; font-size: clamp(28px, 3.5vw, 48px); font-weight: 700; line-height: 1.15; margin-bottom: 15px;">
                    {{ $course->name }}
                </h1>
                <p style="font-size: 16px; color: rgba(255, 255, 255, 0.75); line-height: 1.6; margin-bottom: 25px; max-width: 680px;">
                    {{ $course->short_description }}
                </p>
                <div class="cd-meta-row" style="display: flex; gap: 20px; flex-wrap: wrap; font-size: 13px; color: rgba(255, 255, 255, 0.85);">
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" fill="none" stroke="#7dd3fc" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Duración: {{ $course->duration_weeks }} semanas
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" fill="none" stroke="#7dd3fc" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Nivel: {{ ucfirst($course->level) }}
                    </span>
                    @if($course->instructor)
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        <svg width="15" height="15" fill="none" stroke="#7dd3fc" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Instructor: {{ $course->instructor->name }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="cd-hero-right">
                {{-- Espacio para tarjeta de compra --}}
            </div>
        </div>
    </section>

    {{-- ── Contenido Principal del Detalle ── --}}
    <section class="section" style="background: #fcfcfd; padding: 60px 0;">
        <div class="container" style="max-width: 1280px; margin: 0 auto; padding: 0 24px; display: grid; grid-template-columns: 1fr 400px; gap: 48px;">
            
            {{-- Columna Izquierda: Descripción y Temario --}}
            <div class="cd-main-col" style="display: flex; flex-direction: column; gap: 40px;">
                
                {{-- Descripción del Curso --}}
                <div style="background: #fff; padding: 30px; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 15px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">
                        Acerca de este programa
                    </h2>
                    <div style="font-size: 14.5px; color: #4b5563; line-height: 1.75; white-space: pre-line;">
                        {!! $course->description !!}
                    </div>
                </div>

                {{-- Temario del Curso --}}
                <div style="background: #fff; padding: 30px; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                    <h2 style="font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 20px; border-bottom: 2px solid #f3f4f6; padding-bottom: 10px;">
                        Estructura Curricular
                    </h2>
                    @if($course->modules->isEmpty())
                        <p style="font-size: 14px; color: #6b7280; font-style: italic;">El temario se encuentra en preparación.</p>
                    @else
                        <div class="modules-accordion" style="display: flex; flex-direction: column; gap: 15px;">
                            @foreach($course->modules as $index => $module)
                                <div style="border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; background: #fafafa;">
                                    <div style="padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: 15px; color: #1f2937; border-bottom: 1px solid #e5e7eb; background: #fff;">
                                        <span>Módulo {{ $index + 1 }}: {{ $module->name }}</span>
                                        <span style="font-size: 12px; background: #e0f2fe; color: #0369a1; padding: 3px 10px; border-radius: 12px;">
                                            {{ $module->materials->count() }} recursos
                                        </span>
                                    </div>
                                    <div style="padding: 16px 20px; background: #fff;">
                                        @if($module->description)
                                            <p style="font-size: 13.5px; color: #6b7280; margin-bottom: 15px; line-height: 1.5;">{{ $module->description }}</p>
                                        @endif
                                        
                                        @if($module->materials->isEmpty())
                                            <p style="font-size: 13px; color: #9ca3af; font-style: italic; margin: 0;">Sin materiales registrados en este módulo.</p>
                                        @else
                                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 8px;">
                                                @foreach($module->materials->sortBy('order') as $material)
                                                    <li style="display: flex; align-items: center; justify-content: space-between; font-size: 13.5px; color: #4b5563; padding: 8px 12px; background: #f9fafb; border-radius: 8px; border: 1px solid #f3f4f6;">
                                                        <span style="display: flex; align-items: center; gap: 8px;">
                                                            @if($material->type === 'video')
                                                                <svg width="14" height="14" fill="none" stroke="#0ea5e9" stroke-width="2" viewBox="0 0 24 24"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                                            @elseif($material->type === 'documento' || $material->type === 'presentacion')
                                                                <svg width="14" height="14" fill="none" stroke="#eab308" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                                            @elseif($material->type === 'texto')
                                                                <svg width="14" height="14" fill="none" stroke="#10b981" stroke-width="2" viewBox="0 0 24 24"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>
                                                            @else
                                                                <svg width="14" height="14" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                                            @endif
                                                            {{ $material->title }}
                                                        </span>
                                                        <span style="font-size: 11.5px; color: #9ca3af; text-transform: uppercase; font-weight: 600;">
                                                            @if($material->type === 'video')
                                                                Video ({{ $material->duration_minutes ?: '10' }}m)
                                                            @elseif($material->type === 'documento')
                                                                Documento PDF
                                                            @elseif($material->type === 'presentacion')
                                                                Presentación
                                                            @elseif($material->type === 'texto')
                                                                Lectura
                                                            @else
                                                                Descarga
                                                            @endif
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Columna Derecha (Fija): Tarjeta Lateral de Compra --}}
            <div class="cd-sidebar-col" style="position: relative;">
                <div style="position: sticky; top: 100px; background: #fff; border: 1px solid #e5e7eb; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
                    @php
                        $cover = $course->cover_image;
                        if ($cover && !str_starts_with($cover, 'http')) {
                            $cover = asset('storage/' . $cover);
                        }
                        $cover = $cover ?: 'https://images.unsplash.com/photo-1563636619-e9143da7973b?auto=format&fit=crop&w=700&q=88';
                    @endphp
                    <div style="height: 200px; overflow: hidden;">
                        <img src="{{ $cover }}" alt="{{ $course->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    
                    <div style="padding: 24px;">
                        <div style="margin-bottom: 20px;">
                            <span style="font-size: 12px; color: #9ca3af; font-weight: 500; text-transform: uppercase;">Inversión Total</span>
                            @if($course->has_active_offer)
                                <div style="display: flex; align-items: baseline; gap: 8px; margin-top: 4px;">
                                    <span style="font-size: 28px; font-weight: 800; color: #ef4444;">S/ {{ number_format($course->effective_price, 0) }}</span>
                                    <span style="font-size: 15px; text-decoration: line-through; color: #9ca3af;">S/ {{ number_format($course->price, 0) }}</span>
                                </div>
                            @else
                                <div style="font-size: 28px; font-weight: 800; color: #111827; margin-top: 4px;">
                                    S/ {{ number_format($course->price, 0) }}
                                </div>
                            @endif
                        </div>

                        <button class="btn-inscribir" onclick="inscribir(this)" data-course-id="{{ $course->id }}" style="width: 100%; padding: 12px 24px; font-size: 14.5px; border: none; cursor: pointer; text-align: center; border-radius: 12px; margin-bottom: 20px;">
                            Comprar Ahora
                        </button>

                        <div style="display: flex; flex-direction: column; gap: 12px; border-top: 1px solid #f3f4f6; padding-top: 20px;">
                            <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #4b5563;">
                                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Acceso completo e ilimitado</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #4b5563;">
                                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Certificado emitido digitalmente</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #4b5563;">
                                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Materiales privados descargables</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 10px; font-size: 13px; color: #4b5563;">
                                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                <span>Soporte directo del instructor</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
// Registrar al carrito usando únicamente el course_id por seguridad
async function inscribir(btn) {
    @guest
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

    const courseId = btn.dataset.courseId;
    if (!courseId) {
        showToast('❌ ID de curso no especificado.');
        return;
    }

    btn.disabled    = true;
    btn.textContent = 'Agregando…';

    try {
        const res  = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept':       'application/json',
            },
            body: JSON.stringify({ course_id: courseId }),
        });
        const data = await res.json();

        if (res.ok && data.ok) {
            btn.textContent      = '✓ En el carrito';
            btn.style.background = '#16a34a';
            
            const badge = document.getElementById('nav-cart-count');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = 'inline-flex';
                badge.style.transform = 'scale(1.3)';
                setTimeout(() => badge.style.transform = '', 300);
            }
            showToast('🛒 ' + data.msg + ' — <a href="{{ route("checkout") }}" style="color:#fff;font-weight:700;">Ir al carrito →</a>');
        } else {
            btn.disabled    = false;
            btn.textContent = 'Comprar Ahora';
            showToast('ℹ️ ' + (data.msg || 'No se pudo agregar.'));
        }
    } catch (e) {
        btn.disabled    = false;
        btn.textContent = 'Comprar Ahora';
        showToast('❌ Error de conexión.');
    }
}
</script>
@endpush
