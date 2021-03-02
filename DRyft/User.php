<?php

/**
 * Model user objects and provide storage/retrieval from the database.
 *
 * @author Errol Sayre
 */

namespace DRyft;

class User
{

	/**
	 * User identification number
	 *
	 * Protect this value to make it immutable.
	 *
	 * @type int
	 */
	protected $id;

	/**
	 * User account name
	 *
	 * Protect this value to make it immutable.
	 *
	 * @type string
	 */
	protected $username;

	/**
	 * User type
	 *
	 * Indicates if the user account is for a client, driver, or coordinator.
	 * Protect this value to make it immutable.
	 *
	 * @type string
	 */
	protected $type;

	/**
	 * User password hash
	 *
	 * This value is a hash generated by the password_hash algorithm.
	 * Protect this value to make it immutable without the appropriate accessors.
	 *
	 * @type string
	 */
	protected $passwordHash;

	/**
	 * Last name
	 * @type string
	 */
	public $lastName;

	/**
	 * First name
	 * @type string
	 */
	public $firstName;

	/**
	 * Middle name
	 * @type string
	 */
	public $middleName;

	/**
	 * Email address
	 * @type string
	 */
	public $email;

	/**
	 * Phone number
	 * @type string
	 */
	public $phone;

	/**
	 * Home address
	 *
	 * Home address instance.
	 * Protect this value to make it immutable without the appropriate accessors.
	 *
	 * @type DRyft\Address
	 */
	protected $homeAddress;

	/**
	 * Mailing address
	 *
	 * Mailing address instance.
	 * Protect this value to make it immutable without the appropriate accessors.
	 *
	 * @type DRyft\Address
	 */
	protected $mailingAddress;



	/**
	 * Constructor
	 *
	 * @param string $userName
	 * @param string $lastName
	 * @param string $firstName
	 * @param string $middleName
	 * @param string $type
	 * @param int $userId
	 * @param string $passwordHash
	 * @return DRyft\User
	 */
	public function __construct(
		string $userName = '',
		string $lastName = '',
		string $firstName = '',
		string $middleName = '',
		string $type = 'Client',
		int $userId = 0,
		string $passwordHash = ''
	) {
		$this->id           = $userId;
		$this->username     = $userName;
		$this->lastName     = $lastName;
		$this->firstName    = $firstName;
		$this->middleName   = $middleName;
		$this->passwordHash = $passwordHash;

		$this->setType($type);
	}



	/**
	 * Get the user id
	 * @return int
	 */
	public function id()
	{
		return $this->id;
	}
	/**
	 * Get the username
	 * @return string
	 */
	public function username()
	{
		return $this->username;
	}
	/**
	 * @return string
	 */
	public function firstName()
	{
		return $this->firstName;
	}
	/**
	 * @return string
	 */
	public function lastName()
	{
		return $this->lastName;
	}


	/**
	 * Ensure proper type is set
	 *
	 * @param string $type
	 * @return User
	 */
	protected function setType($type)
	{
		if ($type == Constants::USER_TYPE_CLIENT) {
			$this->type = Constants::USER_TYPE_CLIENT;
		} elseif ($type == Constants::USER_TYPE_DRIVER) {
			$this->type = Constants::USER_TYPE_DRIVER;
		} elseif ($type == Constants::USER_TYPE_COORDINATOR) {
			$this->type = Constants::USER_TYPE_COORDINATOR;
		}
		return $this;
	}

	/**
	 * Is the user a driver
	 */
	public function isDriver()
	{
		if ($this->type == Constants::USER_TYPE_DRIVER) {
			return true;
		}

		return false;
	}

	/**
	 * Is the user a client
	 */
	public function isClient()
	{
		if ($this->type == Constants::USER_TYPE_CLIENT) {
			return true;
		}

		return false;
	}

	/**
	 * Is the user a coordinator
	 */
	public function isCoordinator()
	{
		if ($this->type == Constants::USER_TYPE_COORDINATOR) {
			return true;
		}

		return false;
	}

	/**
	 * Set a new password
	 *
	 * @param string $password
	 */
	public function setPassword(string $password)
	{
		$this->passwordHash = password_hash($password, PASSWORD_DEFAULT);
	}

	/**
	 * Compare a user's password
	 *
	 * @param string $password
	 * @return boolean
	 */
	public function validatePassword(string $password)
	{
		return password_verify($password, $this->passwordHash);
	}

