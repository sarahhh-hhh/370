<?php
require_once('php_connect.php');
session_start();

// Ensure the user is logged in as a student
//if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
  //  header("Location: index.php");
    //exit();
//}

$student_id = $_SESSION['Student_id'];
$student_name = $_SESSION['Name'];

// Fetch notifications
$notifications_query = "SELECT * FROM bookings WHERE student_id = ? AND status IN ('accepted', 'rejected')";
$stmt = $conn->prepare($notifications_query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$notifications = $stmt->get_result();

// Handle search
$search_results = [];
if (isset($_GET['search'])) {
    $search_term = "%" . $_GET['search'] . "%";
    $search_query = "SELECT * FROM consultations WHERE initial LIKE ? AND status = 'available'";
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $search_results = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Page</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>
  <header>
    <div><?= htmlspecialchars($student_name) ?> | ID: <?= $student_id ?></div>
    <a href="logout.php">Logout</a>
  </header>
  <main>
    <form method="GET">
      <input type="text" name="search" placeholder="Search by initial">
      <button type="submit">Search</button>
    </form>
    <h2>Search Results</h2>
    <ul>
      <?php while ($row = $search_results->fetch_assoc()): ?>
        <li>
          <?= htmlspecialchars($row['faculty_name']) ?> | <?= htmlspecialchars($row['slot_time']) ?>
          <form method="POST" action="book_slot.php">
            <input type="hidden" name="consultation_id" value="<?= $row['id'] ?>">
            <button type="submit">Book Slot</button>
          </form>
        </li>
      <?php endwhile; ?>
    </ul>
    <h2>Notifications</h2>
    <ul>
      <?php while ($row = $notifications->fetch_assoc()): ?>
        <li>
          Slot: <?= htmlspecialchars($row['slot_time']) ?> - <?= strtoupper($row['status']) ?>
        </li>
      <?php endwhile; ?>
    </ul>
  </main>
</body>
</html>


