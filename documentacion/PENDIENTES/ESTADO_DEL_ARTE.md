# Estado del Arte — JM y JS Alimentos
## Plataforma de Capacitación en Línea para la Industria Alimentaria

---

## 1. Introducción

El presente Estado del Arte analiza el contexto tecnológico, educativo e industrial en el que se enmarca el desarrollo de la plataforma de e-learning de **JM y JS Alimentos**. Se revisan las tendencias actuales en plataformas de aprendizaje en línea, el uso de inteligencia artificial en la educación, la situación de la capacitación en la industria alimentaria peruana y las tecnologías utilizadas en sistemas similares, con el propósito de justificar las decisiones de diseño e implementación del proyecto.

---

## 2. E-Learning: Panorama Global

### 2.1 Crecimiento del mercado

El mercado global de e-learning ha experimentado un crecimiento sostenido y acelerado. Según reportes del sector, el mercado de educación en línea superó los **250 mil millones de dólares en 2023** y se proyecta que alcance los **600 mil millones para 2027** (Global Market Insights, 2024). Este crecimiento fue impulsado principalmente por:

- La pandemia de COVID-19 (2020–2022), que obligó a instituciones educativas y empresas a migrar sus capacitaciones al entorno digital.
- La democratización del acceso a Internet en países en desarrollo.
- La creciente demanda de certificaciones profesionales reconocidas por la industria.
- La flexibilidad del aprendizaje asíncrono frente a los modelos presenciales.

### 2.2 Plataformas líderes a nivel global

Las principales plataformas de e-learning que dominan el mercado global son:

| Plataforma | Modelo | Características principales |
|---|---|---|
| **Coursera** | B2C / B2B | Cursos universitarios, certificaciones con Google, IBM, Meta |
| **Udemy** | Marketplace | +220,000 cursos, instructores independientes, precios variables |
| **edX** | B2C / institucional | Fundada por MIT y Harvard, MicroMasters y MicroBachelors |
| **LinkedIn Learning** | B2B | Orientado a habilidades profesionales, integrado con LinkedIn |
| **Moodle** | Open Source / LMS | Plataforma de gestión de aprendizaje autoalojada |
| **Platzi** | B2C (LATAM) | Especializado en tecnología, orientado al mercado latinoamericano |

**Diferencia con el proyecto JM y JS:** Ninguna de estas plataformas ofrece contenido especializado en **normativas de la industria alimentaria peruana** (BPM, HACCP, ISO 22000 en contexto local). El proyecto cubre un nicho de mercado que las grandes plataformas no atienden.

---

## 3. E-Learning en el Perú y América Latina

### 3.1 Adopción en el contexto peruano

El Perú ha registrado un crecimiento significativo en la adopción de plataformas de capacitación en línea. Según el **MINEDU** y el **MINCETUR**, el número de peruanos que tomaron cursos en línea se triplicó entre 2019 y 2022. Sin embargo, persisten brechas:

- **Brecha geográfica:** Las regiones del interior del país (como Junín, donde opera JM y JS) tienen menor acceso a capacitación presencial especializada.
- **Brecha de oferta:** La capacitación en inocuidad alimentaria y normativas BPM/HACCP es escasa en formato digital y en español técnico peruano.
- **Informalidad del sector:** Muchas empresas alimentarias artesanales no tienen acceso a formación formal en calidad y normativa.

### 3.2 Plataformas relevantes en Perú

| Plataforma | Enfoque |
|---|---|
| **Perú Capacita (MTPE)** | Capacitación laboral gratuita, enfocada en empleabilidad general |
| **SENATI Virtual** | Formación técnica industrial, incluye algunos módulos de alimentos |
| **INACAL Capacitación** | Normalización y metrología, talleres en ISO pero presenciales |
| **Cámara de Comercio de Lima** | Cursos empresariales, poco enfoque en industria alimentaria específica |

**Oportunidad identificada:** Ninguna plataforma digital en Perú ofrece un catálogo dedicado exclusivamente a la **industria alimentaria**, combinando certificación, accesibilidad en línea y contenido técnico especializado.

---

## 4. Capacitación en la Industria Alimentaria

### 4.1 Normativas que exigen capacitación

La industria alimentaria peruana está regulada por organismos como **DIGESA**, **SENASA** e **INDECOPI**, que exigen el cumplimiento de normas que requieren capacitación constante del personal:

| Norma | Descripción | Relevancia para el proyecto |
|---|---|---|
| **BPM (Buenas Prácticas de Manufactura)** | RM 449-2006/MINSA — obligatoria para plantas procesadoras | Curso BPM en Industria Alimentaria (S/350) |
| **HACCP** | DS 007-98-SA — análisis de peligros y puntos críticos de control | Curso HACCP en Plantas de Alimentos (S/420) |
| **ISO 9001:2015** | Sistema de gestión de calidad — certificación voluntaria | Curso Gestión de Calidad ISO 9001 (S/450) |
| **ISO 22000:2018** | Sistema de gestión de inocuidad alimentaria | Curso Gestión de Inocuidad ISO 22000 (S/480) |

