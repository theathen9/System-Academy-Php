<?php
// Connect to database
$conn = new mysqli('localhost', 'root', '', 'systemacademy');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term from form
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Get pagination info (optional)
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Function to get departments
function getDepartments($conn, $limit, $offset, $search = '')
{
    $searchSql = '';
    if (!empty($search)) {
        $search = $conn->real_escape_string($search);
        $searchSql = " WHERE department_id LIKE '%$search%' 
                      OR department_name LIKE '%$search%' 
                      OR department_code LIKE '%$search%' ";
    }

    $sql = "SELECT * FROM tblDepartment
            $searchSql
            ORDER BY department_id 
            LIMIT $limit OFFSET $offset";

    return $conn->query($sql);
}

// Get departments
$result = getDepartments($conn, $limit, $offset, $search);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Departments</title>
</head>

<body>
    <h1>Departments</h1>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
        <input type="submit" value="Search">
    </form>


    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Code</th>
                <th>name</th>
                <th>description</th>
                <th>status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['department_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['department_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No results found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Simple pagination -->
    <div>
        <?php if ($page > 1): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">Prev</a>
        <?php endif; ?>
        <span>Page <?php echo $page; ?></span>
        <?php if ($result->num_rows == $limit): ?>
            <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
</body>

</html>