	/**
	 * Lazy-init the home address
	 *
	 * @return DRyft\Address
	 */
	public function homeAddress()
	{
		if (!$this->homeAddress instanceof Address) {
			try {
				$this->homeAddress = Address::getAddressById(intval($this->homeAddress));
			} catch (Database\Exception $e) {
				$this->homeAddress = new Address();
			}
		}
		return $this->homeAddress;
	}

	/**
	 * Lazy-init the mailing address
	 *
	 * @return DRyft\Address
	 */
	public function mailingAddress()
	{
		if (!$this->mailingAddress instanceof Address) {
			try {
				$this->mailingAddress = Address::getAddressById(intval($this->mailingAddress));
			} catch (Database\Exception $e) {
				$this->mailingAddress = new Address();
			}
		}
		return $this->mailingAddress;
	}



	/**
	 * Store the object to the database.
	 *
	 * @return boolean
	 */
	public function save()
	{
		// insert/update the record in the database
		// get a reference to the database
		$db = Database\Connection::getConnection();

		// determine if this is an insert or update
		if ($this->id) {
			$query = 'UPDATE `users`' . PHP_EOL
				. 'SET ' . PHP_EOL
				. '  `username` = "'        . $db->escape_string($this->username)     . '",' . PHP_EOL
				. '  `type` = "'            . $db->escape_string($this->type)         . '",' . PHP_EOL
				. '  `pw_hash` = "'         . $db->escape_string($this->passwordHash) . '",' . PHP_EOL
				. '  `name_last` = "'       . $db->escape_string($this->lastName)     . '",' . PHP_EOL
				. '  `name_first` = "'      . $db->escape_string($this->firstName)    . '",' . PHP_EOL
				. '  `name_middle` = "'     . $db->escape_string($this->middleName)   . '",' . PHP_EOL
				. '  `email` = "'           . $db->escape_string($this->email)        . '",' . PHP_EOL
				. '  `phone` = "'           . $db->escape_string($this->phone)        . '",' . PHP_EOL
				. '  `home_address` = '     . intval($this->homeAddress()->id())      . ','  . PHP_EOL
				. '  `mailing_address` = '  . intval($this->mailingAddress()->id())          . PHP_EOL
				. 'WHERE `USER_ID` = '    . intval($this->id) . ';';
			if ($db->query($query) === false) {
				throw new Database\Exception('Unable to save user: ' . $db->error . PHP_EOL . '<pre>' . $query . '</pre>');
			}
		} else {
			// TODO: build this out
			$query = 'INSERT INTO `users` (' . PHP_EOL
				. '  `username`,' . PHP_EOL
				. '  `type`,' . PHP_EOL
				. '  `pw_hash`,' . PHP_EOL
				. '  `name_last`,' . PHP_EOL
				. '  `name_first`,' . PHP_EOL
				. '  `name_middle`,' . PHP_EOL
				. '  `email`,' . PHP_EOL
				. '  `phone`,' . PHP_EOL
				. '  `home_address`,' . PHP_EOL
				. '  `mailing_address`' . PHP_EOL
				. ') VALUES (' . PHP_EOL
				. '  "' . $db->escape_string($this->username)     . '",' . PHP_EOL
				. '  "' . $db->escape_string($this->type)         . '",' . PHP_EOL
				. '  "' . $db->escape_string($this->passwordHash) . '",' . PHP_EOL
				. '  "' . $db->escape_string($this->lastName)     . '",' . PHP_EOL
				. '  "' . $db->escape_string($this->firstName)    . '",' . PHP_EOL
				. '  "' . $db->escape_string($this->middleName)   . '",' . PHP_EOL
				. '  "' . $db->escape_string($this->email)        . '",' . PHP_EOL
				. '  "' . $db->escape_string($this->phone)        . '",' . PHP_EOL
				. '   ' . intval($this->homeAddress()->id())      . ','  . PHP_EOL
				. '   ' . intval($this->mailingAddress()->id())          . PHP_EOL
				. ');';
			if ($db->query($query) !== false) {
				// try to read the user id back
				$this->id = $db->insert_id;
			} else {
				throw new Database\Exception('Unable to insert user: ' . $db->error . PHP_EOL . '<pre>' . $query . '</pre>');
			}
		}

		// Determine if we must create a user attributes entry
		if ($this->type == Constants::USER_TYPE_DRIVER) {
			// We don't care about updates... so just blindly throw an insert to make sure a value is there
			$db->query('INSERT INTO `driver_attributes` ( `DRIVER_ID` ) VALUES (' . intval($this->id) . ');');
		} else {
			$db->query('DELETE FROM `driver_attributes` WHERE `DRIVER_ID` = ' . intval($this->id) . ';');
		}

		return true;
	}

