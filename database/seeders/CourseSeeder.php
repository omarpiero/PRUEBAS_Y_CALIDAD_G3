<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseModule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Categorías ──
        $categorias = [
            ['name' => 'Calidad', 'slug' => 'calidad', 'description' => 'Cursos de gestión y aseguramiento de calidad alimentaria', 'icon' => '🏅', 'order' => 1],
            ['name' => 'Inocuidad', 'slug' => 'inocuidad', 'description' => 'Cursos de inocuidad y seguridad alimentaria', 'icon' => '🛡️', 'order' => 2],
            ['name' => 'Laboratorio', 'slug' => 'laboratorio', 'description' => 'Cursos de análisis y control de laboratorio', 'icon' => '🔬', 'order' => 3],
            ['name' => 'Producción', 'slug' => 'produccion', 'description' => 'Cursos de procesamiento y producción de alimentos', 'icon' => '🏭', 'order' => 4],
            ['name' => 'Normas ISO', 'slug' => 'normas-iso', 'description' => 'Cursos de implementación de normas ISO', 'icon' => '📋', 'order' => 5],
            ['name' => 'Gestión', 'slug' => 'gestion', 'description' => 'Cursos de gestión empresarial y administrativa', 'icon' => '📊', 'order' => 6],
        ];

        foreach ($categorias as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // ── Los 9 cursos del prototipo migrados a BD ──
        $courses = [
            [
                'category' => 'calidad',
                'name' => 'BPM en Industria Alimentaria',
                'short_description' => 'Domina las Buenas Prácticas de Manufactura aplicadas al procesamiento de alimentos.',
                'description' => 'Domina las Buenas Prácticas de Manufactura aplicadas al procesamiento de alimentos. Aprenderás a identificar puntos críticos, documentar procesos y cumplir con la normativa sanitaria peruana.',
                'cover_image' => 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?w=480&h=260&fit=crop&auto=format',
                'level' => 'basico',
                'price' => 350.00,
                'duration_weeks' => 8,
                'is_featured' => true,
                'status' => 'publicado',
                'meta_description' => 'Curso certificado de Buenas Prácticas de Manufactura para la industria alimentaria peruana. 8 semanas, 100% online.',
                'modules' => [
                    ['name' => 'Introducción a las BPM', 'description' => 'Fundamentos y marco regulatorio de las BPM en el Perú'],
                    ['name' => 'Infraestructura y Equipamiento', 'description' => 'Requisitos de infraestructura para plantas alimentarias'],
                    ['name' => 'Higiene del Personal', 'description' => 'Protocolos de higiene y salud del personal manipulador'],
                    ['name' => 'Control de Procesos', 'description' => 'Documentación y control de procesos productivos'],
                ],
            ],
            [
                'category' => 'normas-iso',
                'name' => 'Gestión de Calidad ISO 9001',
                'short_description' => 'Implementa sistemas de gestión de calidad en empresas del rubro alimentario.',
                'description' => 'Implementa sistemas de gestión de calidad en empresas del rubro alimentario. Incluye plantillas listas para usar, auditorías internas y cómo preparar tu empresa para una certificación real.',
                'cover_image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=480&h=260&fit=crop&auto=format',
                'level' => 'intermedio',
                'price' => 450.00,
                'duration_weeks' => 10,
                'is_featured' => false,
                'status' => 'publicado',
                'meta_description' => 'Curso certificado de ISO 9001 para empresas alimentarias. Plantillas, auditorías internas y preparación para certificación.',
                'modules' => [
                    ['name' => 'Fundamentos ISO 9001:2015', 'description' => 'Principios y requisitos de la norma'],
                    ['name' => 'Contexto de la Organización', 'description' => 'Análisis del contexto y partes interesadas'],
                    ['name' => 'Planificación del SGC', 'description' => 'Diseño del sistema de gestión de calidad'],
                    ['name' => 'Auditoría Interna', 'description' => 'Técnicas y herramientas para auditorías internas'],
                    ['name' => 'Mejora Continua', 'description' => 'Acciones correctivas y mejora continua del SGC'],
                ],
            ],
            [
                'category' => 'laboratorio',
                'name' => 'Control Microbiológico en Alimentos',
                'short_description' => 'Técnicas y protocolos actualizados para el control microbiológico en plantas de alimentos.',
                'description' => 'Técnicas y protocolos actualizados para el control microbiológico en plantas de alimentos. Análisis de coliformes, listeria, salmonella y criterios microbiológicos del MINSA/SENASA para alimentos procesados.',
                'cover_image' => 'https://images.unsplash.com/photo-1582719471384-894fbb16e074?w=480&h=260&fit=crop&auto=format',
                'level' => 'avanzado',
                'price' => 380.00,
                'duration_weeks' => 6,
                'is_featured' => false,
                'status' => 'publicado',
                'meta_description' => 'Curso avanzado de control microbiológico para la industria alimentaria. Análisis de patógenos según normativa peruana.',
                'modules' => [
                    ['name' => 'Microbiología de Alimentos', 'description' => 'Fundamentos de microbiología aplicada'],
                    ['name' => 'Técnicas de Muestreo', 'description' => 'Protocolos de muestreo microbiológico'],
                    ['name' => 'Identificación de Patógenos', 'description' => 'Análisis de coliformes, listeria y salmonella'],
                ],
            ],
            [
                'category' => 'produccion',
                'name' => 'Procesamiento de Alimentos Artesanales',
                'short_description' => 'Aprende técnicas de procesamiento artesanal de alimentos.',
                'description' => 'Aprende técnicas de procesamiento artesanal de alimentos, desde la selección de materias primas hasta el envasado final, con estándares de inocuidad para pequeña y mediana escala.',
                'cover_image' => 'https://images.unsplash.com/photo-1559598467-f8b76c8155d0?w=480&h=260&fit=crop&auto=format',
                'level' => 'basico',
                'price' => 280.00,
                'duration_weeks' => 5,
                'is_featured' => false,
                'status' => 'publicado',
                'meta_description' => 'Curso de procesamiento artesanal de alimentos con estándares de inocuidad. Para emprendedores y pequeñas empresas.',
                'modules' => [
                    ['name' => 'Selección de Materias Primas', 'description' => 'Criterios de calidad para materias primas'],
                    ['name' => 'Técnicas de Procesamiento', 'description' => 'Métodos de procesamiento artesanal'],
                    ['name' => 'Envasado y Conservación', 'description' => 'Técnicas de envasado y vida útil'],
                ],
            ],
            [
                'category' => 'produccion',
                'name' => 'Elaboración de Alimentos Fermentados',
                'short_description' => 'Producción de alimentos fermentados con control de cultivos iniciadores.',
                'description' => 'Producción de alimentos fermentados con control de cultivos iniciadores, parámetros de fermentación y vida útil. Incluye formulación, análisis sensorial y envasado correcto.',
                'cover_image' => 'https://images.unsplash.com/photo-1488477181946-6428a0291777?w=480&h=260&fit=crop&auto=format',
                'level' => 'intermedio',
                'price' => 320.00,
                'duration_weeks' => 6,
                'is_featured' => false,
                'status' => 'publicado',
                'meta_description' => 'Curso de elaboración de alimentos fermentados. Cultivos, parámetros de fermentación y análisis sensorial.',
                'modules' => [
                    ['name' => 'Fundamentos de la Fermentación', 'description' => 'Principios bioquímicos de la fermentación'],
                    ['name' => 'Cultivos Iniciadores', 'description' => 'Selección y manejo de cultivos'],
                    ['name' => 'Control de Parámetros', 'description' => 'Temperatura, pH y tiempo de fermentación'],
                ],
            ],
            [
                'category' => 'inocuidad',
                'name' => 'HACCP en Plantas de Alimentos',
                'short_description' => 'Diseño e implementación del sistema HACCP adaptado a la industria alimentaria peruana.',
                'description' => 'Diseño e implementación del sistema HACCP adaptado a la industria alimentaria peruana. Identificación de peligros físicos, químicos y biológicos, determinación de PCC y elaboración del plan HACCP completo.',
                'cover_image' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=480&h=260&fit=crop&auto=format',
                'level' => 'avanzado',
                'price' => 420.00,
                'duration_weeks' => 9,
                'is_featured' => false,
                'status' => 'publicado',
                'meta_description' => 'Curso avanzado de HACCP para plantas de alimentos. Plan HACCP completo según normativa peruana.',
                'modules' => [
                    ['name' => 'Prerrequisitos del HACCP', 'description' => 'BPM y POES como base del sistema'],
                    ['name' => 'Análisis de Peligros', 'description' => 'Identificación de peligros físicos, químicos y biológicos'],
                    ['name' => 'Determinación de PCC', 'description' => 'Árbol de decisiones y puntos críticos de control'],
                    ['name' => 'Plan HACCP', 'description' => 'Elaboración y documentación del plan HACCP'],
                ],
            ],
            [
                'category' => 'produccion',
                'name' => 'Pasteurización y Tratamiento Térmico',
                'short_description' => 'Fundamentos y operación de tratamientos térmicos en líneas de alimentos procesados.',
                'description' => 'Fundamentos y operación de tratamientos térmicos en líneas de alimentos procesados. Control de temperatura, validación de procesos y mantenimiento preventivo de equipos.',
                'cover_image' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=480&h=260&fit=crop&auto=format',
                'level' => 'basico',
                'price' => 290.00,
                'duration_weeks' => 4,
                'is_featured' => false,
                'status' => 'publicado',
                'meta_description' => 'Curso de pasteurización y tratamiento térmico. Control de temperatura y validación de procesos.',
                'modules' => [
                    ['name' => 'Fundamentos del Tratamiento Térmico', 'description' => 'Principios de la pasteurización'],
                    ['name' => 'Control de Temperatura', 'description' => 'Equipos e instrumentos de control'],
                    ['name' => 'Validación de Procesos', 'description' => 'Protocolos de validación y verificación'],
                ],
            ],
            [
                'category' => 'laboratorio',
                'name' => 'Análisis Fisicoquímico de Alimentos',
                'short_description' => 'Determinación de grasa, proteína, densidad, acidez, pH y sólidos totales en alimentos.',
                'description' => 'Determinación de grasa, proteína, densidad, acidez, pH y sólidos totales en alimentos. Manejo de equipos de laboratorio e interpretación de resultados según normas NTP.',
                'cover_image' => 'https://images.unsplash.com/photo-1576867757603-05b134ebc379?w=480&h=260&fit=crop&auto=format',
                'level' => 'intermedio',
                'price' => 360.00,
                'duration_weeks' => 7,
                'is_featured' => false,
                'status' => 'publicado',
                'meta_description' => 'Curso de análisis fisicoquímico de alimentos según normas NTP. Manejo de equipos de laboratorio.',
                'modules' => [
                    ['name' => 'Introducción al Análisis Fisicoquímico', 'description' => 'Fundamentos y equipos de laboratorio'],
                    ['name' => 'Análisis Proximal', 'description' => 'Determinación de grasa, proteína y cenizas'],
                    ['name' => 'Análisis de pH y Acidez', 'description' => 'Técnicas de medición y normativa NTP'],
                    ['name' => 'Interpretación de Resultados', 'description' => 'Análisis estadístico e informes'],
                ],
            ],
            [
                'category' => 'inocuidad',
                'name' => 'Gestión de Inocuidad Alimentaria ISO 22000',
                'short_description' => 'Implementación integral de la norma ISO 22000:2018 en empresas alimentarias.',
                'description' => 'Implementación integral de la norma ISO 22000:2018 en empresas alimentarias. Integra BPM, HACCP y gestión de riesgos en un sistema robusto alineado a estándares internacionales. Ideal para responsables de calidad e inocuidad.',
                'cover_image' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=480&h=260&fit=crop&auto=format',
                'level' => 'avanzado',
                'price' => 480.00,
                'duration_weeks' => 12,
                'is_featured' => true,
                'status' => 'publicado',
                'meta_description' => 'Curso avanzado de ISO 22000:2018 para empresas alimentarias. Integra BPM, HACCP y gestión de riesgos.',
                'modules' => [
                    ['name' => 'Marco Normativo ISO 22000', 'description' => 'Estructura y requisitos de la norma'],
                    ['name' => 'Programas Prerrequisito', 'description' => 'BPM y POES como fundamento'],
                    ['name' => 'Sistema HACCP Integrado', 'description' => 'HACCP dentro del contexto ISO 22000'],
                    ['name' => 'Gestión de Riesgos', 'description' => 'Enfoque basado en riesgos y oportunidades'],
                    ['name' => 'Certificación y Auditoría', 'description' => 'Preparación para la certificación internacional'],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $category = Category::where('slug', $courseData['category'])->first();
            $modules = $courseData['modules'];
            unset($courseData['category'], $courseData['modules']);

            $courseData['category_id'] = $category->id;
            $courseData['slug'] = Str::slug($courseData['name']);
            $courseData['published_at'] = now();

            $course = Course::updateOrCreate(
                ['slug' => $courseData['slug']],
                $courseData
            );

            // Create modules for the course
            foreach ($modules as $index => $moduleData) {
                $moduleData['course_id'] = $course->id;
                $moduleData['order'] = $index + 1;
                $module = CourseModule::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'order' => $moduleData['order'],
                    ],
                    $moduleData
                );

                CourseMaterial::updateOrCreate(
                    [
                        'module_id' => $module->id,
                        'title' => 'Lectura: ' . $module->name,
                    ],
                    [
                        'type' => 'texto',
                        'description' => 'Material base del modulo para repaso y trabajo autonomo.',
                        'content' => '<p>Revisa los conceptos clave del modulo y aplica la plantilla de trabajo en tu contexto operativo.</p>',
                        'order' => 1,
                        'is_downloadable' => false,
                    ]
                );

                if ($index === 0) {
                    CourseMaterial::updateOrCreate(
                        [
                            'module_id' => $module->id,
                            'title' => 'Video introductorio',
                        ],
                        [
                            'type' => 'video',
                            'description' => 'Introduccion al curso y objetivos del modulo.',
                            'video_source' => 'youtube',
                            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                            'duration_minutes' => 8,
                            'order' => 2,
                            'is_downloadable' => false,
                        ]
                    );
                }
            }
        }
    }
}
