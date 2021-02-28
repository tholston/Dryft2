<?php

/**
 * Model Payment-specific objects and provide storage/retrieval from the database.
 * 
 * @author Clay Bellou
 */

namespace DRyft;


class Payment
{
    /**
     * Ride identification number
     * @type int
     */
    protected $paymentID;
    /**
     * Driver identification number
     * @type int
     */
    protected $driverID;
    /**
     * Total miles of all the Rides in this payment
     * @type float
     */
    protected $mileage;
    /**
     * Payment rate for all rides in this payment
     * @type float
     */
    protected $rate;
    /**
     * Total payment amount (stored to reduce redundent calculations.)
     * @type float
     */
    protected $amount;
    /**
     * ENUM("Paid","Unpaid"), indicating if the driver has recieved this payment yet.
     * @type string
     */
    protected $status;
    //2 Constants for the above status variable.
    const PAID = "Paid";
    const UNPAID = "Unpaid";

    public function __construct(
        int $paymentID,
        int $driverID,
        float $mileage,
        float $rate,
        float $amount,
        string $status
    ) {
        $this->paymentID    = $paymentID;
        $this->driverID     = $driverID;
        $this->mileage      = floatval($mileage);
        $this->rate         = floatval($rate);
        $this->amount       = floatval($amount);
        $this->status       = $status;
    }

    /**
     * Just named id() to keep with the same format of User object
     * @return int
     */
    public function id()
    {
        return $this->paymentID;
    }
    /**
     * @return int
     */
    public function driverID()
    {
        return $this->driverID;
    }
    /**
     * @return float
     */
    public function mileage()
    {
        return $this->mileage;
    }
    /**
     * @return float
     */
    public function rate()
    {
        return $this->rate;
    }
    /**
     * @return float
     */
    public function amount()
    {
        return $this->amount;
    }
    /**
     * @return float
     */
    public function status()
    {
        return $this->status;
    }

    /**
     * Load all payments from the database
     *
     * @return array of Payment objects
     */
    public static function getPayments()
    {
        // collect them all
        return self::loadPaymentsByQuery(
            'SELECT * FROM `driver_payments` ORDER BY PAYMENT_ID DESC;'
        );
    }

    /**
     * Load all payments from the database for the given driver
     *
     * @param int driverID (Same as userID)
     * @return array of Payment objects
     */
    public static function getPaymentsByDriver(int $driverID)
    {
        return self::loadPaymentsByQuery(
            "SELECT * FROM `driver_payments` WHERE DRIVER_ID={$driverID} ORDER BY `status` DESC;"
        );
    }

    /**
     * Load all payments that haven't been paid.
     *
     * @return array of Payment objects
     */
    public static function getUnpaidPayments()
    {
        return self::loadPaymentsByQuery("SELECT * FROM driver_payments WHERE `status`='Unpaid';");
    }

    /**
     * Load all payments that are already paid for a specific driver.
     *
     * @param int driverID (Same as userID)
     * @return array of Payment objects
     */
    public static function getPaidPaymentsByDriver(int $driverID)
    {
        return self::loadPaymentsByQuery("SELECT * FROM driver_payments WHERE DRIVER_ID={$driverID} AND `status`='Paid' ORDER BY PAYMENT_ID DESC;");
    }


    /**
     * Load all payments that haven't been paid for the given driver.
     *
     * @param int driverID (Same as userID)
     * @return array of Payment objects
     */
    public static function getUnpaidPaymentsByDriver(int $driverID)
    {
        return self::loadPaymentsByQuery("SELECT * FROM driver_payments WHERE DRIVER_ID={$driverID} AND `status`='Unpaid';");
    }

    /**
     * Load multiple Rides from a query
     *
     * @param string $query
     * @return array of Ride objects
     */
    public static function loadPaymentsByQuery(string $select)
    {
        // Setup a dummy return value
        $payments = [];

        // Grab a copy of the database connection
        $db = Database\Connection::getConnection();

        // confirm the query worked
        if (($result = $db->query($select)) === false) {
            // TODO: replace a simple error with an exception
            throw new Database\Exception('DB payments Query Failed: ' . $db->error);
        }

        // load and convert each result object
        while (($data = $result->fetch_object()) !== null) {
            $payments[] = self::objectForRow($data);
        }

        // convert the resulting object
        return $payments;
    }

