<?php


$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "mysql"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sudokuIds = [];
$sudokuGrid = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sudoku_id'])) {
    $selectedId = intval($_POST['sudoku_id']);
    $stmt = $conn->prepare("SELECT data FROM sudoku WHERE id = ?");
    $stmt->bind_param("i", $selectedId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sudokuGrid = json_decode($row['data'], true);
    }
    $stmt->close();
}


$result = $conn->query("SELECT id FROM sudoku ORDER BY id ASC");
while ($row = $result->fetch_assoc()) {
    $sudokuIds[] = $row['id'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudoku Check</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="matrix">
    <form method="POST">
        <label for="sudoku_id">Select ID:</label>
        <select name="sudoku_id" id="sudoku_id">
            <?php foreach ($sudokuIds as $id): ?>
                <option value="<?php echo $id; ?>"><?php echo $id; ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn" type="submit">Get</button>
    </form>

    <h2>Sudoku</h2>
    <?php if ($sudokuGrid): ?>
        <table>
            <?php for ($row = 0; $row < 9; $row++): ?>
                <tr>
                    <?php for ($col = 0; $col < 9; $col++): ?>
                        <td class="block-<?php echo intdiv($row, 3) * 3 + intdiv($col, 3) + 1; ?>">
                            <?php echo isset($sudokuGrid[$row][$col]) ? $sudokuGrid[$row][$col] : ''; ?>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
    <?php endif; ?>
</div>
</body>
</html>