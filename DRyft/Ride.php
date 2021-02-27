<?php

/**
 * Model Ride-specific objects and provide storage/retrieval from the database.
 * 
 * @author Clay Bellou
 */

namespace DRyft;


class Ride
{
    /**
     * Ride identification number
     * @type int
     */
    protected $rideID;
    /**
     * Client identification number
     * @type int
     */
    protected $clientID;
    /**
     * Driver identification number
     * @type int
     */
    protected $driverID;
    /**
     * Pickup location identification number from locations table
     * @type int
     */
    protected $pickupLocationID;
    /**
     * Dropoff location identification number from locations table
     * @type int
     */
    protected $dropoffLocationID;
    /**
     * Dropoff location identification number from locations table
     * @type string
     */
    protected $departureTime;
    /**
     * Dropoff location identification number from locations table
     * @type string
     */
    protected $arrivalTime;
    /**
     * Dropoff location identification number from locations table
     * @type float
     */
    protected $mileage;

    public function __construct(
        int $rideID,
        int $clientID,
        int $driverID,
        int $pickupLocationID,
        int $dropoffLocationID,
        string $departureTime,
        string $arrivalTime,
        float $mileage
    ) {
        $this->rideID               = $rideID;
        $this->clientID             = $clientID;
        $this->driverID             = $driverID;
        $this->pickupLocationID     = $pickupLocationID;
        $this->dropoffLocationID    = $dropoffLocationID;
        $this->departureTime        = strval($departureTime);
        $this->arrivalTime          = strval($arrivalTime);
        $this->mileage              = floatval($mileage);
    }

    /**
     * Just named id() to keep with the same format of User object
     * @return int
     */
    public function id()
    {
        return $this->rideID;
    }
    /**
     * @return int
     */
    public function clientID()
    {
        return $this->clientID;
    }
    /**
     * @return int
     */
    public function driverID()
    {
        return $this->driverID;
    }
    /**
     * @return int
     */
    public function pickupLocationID()
    {
        return $this->pickupLocationID;
    }
    /**
     * @return int
     */
    public function dropoffLocationID()
    {
        return $this->dropoffLocationID;
    }
    /**
     * @return string
     */
    public function departureTime()
    {
        return $this->departureTime;
    }
    /**
     * @return string
     */
    public function arrivalTime()
    {
        return $this->arrivalTime;
    }
    /**
     * @return float
     */
    public function mileage()
    {
        return $this->mileage;
    }

    /**
     * Load all rides from the database
     *
     * @return array of Ride objects
     */
    public static function getRides()
    {
        // collect them all
        return self::loadRidesByQuery(
            'SELECT * FROM `rides` ORDER BY RIDE_ID DESC;'
        );
    }

    /**
     * Load all rides from the database
     *
     * @param int driverID (Same as userID)
     * @return array of Ride objects
     */
    public static function getRidesByDriver($driverID)
    {
        // SELECT * FROM `rides` WHERE driver=2 ORDER BY RIDE_ID DESC;
        $driverIDInt = intval($driverID);
        return self::loadRidesByQuery(
            "SELECT * FROM `rides` WHERE driver={$driverIDInt} ORDER BY RIDE_ID DESC;"
        );
    }

    /**
     * Load multiple Rides from a query
     *
     * @param string $query
     * @return array of Ride objects
     */
    protected static function loadRidesByQuery(string $select)
    {
        // Setup a dummy return value
        $rides = [];

        // Grab a copy of the database connection
        $db = Database\Connection::getConnection();

        // confirm the query worked
        if (($result = $db->query($select)) === false) {
            // TODO: replace a simple error with an exception
            throw new Database\Exception('DB Rides Query Failed: ' . $db->error);
        }

        // load and convert each result object
        while (($data = $result->fetch_object()) !== null) {
            $rides[] = self::objectForRow($data);
        }

        // convert the resulting object
        return $rides;
    }

    /**
     * Load a Ride by id
     *
     * @param int $rideId
     * @return mixed
     */
    public static function getRideById(int $rideId)
    {
        // secure the query by forcing an integer value
        $rides = self::loadRidesByQuery(
            'SELECT * FROM `rides` WHERE `RIDE_ID` = ' . intval($rideId) . ';'
        );
        // confirm the result set size
        $count = count($rides);
        if ($count > 1) {
            // We must have just one result
            throw new Database\Exception('Single Ride Lookup Failed: returned ' . count($rides) . ' rows.');
        } elseif (!$count) {
            // No results found
            throw new Database\Exception('Single Ride Lookup Failed: no match found.');
        }
        //Return first ride it finds.
        return array_shift($rides);
    }

    /**
     * Convert a MySQL row object to a Ride object
     * 
     * @param object
     * @return Ride
     */
    public static function objectForRow($data)
    {
        // Create the appropriate subclass based on the user type
        return new Ride(
            $data->RIDE_ID,
            $data->client,
            $data->driver,
            $data->pickup,
            $data->dropoff,
            strval($data->departure),
            strval($data->arrival),
            floatval($data->mileage)
        );
    }
}
