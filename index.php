<?php
// Database configuration
$host = 'localhost';  // MySQL host
$dbname = 'student_records'; // Database name
$username = 'your_username'; // Database username
$password = 'your_password'; // Database password

// Connect to MySQL database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Function to fetch all student records
function getAllStudents($pdo) {
    $stmt = $pdo->query('SELECT * FROM students');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to add a new student record
function addStudent($pdo, $name, $email, $phone, $address) {
    $stmt = $pdo->prepare('INSERT INTO students (name, email, phone, address) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $phone, $address]);
}

// Function to delete a student record
function deleteStudent($pdo, $id) {
    $stmt = $pdo->prepare('DELETE FROM students WHERE id = ?');
    $stmt->execute([$id]);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new student record
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        
        addStudent($pdo, $name, $email, $phone, $address);
    }
    
    // Delete a student record
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        deleteStudent($pdo, $id);
    }
}

// Display all student records
$students = getAllStudents($pdo);
?>

<!-- HTML Form to add a new student -->
<form method="post" action="">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" required><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email"><br>
    <label for="phone">Phone:</label><br>
    <input type="text" id="phone" name="phone"><br>
    <label for="address">Address:</label><br>
    <textarea id="address" name="address"></textarea><br>
    <input type="submit" name="add" value="Add Student">
</form>

<!-- Display all student records -->
<?php if (!empty($students)): ?>
    <h2>Student Records</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>Action</th>
        </tr>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo $student['id']; ?></td>
                <td><?php echo $student['name']; ?></td>
                <td><?php echo $student['email']; ?></td>
                <td><?php echo $student['phone']; ?></td>
                <td><?php echo $student['address']; ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                        <input type="submit" name="delete" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No student records found.</p>
<?php endif; ?>

