# Dockerización y Despliegue de Módulos (Capítulo 10)

> **Referencia al Informe Final:** Cubre el Capítulo 10 (Dockerización y Despliegue).

Si bien en la fase actual de desarrollo local y estabilización operativa el equipo ha optado por el stack nativo ligero en bare-metal (XAMPP / MySQL 3307 / Servidor Interno PHP), la arquitectura SDD y la separación de componentes posibilitan un proceso limpio de contenerización (Docker) para entornos de Producción y Staging.

## 10.1. Estrategia de Dockerización
La dockerización propuesta abstrae los tres pilares del LMS:
1. **Contenedor Web/App (Laravel + PHP-FPM):** Imagen base de PHP 8.2-FPM instalando extensiones requeridas (`pdo_mysql`, `bcmath`, `zip`). Este contenedor despachará las peticiones del frontend.
2. **Contenedor Proxy/Web Server (Nginx):** Imagen de Alpine Linux con Nginx. Actúa como reverse proxy, despachando activos estáticos (generados por Vite en `/public/build/`) y redirigiendo las peticiones lógicas hacia el contenedor PHP-FPM mediante FastCGI.
3. **Contenedor Base de Datos (MySQL):** Imagen de MySQL 8, montando los datos mediante volúmenes de Docker (`docker volumes`) para asegurar persistencia.

## 10.2. Generación del Archivo `docker-compose.yml` (Propuesta Arquitectónica)
En el ciclo de despliegue a producción, el orquestador Compose enlaza los servicios garantizando aislamiento:

```yaml
version: '3.8'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    volumes:
      - ./:/var/www/html
    environment:
      - DB_CONNECTION=mysql
      - DB_HOST=db
      - DB_PORT=3306
  web:
    image: nginx:alpine
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www/html
      - ./nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
  db:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: '${DB_DATABASE}'
      MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
```

## 10.3. Despliegue Moderno Híbrido (Serverless + PaaS)
Dada la agilidad de los ecosistemas actuales (Tech Radar Vol. 34), un despliegue de esta aplicación podría delegar la administración nativa de contenedores usando:
- **Vercel / AWS Lambda:** Utilizando librerías como *Bref* para desplegar el backend PHP y las API como funciones serverless.
- **Laravel Forge / Envoyer:** Para orquestar despliegues sin tiempo de caída (Zero-downtime deployment) conectando directamente el repositorio `ROGERCanchumanyaUC/pruebas-calidad-grupo-03` al servidor en producción, compilando Vite y reiniciando colas (`queue:work`) de forma automatizada mediante hooks web.
