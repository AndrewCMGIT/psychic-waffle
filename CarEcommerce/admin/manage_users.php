<?php
session_start();
include '../cardealershipDB.php';

/* Admin-only access */
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("Location: login.php");
  exit();
}

/* Delete customer */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_user_id"])) {
  $deleteID = (int) $_POST["delete_user_id"];

  $stmt = $conn->prepare("DELETE FROM users WHERE userID = ? AND role = 'customer'");
  $stmt->bind_param("i", $deleteID);
  $stmt->execute();

  header("Location: manage_users.php");
  exit();
}

/* Search customers */
$search = trim($_GET["search"] ?? "");

if ($search !== "") {
    $like = "%" . $search . "%";

    $stmt = $conn->prepare("
        SELECT userID, first_name, last_name, address, city, postcode, email, tele_no
        FROM users
        WHERE role = 'customer' 
        AND (
            first_name LIKE ?
            OR last_name LIKE ?
            OR email LIKE ?
            OR city LIKE ?
            OR postcode LIKE ?
        )
        ORDER BY userID ASC
    ");

    $stmt->bind_param("sssss", $like, $like, $like, $like, $like);
} else {
    $stmt = $conn->prepare("
        SELECT userID, first_name, last_name, address, city, postcode, email, tele_no
        FROM users
        WHERE role = 'customer'
        ORDER BY userID ASC
    ");
}

$stmt->execute();
$result = $stmt->get_result();

/* Customer count */
$countResult = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'customer'");
$customerCount = $countResult->fetch_assoc()["total"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> Admin Customers Page - COM336 Car Dealership</title>
  <link rel="stylesheet" href="../styles.css">
</head>

<body>
  <main class="container">
    <section class="hero">
      <div class="hero-content">
        <h2>Customer Management</h2>
        <p>
          Admin users can view, search and delete customer records from the dealership database.
        </p>
        <a href="dashboard.php" class="btn">← Back</a>

        <div class="pill-row">
          <div class="pill">
            <span class="pill-label">Customers</span>
            <span class="pill-value"><?php echo htmlspecialchars($customerCount); ?></span>
          </div>
        </div>
      </div>

      <div class="hero-card">
        <h3>Admin notes</h3>
        <ul>
          <li>This page is protected using PHP sessions.</li>
          <li>Customer data is loaded dynamically from MySQL.</li>
          <li>Only users with the customer role are shown.</li>
        </ul>
      </div>
    </section>

    <!-- Customers -->
    <section class="panel">
      <div class="panel-head">
        <div>
          <h2>Customers</h2>
          <p>Live customer records from the database.</p>
        </div>

        <form method="GET" class="search-form">
          <input
            type="text"
            name="search" 
            placeholder="Search customers..." 
            value="<?php echo htmlspecialchars($search); ?>"
          />
          <button type="submit" class="btn">Search</button>
          <a href="customers.php" class="btn secondary">Reset</a>
        </form>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>CustomerID</th>
              <th>First name</th>
              <th>Last name</th>
              <th>Address</th>
              <th>City</th>
              <th>Postcode</th>
              <th>Email</th>
              <th>Telephone</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            <?php if ($result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td class="mono"><?php echo htmlspecialchars($row["userID"]); ?></td>
                  <td><?php echo htmlspecialchars($row["first_name"]); ?></td>
                  <td><?php echo htmlspecialchars($row["last_name"]); ?></td>
                  <td><?php echo htmlspecialchars($row["address"]); ?></td>
                  <td><?php echo htmlspecialchars($row["city"]); ?></td>
                  <td class="mono"><?php echo htmlspecialchars($row["postcode"]); ?></td>
                  <td><?php echo htmlspecialchars($row["email"]); ?></td>
                  <td class="mono"><?php echo htmlspecialchars($row["tele_no"] ?? "N/A"); ?></td>
                  <td>
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                      <input type="hidden" name="delete_user_id" value="<?php echo htmlspecialchars($row["userID"]); ?>">
                      <button type="submit" class="danger-btn">Delete</button>
                    </form>
                  </td>
                </tr>
                <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="muted">No customers found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

</main>

</body>
</html>