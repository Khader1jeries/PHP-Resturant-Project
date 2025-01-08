<?php
// Define validation error messages
define('ValidationError', [
    'NAME' => 'Invalid customer name',
    'EMAIL' => 'Invalid email address',
    'PHONE' => 'Invalid phone number',
    'DATE' => 'Invalid date format (YYYY-MM-DD)',
    'TIME' => 'Invalid time format (HH:MM)',
    'FUTURE_DATE' => 'The date and time must be in the future',
    'PEOPLE' => 'Number of people must be a positive number'
]);

// Function to validate reservation details
function validateReservationDetails($data) {
    if (isset($data['customerName']) && !preg_match("/^[a-zA-Z\s'-]+$/", $data['customerName'])) {
        throw new Exception(ValidationError['NAME']);
    }
    if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception(ValidationError['EMAIL']);
    }
    if (isset($data['phone']) && !preg_match("/^\+?[0-9\s\-()]+$/", $data['phone'])) {
        throw new Exception(ValidationError['PHONE']);
    }
    if (isset($data['date']) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $data['date'])) {
        throw new Exception(ValidationError['DATE']);
    }
    if (isset($data['time']) && !preg_match("/^\d{2}:\d{2}$/", $data['time'])) {
        throw new Exception(ValidationError['TIME']);
    }
    if (isset($data['date']) && isset($data['time'])) {
        $reservationDateTime = new DateTime($data['date'] . ' ' . $data['time']);
        if ($reservationDateTime <= new DateTime()) {
            throw new Exception(ValidationError['FUTURE_DATE']);
        }
    }
    if (isset($data['numberOfPeople']) && (!is_numeric($data['numberOfPeople']) || $data['numberOfPeople'] <= 0)) {
        throw new Exception(ValidationError['PEOPLE']);
    }
}

class Reservation {
    private $customerName;
    private $email;
    private $phone;
    private $date;
    private $time;
    private $numberOfPeople;

    public function __construct($customerName, $email, $phone, $date, $time, $numberOfPeople) {
        $this->setCustomerName($customerName);
        $this->setEmail($email);
        $this->setPhone($phone);
        $this->setDate($date);
        $this->setTime($time);
        $this->setNumberOfPeople($numberOfPeople);
    }

    public function setCustomerName($name) {
        validateReservationDetails(['customerName' => $name]);
        $this->customerName = $name;
    }

    public function setEmail($email) {
        validateReservationDetails(['email' => $email]);
        $this->email = $email;
    }

    public function setPhone($phone) {
        validateReservationDetails(['phone' => $phone]);
        $this->phone = $phone;
    }

    public function setDate($date) {
        validateReservationDetails(['date' => $date]);
        $this->date = $date;
    }

    public function setTime($time) {
        validateReservationDetails(['time' => $time]);
        $this->time = $time;
    }

    public function setNumberOfPeople($number) {
        validateReservationDetails(['numberOfPeople' => $number]);
        $this->numberOfPeople = $number;
    }

    public function getCustomerName() {
        return $this->customerName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getNumberOfPeople() {
        return $this->numberOfPeople;
    }

    public function toArray() {
        return [
            'customerName' => $this->getCustomerName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
            'date' => $this->getDate(),
            'time' => $this->getTime(),
            'numberOfPeople' => $this->getNumberOfPeople()
        ];
    }

    public function isValid() {
        try {
            validateReservationDetails($this->toArray());
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $reservation = new Reservation(
            $_POST['customerName'],
            $_POST['email'],
            $_POST['phone'],
            $_POST['date'],
            $_POST['time'],
            $_POST['numberOfPeople']
        );

        if ($reservation->isValid()) {
            // Redirect or process the reservation as needed
            // Example: Save reservation data to a database or send email
            header("Location: #" . http_build_query($reservation->toArray())); //Here !!!!!!!!!!!! We Need to Add File Destination !!!!!
            exit();
        } else {
            $errorMessage = "The date and time must be in the future";
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css_files/reservation.css" />
    <title>Restaurant Reservation</title>
</head>
<body>
    <div class="container" id="container">
        <h2>Restaurant Reservation</h2>
        <?php if (isset($errorMessage)): ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form id="reservationForm" method="POST">
            <input
                type="text"
                id="customerName"
                name="customerName"
                required
                placeholder="Full Name"
            />
            <input
                type="tel"
                id="phone"
                name="phone"
                required
                placeholder="Contact Number"
            />
            <input
                type="email"
                id="email"
                name="email"
                required
                placeholder="Email Address"
            />
            <input type="date" id="date" name="date" required />
            <input type="time" id="time" name="time" required />
            <input
                type="number"
                id="numberOfPeople"
                name="numberOfPeople"
                required
                placeholder="Party Size"
                min="1"
                max="12"
            /><br /><br />
            <input type="submit" value="Reserve" />
        </form>
    </div>
</body>
</html>
