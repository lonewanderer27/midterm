<?php
include 'db.php';
global $cn, $sessions;

// Function to fetch a single value from the database
function fetchSingleValue($query): float
{
    $result = $GLOBALS['cn']->query($query)->fetch_row();
    return ($result !== null) ? $result[0] : 0;
}

// Function to calculate the median from an array of grades
function calculateMedian($grades_array): float
{
    $length = count($grades_array);
    return ($length % 2 === 0)
        ? ($grades_array[($length / 2) - 1] + $grades_array[$length / 2]) / 2
        : $grades_array[(int)($length / 2)];
}

$mean = fetchSingleValue("SELECT AVG(Grade) FROM grades");
$mode = fetchSingleValue("SELECT Grade FROM grades GROUP BY Grade ORDER BY COUNT(*) DESC LIMIT 1");

$grades = $cn->query("SELECT Grade FROM grades");
$grades_array = [];
while ($row = $grades->fetch_assoc()) {
    $grades_array[] = $row['Grade'];
}
sort($grades_array);

$median = calculateMedian($grades_array);

$average_score = fetchSingleValue("SELECT AVG(Grade) FROM grades");
$highest_score = fetchSingleValue("SELECT MAX(Grade) FROM grades");
$lowest_score = fetchSingleValue("SELECT MIN(Grade) FROM grades");
?>

<?php if ($sessions != 0): ?>
    <div class="my-5">
        <h2>Statistics for all sections</h2>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody class="table-group-divider">
            <tr>
                <td>
                    Highest Exam Grade (All Sections)
                </td>
                <td>
                    <?= $highest_score; ?>%
                </td>
            </tr>
            <tr>
                <td>
                    Lowest Exam Grade (All Sections)
                </td>
                <td>
                    <?= $lowest_score; ?>%
                </td>
            </tr>
            <tr>
                <td>
                    Average Exam Grade (All Sections)
                </td>
                <td>
                    <?= round($average_score, 2); ?>%
                </td>
            </tr>
            <tr>
                <td>
                    Median (Most Frequent Grade)
                </td>
                <td>
                    <?= $median; ?>%
                </td>
            </tr>
            <tr>
                <td>
                    Mode (Middle Score)
                </td>
                <td>
                    <?= $mode; ?>%
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>
