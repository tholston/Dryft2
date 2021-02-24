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
		string $userName,
		string $lastName,
		string $firstName,
		string $middleName = '',
		string $type = 'Client',
		int $userId = 0,
		string $passwordHash = ''
	) {
		$this->id           = $userId;
		$this->username     = $userName;
		$this->type         = $type;
		$this->lastName     = $lastName;
		$this->firstName    = $firstName;
		$this->middleName   = $middleName;
		$this->passwordHash = $passwordHash;
	}


	/**
	 * Get the firstName
	 * @return string
	 */
	public function firstName()
	{
		return $this->firstName;
	}

	/**
	 * Get the lastName
	 * @return string
	 */
	public function lastName()
	{
		return $this->lastName;
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
	 * Get the homeAddress
	 * @return string
	 */
	public function homeAddress()
	{
		return $this->homeAddress;
	}

	/**
	 * Is the user a coordinator
	 */
	public function isCoordinator()
	{
		if ($this->type == USER_TYPE_COORDINATOR) {
			return true;
		}

		return false;
	}

	/**
	 * Is the user a client
	 */
	public function isClient()
	{
		if ($this->type == USER_TYPE_CLIENT) {
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
	 * Store the object to the database.
	 *
	 * @return boolean
	 */
	public function save()
	{
		return false;
	}



	/**
	 * Load a user by username
	 *
	 * @param string $username
	 * @return mixed
	 */
	public function getUserByName(string $username)
	{

		// Grab a copy of the database connection
		$db = Database\Connection::getConnection();

		$select = 'SELECT * FROM `users` WHERE `username` = "'
			. $db->escape_string($username) . '";';

		// confirm the query worked
		if (($result = $db->query($select)) === false) {
			// TODO: replace a simple error with an exception
			return null;
		}

		// confirm the result set size
		if ($result->num_rows != 1) {
			// TODO: replace a simple error with an exception
			return null;
		}

		// confirm the result object
		if (($data = $result->fetch_object()) === null) {
			// TODO: replace a simple error with an exception
			return null;
		}

		// convert the resulting object
		return self::objectForRow($data);
	}

	/**
	 * Convert a MySQL row object to a User
	 *
	 * @param object
	 * @return User
	 */
	public function objectForRow($data)
	{

		// Create the appropriate subclass based on the user type
		return new User(
			$data->username,
			$data->name_last,
			$data->name_first,
			$data->name_middle,
			$data->type,
			$data->USER_ID,
			$data->pw_hash
		);
	}
}
