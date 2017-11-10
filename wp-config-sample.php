<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress 
 */

include("vlz_config.php");

define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );
// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', $db);
/** Tu nombre de usuario de MySQL */
define('DB_USER', $user);
/** Tu contraseña de MySQL */
define('DB_PASSWORD', $pass);
/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', $host);
/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');
/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');



/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '!>(W^HGM+([mkgXVa(NPtO1;n-iMl3>r0}X%BJA7wdT7g)zA~-^I`CfeoGCtzkVU');
define('SECURE_AUTH_KEY', '}khcIf<t,ncn)u(hkR(%jP4*r*{+Z?qh47EUL01@DzF0%%p`5wnGWau?Y5Q,E-B,');
define('LOGGED_IN_KEY', '0}w,79Z?CAdhh7dg2QAI*0OzFM<.6g?X;|CEjxh<n!$+$j5]a<MPk2@[&[.7!=[S');
define('NONCE_KEY', 'W$@53+jr-M,u{O[>l`|(e`=y>>|[y+Lym*mj0D-%1-6$Che4%!I0T}aSk_Y%?)-J');
define('AUTH_SALT', 'pRcfAcQ+cs]~ht_:cEI4R</5KfH5kUU8)>t|Kr`W}]<^G`F^9x&V4LFde&$GqFVl');
define('SECURE_AUTH_SALT', '$.3t9!jOlnnN&4SB>T+bP%R7WG-NY$|;`)fez`{lJiguf-gsAV|I@jL}2Hm>oTzp');
define('LOGGED_IN_SALT', '?P;UP*# E~VwVf4AGAS[!td9d#h{,,)wQ2P3|K}L1;q1PK7bYo,3N+N<:6zH60}%');
define('NONCE_SALT', ' _iao3yeK,Pi(ii;lI;|V$]9iNop~65dkdOpD5M$rJ~-wyD|u4}jm@ !:aW3sv&+');
/**#@-*/
/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';
/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false );
/* ¡Eso es todo, deja de editar! Feliz blogging */
/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

ini_set('log_errors', 'Off');
ini_set('error_log', '/home/kmimos/logs/php-errors.log');