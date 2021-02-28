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

	public function id()
	{
		return $this->id;
	}
	public function __toString()
	{
		// TODO: assemble the properties to the address
		return '123 Main Street, City, ST 12345';
	}

	public function save()
	{
		// insert/update the record in the database
		// TODO: build this out
		return false;
	}

	public static function getAddressForId(int $addressId)
	{
		// TODO: build this out
		return new Address();
	}
}
