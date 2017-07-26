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
$sessionHandler->start();

header( 'Content-Type: text/plain; charset=utf-8' );
$now = time();
echo 'Now: ', $now, "\n";
echo 'Session id: ', session_id(), "\n";
echo 'Last visit: ', isset( $_SESSION['last'] ) ? $_SESSION['last'] : 0, "\n";
$_SESSION['last'] = $now;

echo "\n\n";
echo str_repeat( '-', 70 ), "\n";
echo "src: https://phabricator.wikimedia.org/source/tool-mysql-php-session-test/\n";
echo "license: MIT\n";
