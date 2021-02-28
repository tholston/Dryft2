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
	protected $nick;
	protected $line1;
	protected $line2;
	protected $city;
	protected $state;
	protected $zip;

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
		return $this->line1 . ', ' . $this->city . ', ' . $this->state . ', ' . $this->zip;
		//return '123 Main Street, City, ST 12345';
	}

	public function save()
	{
		// insert/update the record in the database
		// TODO: build this out
		$db = Database\Connection::getConnection();

		return false;
	}

	public static function getAddressForId(int $addressId)
	{
		// TODO: build this out
		$db = Database\Connection::getConnection();

		$select = "SELECT * FROM locations WHERE LOCATION_ID='$addressID'";
		return mysqli_query($db, $select);
	}
}
