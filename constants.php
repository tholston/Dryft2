<?php

/**
 * constants.php
 *
 * List of constants for use in the DRyft system.
 */

namespace DRyft;

// Environments
const DEVELOPMENT  = 'Dev';
const PRODUCTION   = 'Prod';
const HOST_TURING  = 'turing';

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
const CLAY_DB_USER     = '';
const CLAY_DB_PASSWORD = '';
const CLAY_DB_HOST     = DB_PROD_HOST;
const CLAY_DB_SCHEMA   = '';

// User types
const USER_TYPE_CLIENT      = 'Client';
const USER_TYPE_COORDINATOR = 'Coordinator';
const USER_TYPE_DRIVER      = 'Driver';

// Common URL parameters
const PARAM_ACTION  = 'action';
const ACTION_NEW    = 'new';
const ACTION_CREATE = 'create';
const ACTION_EDIT   = 'edit';
const ACTION_UPDATE = 'update';
const ACTION_ERROR  = 'error';
const PARAM_ID      = 'id';
const PARAM_USER    = 'user';
