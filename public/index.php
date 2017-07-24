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

// Open database connection
$dbcreds = Utils::mysqlCredentials();
$dbconn = new \mysqli(
	'tools.labsdb',
	$creds['user'],
	$creds['password'],
	$creds['user'] . '__sessions'
);
if ( $dbconn->connect_error ) {
	die( "Connection failed: {$dbconn->connect_error}" );
}

// Setup sessions via mysql storage
$sessionHandler = new \JamieCressey\SessionHandler\SessionHandler();
$sessionHandler->setDbConnection( $dbconn );
$sessionHandler->setDbTable( 'sessions' );
session_set_save_handler( $sessionHandler, true );

session_name( 'mpst_s' );
session_set_cookie_params(
	60*60*24*30, '/mysql-php-session-test', 'tools.wmflabs.org', true, true );
session_start();

header( 'Content-Type: text/plain' );
echo 'Session id: ' . session_id();
echo "Last visit: {$_SESSION['last']}";
$_SESSION['last'] = date();

$dbconn->close();
