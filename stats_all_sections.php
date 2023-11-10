<?php
include 'db.php';
include 'constants.php';
global $cn, $sessions;

$mean = $cn->query("SELECT AVG(Grade) FROM grades")->fetch_row()[0] ?? 0;
$mode = $cn->query("SELECT Grade FROM grades GROUP BY Grade ORDER BY COUNT(*) DESC LIMIT 1")->fetch_row()[0] ?? 0;

$grades = $cn->query("SELECT Grade FROM grades");
$grades_array[] = [];
while ($row = $grades->fetch_assoc()) {
    $grades_array[] = $row['Grade'];
}
sort($grades_array);
$length = count($grades_array);
$median = ($length % 2 === 0)
    ? ($grades_array[($length / 2) - 1] + $grades_array[$length / 2]) / 2
    : $grades_array[(int)($length / 2)];

$average_score = $cn->query("SELECT AVG(Grade) FROM grades")->fetch_row()[0] ?? 0;
$highest_score = $cn->query("SELECT MAX(Grade) FROM grades")->fetch_row()[0] ?? 0;
$lowest_score = $cn->query("SELECT MIN(Grade) FROM grades")->fetch_row()[0] ?? 0;
?>

<?php if ($sessions != 0): ?>
    <div class="my-3">
        <h2>Statistics Summary for all sections</h2>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Highest Exam Grade (All Sections)
                </td>
                <td>
                    <?php echo $highest_score; ?>%
                </td>
            </tr>
            <tr>
                <td>
                    Lowest Exam Grade (All Sections)
                </td>
                <td>
                    <?php echo $lowest_score; ?>%
                </td>
            </tr>
            <tr>
                <td>
                    Average Exam Grade (All Sections)
                </td>
                <td>
                    <?php echo round($average_score, 2); ?>%
                </td>
            </tr>
            <tr>
                <td>
                    Median (Most Frequent Grade)
                </td>
                <td>
                    <?php echo $median; ?>%
                </td>
            </tr>
            <tr>
                <td>
                    Mode (Middle Score)
                </td>
                <td>
                    <?php echo $mode; ?>%
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>