    /**
     * Creates a new payment entry for the given driverID
     * 
     * @param int $driverID (Same as userID)
     * @return boolean True if it succeded, false if it failed.
     */
    public static function addNewPaymentForDriver(int $driverID)
    {
        $driver = Driver::getDriverById($driverID);
        $db = Database\Connection::getConnection();
        $query = "INSERT INTO `driver_payments` (`PAYMENT_ID`, `DRIVER_ID`, `mileage`, `rate`, `amount`, `status`) VALUES
        (default, {$driverID}, 0, {$driver->rate()}, 0, 'Unpaid');";
        // confirm the query worked
        if (($result = $db->query($query)) === false) {
            // TODO: replace a simple error with an exception
            // throw new Database\Exception('DB Adding new Payment Query Failed: ' . $db->error);
            return false;
        } else {
            return true;
        }
    }

    /**
     * Creates entry in payment_rides to link the finished ride to a driver's payment. (Get's the driver from the rideID itself!)
     * Automatically grabs the first unpaid payment it finds, or creates a new one if none exist.
     * Also calculates the new milage and amount (ASSUMES OLD TOTALS WERE CORRECT. It does NOT re-calculate from ALL rides).
     * Uses rate * milage to calculate amount (though that can be changed)
     * 
     * @param int $rideID
     * 
     */
    public static function addFinishedRideToPayment(int $rideID)
    {
        $db = Database\Connection::getConnection();

        $ride = Ride::getRideById($rideID);
        $rideID = $ride->id();
        $driverID = $ride->driverID();
        $payments = self::getUnpaidPaymentsByDriver($driverID);
        $payment = null;
        if (count($payments) <= 0) {
            $success = self::addNewPaymentForDriver($driverID);
            if ($success) {
                $payment = array_shift(self::getUnpaidPaymentsByDriver($driverID));
            }
        } else {
            $payment = array_shift($payments);
        }
        $paymentID = $payment->id();
        // INSERT INTO `payment_rides` (`PAYMENT_ID`, `RIDE_ID`) VALUES (3, 5);
        $query = "INSERT INTO `payment_rides` (`PAYMENT_ID`, `RIDE_ID`) VALUES ({$paymentID}, {$rideID});";
        // confirm the query worked
        if (($result = $db->query($query)) === false) {
            // TODO: replace a simple error with an exception
            throw new Database\Exception('DB Adding new payment_ride Query Failed: ' . $db->error);
        }

        $newMileage = floatval($ride->mileage() + $payment->mileage());
        $newAmount = floatval($payment->rate() * $newMileage);
        // UPDATE `driver_payments` SET mileage=30, amount=299 WHERE PAYMENT_ID=0;
        $query2 = "UPDATE `driver_payments` SET mileage={$newMileage}, amount={$newAmount} WHERE PAYMENT_ID={$paymentID}";
        // confirm the query worked
        if (($result = $db->query($query2)) === false) {
            // TODO: replace a simple error with an exception
            throw new Database\Exception('DB Updating calculations in driver_payments based on new ride Query Failed: ' . $db->error);
        }
    }

    /**
     * Load a Payment by id
     *
     * @param int $paymentID
     * @return mixed
     */
    public static function getPaymentById(int $paymentID)
    {
        // secure the query by forcing an integer value
        $payments = self::loadPaymentsByQuery(
            'SELECT * FROM `driver_payments` WHERE `PAYMENT_ID` = ' . intval($paymentID) . ';'
        );
        // confirm the result set size
        $count = count($payments);
        if ($count > 1) {
            // We must have just one result
            throw new Database\Exception('Single Payment Lookup Failed: returned ' . count($payments) . ' rows.');
        } elseif (!$count) {
            // No results found
            throw new Database\Exception('Single Payment Lookup Failed: no match found.');
        }
        //Return first ride it finds.
        return array_shift($payments);
    }

    /**
     * Convert a MySQL row object to a Payment object
     * 
     * @param object
     * @return Payment
     */
    public static function objectForRow($data)
    {
        // Create the appropriate subclass based on the user type
        return new Payment(
            $data->PAYMENT_ID,
            $data->DRIVER_ID,
            $data->mileage,
            $data->rate,
            $data->amount,
            $data->status
        );
    }
}
