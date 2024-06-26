<?php declare(strict_types=1);
if( !defined( 'ROOT' ) ) {
  define( 'ROOT', $_SERVER['DOCUMENT_ROOT'] . '/' );
}
if( !defined( 'SETTINGS' ) ) {
  define( 'SETTINGS', getSettings( './settings.ini' ) );
}
/**
 * checkParam Check if parameters are set
 *
 * @param   $required
 */
function checkParam( array $required, array $request, ?bool $exact = false )
{
  $params_check = array_intersect( array_keys( $request ), $required );
  if( $exact && count( $params_check ) !== count( $required ) ) {
    http_response_code( 403 );
    trigger_error( "POST not complete", E_USER_ERROR );
  }
  // not exact but check if parameters are required
  if( count( $params_check ) < count( $required ) ) {
    http_response_code( 403 );
    trigger_error( "POST not complete", E_USER_ERROR );
  }
}
/**
 * Undocumented function
 *
 * @param  $settingsFileName File name of the settings file
 * @return array
 */
function getSettings( string $settingsFileName ): array
{
  $settings = parse_ini_file( $settingsFileName, true );
  // add the dsn for easy connection
  $settings['db']['dsn'] = sprintf( "mysql:host=%s;dbname=%s", $settings['db']['host'], $settings['db']['name'] );

  return $settings;
}

/**
 * Autoloader
 */
spl_autoload_register( function (string $class_name) {
  $class_name = str_replace( '\\', '/', $class_name );
  $filename   = ROOT . '/classes/' . $class_name . '.php';
  if( file_exists( $filename ) ) {
    require $filename;
    return;
  }
} );