	/**
	 * Load changes from a request object
	 *
	 * @return boolean
	 */
	public function updateFromRequest($data)
	{
		// first look for the easy to update items
		foreach ($this->formInputPropertyMapping() as $property => $formKey) {
			if (array_key_exists($formKey, $data)) {
				$this->$property = $data[$formKey];
			}
		}

		// assume if we're getting a password reset the system has already confirmed the user has
		// permissions to modify this
		if (array_key_exists(Constants::PARAM_PASSWORD, $data) && strlen($data[Constants::PARAM_PASSWORD]) > 7) {
			$this->setPassword($data[Constants::PARAM_PASSWORD]);
		}

		// user type can only be changed by a coordinator
		// trust that this field has been removed by the viewtroller
		if (array_key_exists(Constants::PARAM_USER_TYPE, $data)) {
			$this->setType($data[Constants::PARAM_USER_TYPE]);
		}

		// address ids should never be changed once created...

		return true;
	}



	/**
	 * Load a user by username
	 *
	 * @param string $username
	 * @return mixed
	 */
	public static function getUserByName(string $username)
	{

		// Grab a copy of the database connection
		$db = Database\Connection::getConnection();

		$select = 'SELECT * FROM `users` WHERE `username` = "'
			. $db->escape_string($username) . '";';

		return self::loadUserByQuery($select);
	}

	/**
	 * Load a user by id
	 *
	 * @param int $userId
	 * @return mixed
	 */
	public static function getUserById(int $userId)
	{
		// secure the query by forcing an integer value
		return self::loadUserByQuery(
			'SELECT * FROM `users` WHERE `USER_ID` = ' . intval($userId) . ';'
		);
	}

	/**
	 * Load all users from the database
	 *
	 * @return array
	 */
	public static function getUsers()
	{
		// collect them all
		return self::loadUsersByQuery(
			'SELECT * FROM `users` ORDER BY name_last, name_first, name_middle, USER_ID;'
		);
	}

	/**
	 * Execute a single select
	 *
	 * @param string $query
	 * @return User
	 */
	protected static function loadUserByQuery(string $select)
	{
		// use the multi-select to load matching users
		$users = self::loadUsersByQuery($select);

		// confirm the result set size
		$count = count($users);
		if ($count > 1) {
			// We must have just one result
			throw new Database\Exception('Single Lookup Failed: returned ' . count($users) . ' rows.');
		} elseif (!$count) {
			// No results found
			throw new Database\Exception('Single Lookup Failed: no match found.');
		}

		// pop off the single result
		return array_shift($users);
	}

	/**
	 * Load multiple users from a query
	 *
	 * @param string $query
	 * @return array
	 */
	protected static function loadUsersByQuery(string $select)
	{
		// Setup a dummy return value
		$users = [];

		// Grab a copy of the database connection
		$db = Database\Connection::getConnection();

		// confirm the query worked
		if (($result = $db->query($select)) === false) {
			// TODO: replace a simple error with an exception
			throw new Database\Exception('DB Query Failed: ' . $db->error);
		}

		// load and convert each result object
		while (($data = $result->fetch_object()) !== null) {
			$users[] = self::objectForRow($data);
		}

		// convert the resulting object
		return $users;
	}

	/**
	 * Convert a MySQL row object to a User
	 *
	 * @param object
	 * @return User
	 */
	public static function objectForRow($data)
	{

		// Create the appropriate subclass based on the user type
		$user = new User(
			$data->username,
			$data->name_last,
			$data->name_first,
			$data->name_middle,
			$data->type,
			$data->USER_ID,
			$data->pw_hash
		);

		// load the contact fields separately
		$user->email = $data->email;
		$user->phone = $data->phone;

		// temporarily set the instance variables for address objects to the location ids
		$user->homeAddress = $data->home_address;
		$user->mailingAddress = $data->mailing_address;

		return $user;
	}

	/**
	 * Provide a mapping of form fields to properties
	 *
	 * @return array
	 */
	public static function formInputPropertyMapping()
	{
		return [
			'username'   => 'username',
			'firstName'  => 'firstName',
			'middleName' => 'middleName',
			'lastName'   => 'lastName',
			'email'      => 'email',
			'phone'      => 'phone',
		];
	}
}
