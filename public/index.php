<?php
/**
 * Copyright 2017 Wikimedia Foundation and contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including without
 * limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to
 * whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 */
namespace Bd808\Mpst;

use Bd808\Toolforge\Mysql\SessionHandler;

if ( !defined( 'APP_ROOT' ) ) {
	define( 'APP_ROOT', dirname( __DIR__ ) );
}
require_once APP_ROOT . '/vendor/autoload.php';

// Ensure that a default timezone is set
set_error_handler(
	function ( $errno, $errstr ) {
		throw new Exception( $errstr );
	}
);
try {
	date_default_timezone_get();
} catch ( Exception $e ) {
	// Use UTC if not specified anywhere in .ini
	date_default_timezone_set( 'UTC' );
}
restore_error_handler();

// Setup sessions via mysql storage
$sessionHandler = new SessionHandler();
session_set_save_handler( $sessionHandler, true );
session_register_shutdown();

// Session cookies only live until the browser closes
ini_set( 'session.cookie_lifetime', 0 );
// Only accept session id from cookie
ini_set( 'session.use_cookies', true );
ini_set( 'session.use_only_cookies', true );
ini_set( 'session.use_strict_mode', true );
ini_set( 'session.use_trans_sid', false );
// Use long session ids
ini_set( 'session.hash_function', 'sha256' );
ini_set( 'session.hash_bits_per_character', 6 );
// Don't cache pages where sessions are in use
ini_set( 'session.cache_limiter', 'nocache' );
// Sessions are eligible for cleanup after this many seconds
ini_set( 'session.gc_maxlifetime', 60*60*24 );
// Restrict session cookie to this tool over https
ini_set( 'session.cookie_path', '/mysql-php-session-test' );
ini_set( 'session.cookie_domain', 'tools.wmflabs.org' );
ini_set( 'session.cookie_secure', true );
ini_set( 'session.cookie_httponly', true );

session_name( 'mpst_s' );
session_start();

header( 'Content-Type: text/plain; charset=utf-8' );
$now = time();
echo 'Now: ', $now, "\n";
echo 'Session id: ', session_id(), "\n";
echo 'Last visit: ', $_SESSION['last'], "\n";
$_SESSION['last'] = $now;
