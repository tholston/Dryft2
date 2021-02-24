<?php
/**
 * constants.php
 *
 * List of constants for use in the DRyft system.
 */

namespace DRyft;

// Environments
const DEVELOPMENT = 'Dev';
const PRODUCTION  = 'Prod';

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

// User types
const USER_TYPE_CLIENT      = 'Client';
const USER_TYPE_COORDINATOR = 'Coordinator';
const USER_TYPE_DRIVER      = 'Driver';
