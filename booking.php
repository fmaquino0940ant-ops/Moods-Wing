<?php
require "db.php";
date_default_timezone_set("Asia/Manila");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Only logged-in users can book
if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date   = $_POST["date"] ?? "";
    $time   = $_POST["time"] ?? "";
    $guests = (int)($_POST["guests"] ?? 0);
    $mobile = trim($_POST["mobile"] ?? "");
    $notes  = trim($_POST["notes"] ?? "");
    $q = $con->prepare("SELECT email FROM users WHERE id = ?");
    $q->bind_param("i", $_SESSION["user_id"]);
    $q->execute();
    $row = $q->get_result()->fetch_assoc();

    if (!$row) {
        echo "<script>alert('Please log in again.'); window.location='login.html';</script>";
        exit;
    }
    $email = $row["email"];

    $error = "";

    if (!$date || !$time || $guests < 1 || !$mobile) {
        $error = "Please fill in all required fields.";
    } elseif (!preg_match('/^\d{2}:\d{2}$/', $time)) {
        $error = "Please pick a valid time.";
    } elseif ($date < date("Y-m-d")) {
        $error = "Please choose a date today or later.";
    } elseif (date("w", strtotime($date)) == 2) {
        $error = "Sorry, we are closed on Tuesdays.";
    } elseif ($time < "11:00" || $time > "21:00") {
        $error = "Please choose a time between 11:00 AM and 9:00 PM.";
    } elseif (!preg_match('/^\+?[0-9\s-]{10,15}$/', $mobile)) {
        $error = "Please enter a valid mobile number.";
    }

    if ($error) {
        echo "<script>alert(" . json_encode($error) . "); history.back();</script>";
        exit;
    }

    try {
        $stmt = $con->prepare(
            "INSERT INTO bookings (email, booking_date, booking_time, guests, mobile, notes)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("sssiss", $email, $date, $time, $guests, $mobile, $notes);
        $stmt->execute();

        echo "<script>alert('Booking confirmed! See you soon.'); window.location='home.php';</script>";
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert(" . json_encode("DB error: " . $e->getMessage()) . "); history.back();</script>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book a Table | Moods Wing</title>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Passion+One:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="booking.css?v=2">
</head>
<body class="booking-page">
  <main class="page">
    <!-- LEFT -->
    <section class="hero">
      <img class="hero-bg" src="booking.jpg" alt="" />
      <a href="home.php" class="logo"><img src="Logo.png" alt="Moods Wing"></a>
      <h1>Save your seat,<br />own your mood</h1>
    </section>
    <!-- RIGHT -->
    <section class="panel">
      <nav class="nav">
        <a href="menu.php">Flavor Menu</a>
        <a href="order.php">Order</a>
        <a href="booking.php" aria-current="page">Book a Table</a>
      </nav>
      <div class="card info">
        <h2>Address</h2>
        <div class="row">8229 Dr. Santos Ave, Parañaque, Philippines, 1700</div>
        <h2>Open Hours</h2>
        <div class="row">11:00 AM - 9:00 PM (<span class="closed">CLOSED</span> every Tuesday)</div>
        <h2>Contact</h2>
        <div class="row">+63 920 929 8083</div>
      </div>
      <div class="card booking">
        <form action="booking.php" method="post">
          <div class="grid">
            <div class="field">
              <label for="date">Date</label>
              <input type="date" id="date" name="date" min="<?= date('Y-m-d') ?>" required />
            </div>
            <div class="field">
              <label for="time">Time</label>
              <input type="text" id="time" name="time" placeholder="12:00 PM" required
                     onfocus="this.type='time'; this.min='11:00'; this.max='21:00'"
                     onblur="if(!this.value) this.type='text'" />
            </div>
            <div class="field">
              <label for="guests">Guests</label>
              <input type="number" id="guests" name="guests" min="1" placeholder="0" required />
            </div>
            <div class="field">
              <label for="mobile">Mobile Number</label>
              <input type="tel" id="mobile" name="mobile" placeholder="+63" required />
            </div>
          </div>
          <div class="notes-field">
            <label for="notes">Notes</label>
            <textarea id="notes" name="notes" placeholder="Celebrations, allergies, high chair..."></textarea>
          </div>
          <button type="submit" class="submit">Confirm Booking</button>
        </form>
      </div>
    </section>
  </main>
</body>
</html>
