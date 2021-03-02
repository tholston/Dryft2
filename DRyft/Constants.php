<?php

/**
 * DRyft Constants
 *
 * List of constants for use in the DRyft system.
 */

namespace DRyft;

class Constants
{
	// Environments
	const DEVELOPMENT     = 'Dev';
	const PRODUCTION      = 'Prod';
	const HOST_TURING     = 'turing';

	// DB elements
	const DB_DEV_USER     = 'dryft';
	const DB_DEV_PASSWORD = 'ADeveloperPassword';
	const DB_DEV_HOST     = 'db';
	const DB_DEV_SCHEMA   = 'dryft';

	// Production DB elements
	const DB_PROD_USER     = 'gamestonk562';
	const DB_PROD_PASSWORD = 'olemiss2021';
	const DB_PROD_HOST     = 'localhost';
	const DB_PROD_SCHEMA   = 'gamestonk562';

	// Clay Working Copy items
	const CLAY_ENVIRONMENT = 'Clay';
	const CLAY_USER        = 'cabellou';
	const CLAY_DB_USER     = 'cabellou';
	const CLAY_DB_PASSWORD = 'cabelloudb';
	const CLAY_DB_HOST     = self::DB_PROD_HOST;
	const CLAY_DB_SCHEMA   = 'cabellou';

	// User types
	const USER_TYPE_CLIENT      = 'Client';
	const USER_TYPE_COORDINATOR = 'Coordinator';
	const USER_TYPE_DRIVER      = 'Driver';

	// Common URL parameters
	const PARAM_ACTION    = 'action';
	const PARAM_ID        = 'id';
	const PARAM_USER      = 'user';
	const PARAM_TYPE      = 'type';
	const PARAM_USER_TYPE = 'userType';
	const PARAM_PASSWORD  = 'password';

	const ACTION_NEW    = 'new';
	const ACTION_CREATE = 'create';
	const ACTION_EDIT   = 'edit';
	const ACTION_UPDATE = 'update';
	const ACTION_ERROR  = 'error';

	// Custom actions for the users controller
	const ACTION_NEW_ADDRESS = 'new-address';

	// User Address types
	const ADDRESS_TYPE_HOME    = 'home';
	const ADDRESS_TYPE_MAILING = 'mailing';



	/**
	 * Memoize the determined environment
	 */
	protected static $environment;

	/**
	 * Identify the environment
	 */
	public static function environment()
	{
		if (!self::$environment) {
			self::$environment = self::DEVELOPMENT;

			// determine if we're on turing
			if (php_uname('n') == self::HOST_TURING) {
				// determine if this is Clay's account
				if (strpos(dirname(__FILE__), self::CLAY_USER) !== false) {
					// this is still a dev environment, but should use separate credentials
					self::$environment = self::CLAY_ENVIRONMENT;
				} else {
					// this is production
					self::$environment = self::PRODUCTION;
				}
			}
		}
		return self::$environment;
	}

	/**
	 * Is this a development environment
	 */
	public static function isDevelopment()
	{
		return self::environment() == self::DEVELOPMENT;
	}

	/**
	 * Is this Clay's dev environment
	 */
	public static function isClaysEnvironment()
	{
		return self::environment() == self::CLAY_ENVIRONMENT;
	}

	/**
	 * Is this a production environment
	 */
	public static function isProduction()
	{
		return self::environment() == self::PRODUCTION;
	}
}
