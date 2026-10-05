\# 🌊 WAVE Lite



!\[Inicio](img/inicio.png)



\*\*Web Account and Virtual Environment Lite\*\*



\*Versión ligera del sistema WAVE desarrollada con PHP y SQLite.\*



\---



\# 🚀 Bienvenido a WAVE Lite



\*\*WAVE Lite\*\* es una versión optimizada de \*\*WAVE\*\*, diseñada para administrar cuentas digitales, servicios y plataformas utilizando \*\*SQLite\*\* como motor de base de datos.



A diferencia de la versión principal, WAVE Lite \*\*no requiere instalar MySQL o MariaDB\*\*, ya que toda la información se almacena en un único archivo de base de datos SQLite.



Esto hace que el sistema sea ideal para proyectos personales, demostraciones, pruebas y entornos donde se busca simplicidad, portabilidad y facilidad de instalación.



\---



\# ✨ Características



\- 👤 Gestión de usuarios

\- 🔑 Administración de cuentas digitales

\- 🌐 Gestión de servicios y plataformas

\- 🗂️ Organización mediante categorías

\- 📝 Registro de notas para cada cuenta

\- 🖼️ Carga de imágenes para usuarios y servicios

\- ✏️ Crear, editar y eliminar registros

\- 📊 Dashboard con estadísticas generales

\- 🔒 Sistema de autenticación

\- 👤 Perfil de usuario

\- ⚙️ Configuración del sistema

\- 💾 Base de datos SQLite

\- 🚀 No requiere MySQL



\---



\# 🖼️ Capturas de pantalla



\## 🔐 Login



!\[Login](img/login.png)



\---



\## 📊 Dashboard



!\[Dashboard](img/dashboard.png)



\---



\## 👤 Gestión de usuarios



!\[Usuarios](img/usuarioss.png)



\---



\## 🌐 Gestión de servicios



!\[Servicios](img/servicioss.png)



\---



\## 🔑 Gestión de cuentas



!\[Cuentas](img/cuentass.png)



\---





\# ⚙️ Tecnologías utilizadas



\- PHP

\- SQLite

\- HTML5

\- CSS3

\- JavaScript

\- XAMPP / WAMP

\- SQLite3



\---



\# 📁 Estructura del proyecto



```text

WAVE-LITE/

│

├── audio/

├── bd\_lite/

│   ├── wave.db

│   ├── Consultas para crear tablas.txt

│   ├── Insertar datos a Tablas.txt

│   └── Estructura.png

│

├── css/

├── img/

├── img\_iconos/

├── img\_servicios/

├── img\_usuarios/

├── js/

│

├── auth.php

├── conexion.php

├── configuracion.php

├── bienvenida.php

│

├── login.php

├── logout.php

├── dashboard.php

├── dashboard\_2.php

├── perfil.php

│

├── categorias.php

├── categorias\_crear.php

├── categorias\_editar.php

├── categorias\_borrar.php

│

├── servicios.php

├── servicios\_crear.php

├── servicios\_editar.php

├── servicios\_borrar.php

│

├── cuentas.php

├── cuentas\_crear.php

├── cuentas\_editar.php

├── cuentas\_borrar.php

│

├── usuarios.php

├── usuarios\_crear.php

├── usuarios\_editar.php

├── usuarios\_borrar.php

│

└── inicio.html

```

\---



\### 🛢️ Diagrama E-R de la Base de Datos.



!\[Diagrama](bd\_lite/diagrama.png)



\---

\---



\# 🚀 Instalación



\## 1. Clonar el repositorio



```bash

git clone https://github.com/bryan2002-afk/Wave\_Lite.git

```



\---



\## 2. Copiar el proyecto



Coloca la carpeta dentro de tu servidor local.



\### WAMP



```text

wamp64/www/

```



\### XAMPP



```text

xampp/htdocs/

```



\---



\## 3. Verificar la base de datos



La base de datos SQLite ya se encuentra incluida en:



```text

bd\_lite/wave.db

```



No es necesario importar ningún archivo SQL.



\---



\## 4. Configurar la conexión



Edita el archivo:



```text

conexion.php

```



y verifica que la ruta hacia la base de datos SQLite sea correcta.



Ejemplo:



```php

$db = new SQLite3("bd\_lite/wave.db");

```



\---



\## 5. Iniciar el servidor



Solo es necesario iniciar:



\- Apache



No es necesario iniciar MySQL.



\---



\## 6. Ejecutar el proyecto



Abre el navegador y visita:



```text

http://localhost/wave-lite

```



=======================================



\## 🪪 Credencial para Iniciar Sesión



&#x09;User: admin

&#x09;Pss:  admin123



❗⚠️ Cambielos inmediatamente ⚠️❗

=========================================





\---



\# 🔐 Funcionalidades principales



\## 👤 Usuarios



\- Crear usuarios

\- Editar información

\- Eliminar usuarios

\- Imagen de perfil



\---



\## 🔑 Cuentas



\- Registrar cuentas digitales

\- Guardar usuario o correo electrónico

\- Almacenar contraseñas

\- Agregar notas

\- Asociar categorías

\- Asociar servicios



\---



\## 🌐 Servicios



\- Crear plataformas digitales

\- Asignar imágenes

\- Editar información

\- Eliminar registros



\---



\## 🗂️ Categorías



\- Organización de cuentas

\- Administración completa



\---



\## 📊 Dashboard



\- Estadísticas generales

\- Resumen del sistema

\- Accesos rápidos



\---



\## 👤 Perfil



\- Información del usuario

\- Actualización de datos personales



\---



\# 💾 ¿Por qué SQLite?



WAVE Lite utiliza \*\*SQLite\*\*, una base de datos ligera que ofrece múltiples ventajas:



\- No requiere instalar un servidor de base de datos.

\- Toda la información se almacena en un único archivo (`wave.db`).

\- Fácil de transportar entre equipos.

\- Ideal para proyectos personales, educativos y de demostración.

\- Mayor facilidad de instalación.



\---



\# 🔒 Seguridad



El sistema incorpora:



\- Inicio de sesión mediante autenticación

\- Protección mediante sesiones PHP

\- Validación de formularios

\- Restricción de acceso

\- Consultas preparadas

\- Organización segura de la información



\---



\# 📌 Estado del proyecto



🟢 \*\*En desarrollo\*\*



WAVE Lite nace como una alternativa ligera de WAVE para facilitar la instalación y el aprendizaje del desarrollo web utilizando PHP y SQLite.



\---



\# 🔄 Diferencias entre WAVE y WAVE Lite



| WAVE | WAVE Lite |

|------|-----------|

| MySQL | SQLite |

| Requiere servidor MySQL | No requiere servidor |

| Base de datos SQL | Archivo `wave.db` |

| Ideal para producción | Ideal para pruebas y proyectos personales |

| Instalación tradicional | Instalación rápida |



\---



\# 👨‍💻 Autor



\*\*El\_Inge.\*\*



Estudiante de Ingeniería en Sistemas Computacionales.



GitHub:



https://github.com/bryan2002-afk



\---



\# 📄 Licencia



Proyecto desarrollado con fines educativos.



Puede utilizarse como referencia para aprender desarrollo web con \*\*PHP\*\*, \*\*SQLite\*\*, \*\*HTML\*\*, \*\*CSS\*\* y \*\*JavaScript\*\*.



\---



\# ⭐ Apoya el proyecto



Si \*\*WAVE Lite\*\* te resulta útil o te sirve como referencia para aprender PHP con SQLite, considera dejar una ⭐ en este repositorio.



¡Gracias por tu apoyo!

