<?php

/**
 * Model driver-specific objects and provide storage/retrieval from the database.
 *
 * @author Clay Bellou
 */

namespace DRyft;


class Driver extends User
{
    protected $isAvailable;

    protected $rate;

    public function __construct(
        string $userName,
        string $lastName,
        string $firstName,
        string $middleName = '',
        string $type = 'Client',
        int $userId = 0,
        string $passwordHash = '',
        string $isAvailable = "No",
        float $rate = 0
    ) {
        $this->id           = $userId;
        $this->username     = $userName;
        $this->type         = $type;
        $this->lastName     = $lastName;
        $this->firstName    = $firstName;
        $this->middleName   = $middleName;
        $this->passwordHash = $passwordHash;
        $this->isAvailable  = $isAvailable;
        $this->rate         = $rate;
    }

    /**
     * Gets current rate of driver.
     * @return float
     */
    public function rate()
    {
        return $this->rate;
    }

    /**
     * If the driver's status is set as available or not.
     * @return bool
     */
    public function isAvailable()
    {
        return ($this->isAvailable == "Yes");
    }

    /**
     * Set is_available for the given driver
     * @param int $DRIVER_ID (Same as $USER_ID)
     * @param bool (Will auto convert to Yes/No format) 
     */
    public static function setIsAvailable(int $DRIVER_ID, bool $isAvailable)
    {
        $targetValue = "No";
        if ($isAvailable) {
            $targetValue = "Yes";
        }
        $db = Database\Connection::getConnection();
        $query = "UPDATE `driver_attributes` SET is_available=\"{$targetValue}\" WHERE DRIVER_ID={$DRIVER_ID}";
        // confirm the query worked
        if (($result = $db->query($query)) === false) {
            // TODO: replace a simple error with an exception
            throw new Database\Exception('Setting is_available Failed: ' . $db->error);
        }
    }

    /**
     * Set rate for the given driver
     * @param int $DRIVER_ID (Same as $USER_ID)
     * @param float New Rate
     */
    public static function setRate(int $DRIVER_ID, float $newRate)
    {
        $db = Database\Connection::getConnection();
        $query = "UPDATE `driver_attributes` SET rate=\"{$newRate}\" WHERE DRIVER_ID={$DRIVER_ID}";
        // confirm the query worked
        if (($result = $db->query($query)) === false) {
            // TODO: replace a simple error with an exception
            throw new Database\Exception('Setting is_available Failed: ' . $db->error);
        }
    }

    /**
     * Load all drivers from the database
     *
     * @return array of Driver objects
     */
    public static function getDrivers()
    {
        // collect them all
        return self::loadDriversByQuery(
            'SELECT * FROM `users`,`driver_attributes` WHERE USER_ID=DRIVER_ID AND `type`="Driver" ORDER BY name_last, name_first, name_middle, USER_ID;'
        );
    }

    /**
     * Load all drivers from the database who have "is_available" set to 'Yes'
     * Aka they are availible to do rides.
     * 
     * @return array of Driver objects
     */
    public static function getAvailableDrivers()
    {
        // collect them all
        return self::loadDriversByQuery(
            'SELECT * FROM `users`,`driver_attributes` WHERE USER_ID=DRIVER_ID AND `type`=Driver AND is_available="Yes" ORDER BY name_last, name_first, name_middle, USER_ID;'
        );
    }

    /**
     * Load multiple drivers from a query
     *
     * @param string $query
     * @return array of Driver objects
     */
    protected static function loadDriversByQuery(string $select)
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
     * Convert a MySQL row object to a Driver
     * Overrides User.objectForRow()
     * @param object
     * @return Driver
     */
    public static function objectForRow($data)
    {

        // Create the appropriate subclass based on the user type
        return new Driver(
            $data->username,
            $data->name_last,
            $data->name_first,
            $data->name_middle,
            $data->type,
            $data->USER_ID,
            $data->pw_hash,
            $data->is_available,
            $data->rate
        );
    }
}