La obligatoriedad de estas normativas genera una **demanda constante y estructural** de capacitación, haciendo viable el modelo de negocio de la plataforma.

### 4.2 Situación de la industria alimentaria en Junín

La región Junín es uno de los principales corredores alimentarios del Perú, con producción concentrada en:
- Valle del Mantaro (Huancayo, Chupaca, Concepción)
- Agroindustrias artesanales y semi-industriales
- Plantas de procesamiento y acopio de alimentos

Esta densidad industrial local crea una **audiencia objetivo concentrada geográficamente** que demanda capacitación técnica accesible sin necesidad de trasladarse a Lima.

---

## 5. Inteligencia Artificial en Plataformas Educativas

### 5.1 Tendencias en IA aplicada al e-learning

El uso de inteligencia artificial en plataformas educativas ha evolucionado rápidamente. Las aplicaciones más frecuentes son:

| Aplicación | Descripción | Ejemplo |
|---|---|---|
| **Chatbots de asistencia** | Responden preguntas frecuentes 24/7 sin intervención humana | Duolingo, Khan Academy |
| **Recomendación de contenido** | Sugieren cursos según el historial del usuario | Coursera, LinkedIn Learning |
| **Evaluación automática** | Corrección de ejercicios y retroalimentación inmediata | Grammarly, Codewars |
| **Tutores virtuales** | Guían al estudiante paso a paso en temas complejos | Khanmigo (Khan Academy + GPT-4) |
| **Generación de contenido** | Creación de quizzes, resúmenes y material de estudio | Socratic (Google) |

### 5.2 Modelos de lenguaje de gran escala (LLMs)

Los **Large Language Models (LLMs)** han transformado la interacción entre usuarios y sistemas educativos. Los modelos más relevantes al momento del desarrollo son:

| Modelo | Proveedor | Característica principal |
|---|---|---|
| **GPT-4o** | OpenAI | Multimodal, alta capacidad de razonamiento |
| **Gemini 2.5 Flash** | Google | Rápido, eficiente en costo, multimodal |
| **Claude 3.5 Sonnet** | Anthropic | Alto rendimiento en tareas de análisis y escritura |
| **Llama 3** | Meta | Open source, deployable localmente |

**Elección del proyecto:** Se optó por **Google Gemini 2.5 Flash** por su:
- Acceso gratuito a través de Google AI Studio (apropiado para el presupuesto del proyecto)
- Alta velocidad de respuesta (modelo "flash" = optimizado para latencia baja)
- Integración sencilla via API REST sin SDK de terceros
- Soporte nativo para español técnico

### 5.3 Chatbots en plataformas educativas latinoamericanas

La adopción de chatbots con IA en plataformas educativas de LATAM es aún incipiente. Plataformas como **Platzi** han implementado asistentes de IA para orientar a sus estudiantes, demostrando que el modelo es viable para audiencias hispanohablantes. El proyecto JM y JS replica este enfoque adaptado a la industria alimentaria.

---

## 6. Tecnologías de Desarrollo Web Utilizadas

### 6.1 Laravel como framework backend

**Laravel** es el framework PHP más utilizado en el mundo según múltiples índices (Packagist, GitHub Stars, Google Trends para PHP frameworks). En su versión 12 (2025), ofrece:

- Sistema de routing expresivo y middleware configurable
- ORM Eloquent con soporte completo para relaciones y migraciones
- Sistema de autenticación y autorización built-in
- Soporte nativo para SQLite, ideal para proyectos de menor escala
- Ecosistema maduro: Tinker, Pint, Sail, Pail, Vite integration

**Adopción en proyectos similares:** Laravel es ampliamente usado en plataformas de e-learning medianas en América Latina por su curva de aprendizaje moderada, documentación extensa en español y comunidad activa.

### 6.2 React como librería frontend

**React** (Meta, 2013) es la librería de interfaces de usuario más descargada del ecosistema JavaScript. En su versión 19 (2025) incluye mejoras en el modelo de concurrencia y server components.

En este proyecto, React se usa de forma selectiva para el componente del chatbot, siguiendo el patrón de **islas de interactividad**: la mayor parte de la interfaz es HTML estático renderizado por Blade (más eficiente), y solo los componentes que requieren estado reactivo usan React.

### 6.3 Vite como bundler

**Vite** (Evan You, 2020) ha reemplazado a Webpack como el bundler estándar en el ecosistema moderno. Sus ventajas clave son:
- Tiempo de inicio del servidor de desarrollo en milisegundos (vs. segundos con Webpack)
- Hot Module Replacement (HMR) instantáneo
- Integración nativa con Laravel desde la versión 9

