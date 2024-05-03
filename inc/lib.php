<?php declare(strict_types=1);
define( 'SETTINGS', getSettings( './settings.ini' ) );

/**
 * checkParam Check if parameters are set
 *
 * @param   $required
 */
function checkParam( array $required, array $request = $_POST )
{
  $params_check = array_intersect( array_keys( $request ), $required );
  if( count( $params_check ) !== count( $required ) ) {
    http_response_code( 403 );
    trigger_error( "POST not complete", E_USER_ERROR);
  }
}

function getSettings( string $settingsFileName ): array
{
  $settings        = parse_ini_file( $settingsFileName, true );
  $settings['dsn'] = sprintf( "mysql:host=%s;dbname=%s", $settings['db']['host'], $settings['db']['name'] );

  return $settings;
}