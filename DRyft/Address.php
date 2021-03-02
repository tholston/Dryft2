<?php

/**
 * Address
 *
 * Model a physical/mailing/street address for locations within the app.
 *
 * @author Noah South
 */

namespace DRyft;

class Address
{

	protected $id;
	public $nickname = '';
	public $latitude = 0.0;
	public $longitude = 0.0;
	public $addressLine1 = '';
	public $addressLine2 = '';
	public $city = '';
	public $state = '';
	public $zip = '';

	public function __construct(
		int $locaid = 0,
		string $nickn = '',
		string $linone = '',
		string $lintwo = '',
		string $cit = '',
		string $stat = '',
		string $zipc = ''
	) {
		$this->id           = $locaid;
		$this->nick     	= $nickn;
		$this->line1        = $linone;
		$this->line2     	= $lintwo;
		$this->city		    = $cit;
		$this->state 	    = $stat;
		$this->zip 			= $zipc;
	}

	/* Simple getter methods for the construct of an Address location */
	public function id()
	{
		return $this->id;
	}
	public function nick()
	{
		return $this->nick;
	}
	public function line1()
	{
		return $this->line1;
	}
	public function line2()
	{
		return $this->line2;
	}
	public function city()
	{
		return $this->city;
	}
	public function state()
	{
		return $this->state;
	}
	public function zip()
	{
		return $this->zip;
	}

	public function __toString()
	{
		// TODO: assemble the properties to the address
		if ($this->id) {
			$address = $this->addressLine1 . ', ';
			if ($this->addressLine2) {
				$address .= $this->addressLine2 . ', ';
			}
			$address .= $this->city . ', ' . $this->state . ' ' . $this->zip;
			return $address;
		}
		return 'No Address Set';
	}

	public function save()
	{
		// insert/update the record in the database
		// TODO: build this out
		// get a reference to the database
		$db = Database\Connection::getConnection();

		// determine if this is an insert or update
		if ($this->id) {
			// TODO: build this out
			$query = 'UPDATE `locations` SET `nickname` = "' . $this->nickname . '" WHERE `LOCATION_ID` = ' . intval($this->id) . ';';
			if ($db->query($query) !== false) {
				return true;
			}
		} else {
			// TODO: build this out
			$query = 'INSERT INTO `locations` ('
				. '`NICKNAME`,'
				. '`line1`,'
				. '`city`,'
				. '`state`,'
				. '`zip`'
				. ') VALUES ('
				. '"' . $db->escape_string($this->nickname) . '",'
				. '"' . $db->escape_string($this->line1) . '",'
				. '"' . $db->escape_string($this->city) . '",'
				. '"' . $db->escape_string($this->state) . '",'
				. '"' . $db->escape_string($this->zip) . '"'
				. ');';
			if ($db->query($query) !== false) {
				// try to read the location id back
				$this->id = $db->insert_id;
				return true;
			} else {
				throw new Database\Exception('Unable to insert address: ' . $db->error);
			}
		}
		return false;
	}

	/**
	 * Load an address by id
	 *
	 * @param int $addressId
	 * @return Address
	 */
	public static function getAddressById(int $addressId)
	{
		// secure the query by forcing an integer value
		return self::loadAddressByQuery(
			'SELECT * FROM `locations` WHERE `LOCATION_ID` = ' . intval($addressId) . ';'
		);
	}

	/**
	 * Load all addresses from the database
	 *
	 * @return array
	 */
	public static function getAddresses()
	{
		// collect them all
		return self::loadAddressesByQuery(
			'SELECT * FROM `locations` ORDER BY LOCATION_ID, state, city, nickname;'
		);
	}

	/**
	 * Execute a single select
	 *
	 * @param string $query
	 * @return Address
	 */
	protected static function loadAddressByQuery(string $select)
	{
		// use the multi-select to load matching addresses
		$addresses = self::loadAddressesByQuery($select);

		// confirm the result set size
		$count = count($addresses);
		if ($count > 1) {
			// We must have just one result
			throw new Database\Exception('Single Lookup Failed: returned ' . count($addresses) . ' rows.');
		} elseif (!$count) {
			// No results found
			throw new Database\Exception('Single Lookup Failed: no match found.');
		}

		// pop off the single result
		return array_shift($addresses);
	}

	/**
	 * Load multiple addresses from a query
	 *
	 * @param string $query
	 * @return array
	 */
	protected static function loadAddressesByQuery(string $select)
	{
		// Setup a dummy return value
		$addresses = [];

		// Grab a copy of the database connection
		$db = Database\Connection::getConnection();

		// confirm the query worked
		if (($result = $db->query($select)) === false) {
			// TODO: replace a simple error with an exception
			throw new Database\Exception('DB Query Failed: ' . $db->error);
		}

		// load and convert each result object
		while (($data = $result->fetch_object()) !== null) {
			$addresses[] = self::objectForRow($data);
		}

		// convert the resulting object
		return $addresses;
	}

	/**
	 * Convert a MySQL row object to an Address
	 *
	 * @param object
	 * @return Address
	 */
	public static function objectForRow($data)
	{
		$address = new Address();
		$address->id = $data->LOCATION_ID;
		$address->latitude = $data->latitude;
		$address->longitude = $data->longitude;
		$address->nickname = $data->nickname;
		$address->addressLine1 = $data->line1;
		$address->addressLine2 = $data->line2;
		$address->city = $data->city;
		$address->state = $data->state;
		$address->zip = $data->zip;
		return $address;
	}
}
