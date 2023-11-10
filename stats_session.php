<?php
include 'config.php';
global $cn, $ALL, $sessions;

function fetchExamScores($selectedSession) {
    global $cn, $ALL;

    $query = ($selectedSession == $ALL) ? "SELECT * FROM grades" : "SELECT * FROM grades WHERE Session = '$selectedSession'";
    $result = $cn->query($query);

    $scores = [];
    while ($row = $result->fetch_assoc()) {
        $section = $row['Section'];
        $grade = $row['Grade'];

        $scores[$section][] = $grade;
    }

    return $scores;
}

function calculateStatistics($scores) {
    $averageScores = [];
    $highestScores = [];
    $lowestScores = [];

    foreach ($scores as $section => $grades) {
        $average = round(array_sum($grades) / count($grades), 2);
        $highest = max($grades);
        $lowest = min($grades);

        $averageScores[$section] = $average;
        $highestScores[$section] = $highest;
        $lowestScores[$section] = $lowest;
    }

    return [
        'average' => $averageScores,
        'highest' => $highestScores,
        'lowest' => $lowestScores,
    ];
}

$selectedSession = $_GET['filter_session'] ?? $ALL;
$scores = fetchExamScores($selectedSession);

if ($sessions != 0): ?>
    <div class="my-5">
        <h2>
            Summary for
            <?= ($selectedSession == $ALL) ? 'all sessions' : "Session $selectedSession" ?>
        </h2>
        <table class="table table-striped table-hover">
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
            $statistics = calculateStatistics($scores);
            foreach ($statistics['average'] as $section => $average): ?>
                <tr>
                    <td><?= $section ?></td>
                    <td><?= $average ?>%</td>
                    <td><?= $statistics['highest'][$section] ?>%</td>
                    <td><?= $statistics['lowest'][$section] ?>%</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