### 6.4 SQLite como base de datos

**SQLite** es la base de datos más desplegada del mundo (presente en cada smartphone Android e iOS, cada navegador web y miles de aplicaciones de escritorio). Para este proyecto es la elección correcta porque:

- No requiere servidor de base de datos separado (cero configuración)
- El archivo `database.sqlite` es portátil y fácil de respaldar
- Rendimiento suficiente para el volumen de datos esperado (cientos de usuarios, no millones)
- Soporte completo en Laravel con Eloquent ORM

**Cuándo escalar:** Si el proyecto crece significativamente en usuarios, la migración a MySQL o PostgreSQL en Laravel implica cambiar una sola línea en `.env` (`DB_CONNECTION=mysql`), dado que Eloquent abstrae el motor de BD.

### 6.5 Tailwind CSS

**Tailwind CSS v4** (2025) representa un cambio de paradigma en el CSS utilitario: en lugar de clases predefinidas, utiliza un motor CSS-first que genera estilos directamente desde las directivas CSS. La plataforma lo tiene disponible y usa CSS personalizado en `public/css/site.css` como sistema de diseño principal.

---

## 7. Sistemas de E-Commerce Educativo

El flujo de **carrito → checkout → inscripción** implementado en el proyecto sigue el modelo estándar de e-commerce educativo, documentado y validado por plataformas como Udemy y Hotmart:

| Característica | Implementación en el proyecto |
|---|---|
| **Carrito persistente** | Sesión PHP (sin base de datos) — adecuado para sesiones cortas |
| **Pago único por curso** | Sin suscripciones ni pagos recurrentes |
| **Inscripción automática post-pago** | `Enrollment::create()` inmediato tras validar tarjeta |
| **Prevención de doble compra** | Verificación `EXISTS` antes de crear enrollment |
| **Confirmación visual** | Página de éxito + panel "Mis Cursos" |

El modelo de **sesión para el carrito** (sin persistencia en BD) es el mismo que usa Shopify para carritos anónimos y está ampliamente documentado como práctica correcta para flujos de compra de sesión corta.

---

## 8. Seguridad en Aplicaciones Web Educativas

Las plataformas de e-learning manejan datos personales y financieros, lo que las hace objetivo de ataques. El proyecto implementa las siguientes protecciones alineadas con el **OWASP Top 10**:

| Amenaza OWASP | Mitigación implementada |
|---|---|
| **A01 — Control de acceso roto** | Middleware `auth` y `admin` en todas las rutas protegidas |
| **A02 — Fallas criptográficas** | Bcrypt con 12 rondas para contraseñas; HTTPS recomendado en producción |
| **A03 — Inyección** | Eloquent ORM con query bindings previene SQL injection |
| **A05 — Configuración incorrecta** | Variables sensibles en `.env`, nunca en código fuente |
| **A07 — Fallas de autenticación** | Session regeneration en login, invalidación en logout, tokens CSRF |

---

## 9. Tendencias que Influyen en el Proyecto

| Tendencia | Impacto en el proyecto |
|---|---|
| **Micro-credenciales** | Los cursos ofrecen certificados individuales, alineados con la tendencia de aprendizaje modular |
| **Mobile-first** | Diseño responsivo desde 320px para llegar a profesionales que acceden desde smartphones |
| **IA como asistente** | Chatbot con Gemini reduce barreras de entrada al orientar al usuario en tiempo real |
| **Pagos digitales en Perú** | Crecimiento de Yape, Plin y tarjetas; el checkout está preparado para integrar pasarelas reales |
| **Upskilling industrial** | La demanda de recertificación en normativas alimentarias crece con la regulación peruana |

---

## 10. Conclusión del Estado del Arte

El análisis del estado del arte evidencia que:

1. **Existe un nicho no atendido:** No hay plataformas digitales especializadas en capacitación alimentaria en el Perú, lo que posiciona a JM y JS Alimentos como pioneros en este segmento.

2. **Las tecnologías elegidas son las adecuadas:** Laravel, React, Vite y SQLite son las herramientas estándar de la industria para proyectos de esta escala, con amplio soporte, documentación y comunidad.

3. **La integración de IA es una ventaja competitiva:** El chatbot con Google Gemini 2.5 Flash coloca a la plataforma al nivel de las tendencias más avanzadas en e-learning, siendo aún una característica diferencial en el mercado local.

4. **El modelo de e-commerce educativo está validado:** El flujo carrito → pago → inscripción automática sigue las mejores prácticas documentadas por las plataformas líderes globales.

5. **La regulación peruana sostiene la demanda:** Las obligaciones legales de BPM y HACCP garantizan una demanda estructural y continua de capacitación, haciendo viable el modelo de negocio a largo plazo.

---

*Estado del Arte — JM y JS Alimentos — Mayo 2026*
