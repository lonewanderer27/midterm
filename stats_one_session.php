<?php
include 'db.php';
include 'constants.php';
global $cn, $ALL, $sessions;

$average_scores = array();
$highest_scores = array();
$lowest_scores = array();

$selected_session = $_GET['filter_session'] ?? $ALL;

// Fetch exam scores for the selected session or all sessions
if ($selected_session == $ALL) {
    $query = "SELECT * FROM grades";
} else {
    $query = "SELECT * FROM grades WHERE Session = '$selected_session'";
}

$result = $cn->query($query);

// Process the results to calculate statistics
while ($row = $result->fetch_assoc()) {
    $section = $row['Section'];
    $grade = $row['Grade'];

    // Calculate average, highest, and lowest scores for each section
    $average_scores[$section][] = $grade;
    $highest_scores[$section] = isset($highest_scores[$section]) ? max($highest_scores[$section], $grade) : $grade;
    $lowest_scores[$section] = isset($lowest_scores[$section]) ? min($lowest_scores[$section], $grade) : $grade;
}

?>

<?php if ($sessions != 0): ?>
    <div class="my-3">
        <h2>
            Statistics Summary for
            <?php if ($selected_session == $ALL): ?>
                all sessions
            <?php else: ?>
                Session <?= $selected_session ?>
            <?php endif; ?>
        </h2>
        <table class="table">
            <thead>
            <tr>
                <th>Section</th>
                <th>Average Grade</th>
                <th>Highest Grade</th>
                <th>Lowest Grade</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Display statistics for each section
            foreach ($average_scores as $section => $grades) {
                $average = round(array_sum($grades) / count($grades), 2);
                echo "<tr>
                        <td>{$section}</td>
                        <td>{$average}%</td>
                        <td>{$highest_scores[$section]}%</td>
                        <td>{$lowest_scores[$section]}%</td>
                      </tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>