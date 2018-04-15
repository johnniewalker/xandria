<?php
namespace Xandria\AppEnvironment;

/**
* @desc holds the constants for environment types
*/
class EnvironmentType
	{
	/**
	* @desc live environment where 
	* SERVER_NAME is always the canonical name of the domain
	* display errors is off
	* sensitive data suppresed during triggering of errors
	*/
	const PRODUCTION_ENV = 'production';

	
	/**
	* @desc almost identical to production except 
	* that the SERVER_NAME might be different to the canonical name of the domain
	* display errors might be on, but is normally off
	* sensitive data suppresed during triggering of errors
	*/
	const STAGING_ENV = 'staging';
	
	/**
	* @desc probably a virtual machine environment
	* the SERVER_NAME likely to be different to the canonical name of the domain
	* display errors might be off, but is normally on
	* sensitive data is likely to be shown during triggering of errors
	*/
	const DEVELOPMENT_ENV = 'development';


	static public function isEnvVarSetToATrueValue()
		{
		return ( getenv( 'APPLICATION_ENV' ));
		}


	static public function doesEnvVarIndicateProductionEnvironment()
		{

		if ( ( getenv( 'APPLICATION_ENV' ) )
			 && ( getenv( 'APPLICATION_ENV' ) == self::PRODUCTION_ENV ) )
			{
			//it has a value and that value is 'production'
			return TRUE;
			}
		//else
		return FALSE;
		}

	static public function doesEnvVarIndicateStagingEnvironment()
		{

		if ( ( getenv( 'APPLICATION_ENV' ) )
			 && ( getenv( 'APPLICATION_ENV' ) == self::STAGING_ENV ) )
			{
			//it has a value and that value is 'staging'
			return TRUE;
			}
		//else
		return FALSE;
		}

	static public function doesEnvVarIndicateDevelopmentEnvironment()
		{

		if ( ( getenv( 'APPLICATION_ENV' ) )
			 && ( getenv( 'APPLICATION_ENV' ) == self::DEVELOPMENT_ENV ) )
			{
			//it has a value and that value is 'development'
			return TRUE;
			}
		//else
		return FALSE;
		}	

	}