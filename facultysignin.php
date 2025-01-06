<?php
require_once('php_connect.php');
session_start();

// Ensure the user is logged in as a faculty member
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'faculty') {
  //  header("Location: index.php");
    //exit();
//}

$faculty_id = $_SESSION['user_id'];
$faculty_name = $_SESSION['name'];

// Fetch booking requests for the faculty
$requests_query = "SELECT b.id AS booking_id, s.name AS student_name, b.slot_time, b.status 
                   FROM bookings b
                   JOIN users s ON b.student_id = s.id
                   WHERE b.faculty_id = ? AND b.status = 'pending'";
$stmt = $conn->prepare($requests_query);
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$booking_requests = $stmt->get_result();

// Handle booking actions (Accept/Reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['action'])) {
    $booking_id = $_POST['booking_id'];
    $action = $_POST['action'];

    // Update the booking status based on the action
    if ($action === 'accept') {
        $status = 'accepted';
    } elseif ($action === 'reject') {
        $status = 'rejected';
    }

    $update_query = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $status, $booking_id);
    $stmt->execute();

    // Redirect to refresh the page
    header("Location: faculty_page.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Faculty Page</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
  <header>
    <div style="display: flex; justify-content: space-between; padding: 10px;">
      <div>Faculty: <?= htmlspecialchars($faculty_name) ?></div>
      <div><a href="logout.php">Logout</a></div>
    </div>
  </header>
  <main>
    <h2>Booking Requests</h2>
    <?php if ($booking_requests->num_rows > 0): ?>
      <ul>
        <?php while ($row = $booking_requests->fetch_assoc()): ?>
          <li>
            <strong>Student:</strong> <?= htmlspecialchars($row['student_name']) ?> <br>
            <strong>Slot:</strong> <?= htmlspecialchars($row['slot_time']) ?>
            <form method="POST" style="display: inline;">
              <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
              <button type="submit" name="action" value="accept">Accept</button>
              <button type="submit" name="action" value="reject">Reject</button>
            </form>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No booking requests at the moment.</p>
    <?php endif; ?>
  </main>
</body>
</html>
