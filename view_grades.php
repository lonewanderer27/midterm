<?php
include 'config.php';
global $ALL, $sessions, $GRADES_TABLE, $GRADES, $FILTER_SESSION, $cn, $DELETE;

function handleDelete($filter)
{
    global $cn, $ALL;
    $sql = ($filter != $ALL) ? "DELETE FROM grades WHERE Session=$filter" : "DELETE FROM grades";
    $cn->query($sql);
    header("location:index.php");
}

function getFilteredGrades($filter)
{
    global $ALL, $cn;
    $sql = ($filter != $ALL) ? "SELECT * FROM grades WHERE Session=$filter" : "SELECT * FROM grades";
    return $cn->query($sql);
}

?>

<?php
$filter = $_GET[$FILTER_SESSION] ?? $ALL;

if (isset($_GET[$DELETE])) {
    handleDelete($filter);
}

$rs = getFilteredGrades($filter);
?>

<?php if ($sessions != 0): ?>
    <div class="d-flex my-3">
        <!-- Dropdown for selecting Session -->
        <form action="/" method="GET">
            <label>Session: </label>
            <label>
                <select class="form-select" name="<?= $FILTER_SESSION ?>">
                    <?php if ($filter == $ALL): ?>
                        <option name="<?= $ALL ?>" value="<?= $ALL ?>" selected>All Sessions</option>
                    <?php else: ?>
                        <option name="<?= $ALL ?>" value="<?= $ALL ?>">All Sessions</option>
                    <?php endif; ?>
                    <?php for ($k = 1; $k <= $sessions; $k++): ?>
                        <?php if ($k == $filter): ?>
                            <option name="<?= $k ?>" value="<?= $k ?>" selected><?= $k ?></option>
                        <?php else: ?>
                            <option name="<?= $k ?>" value="<?= $k ?>"><?= $k ?></option>
                        <?php endif; ?>
                    <?php endfor ?>
                </select>
            </label>
            <button class="btn btn-primary" type="submit">Filter</button>
        </form>
        <form action="/" method="GET" class="ms-2">
            <input name="delete" value="true" style="display: none">
            <input name="filter_session" value="<?= $filter ?>" style="display: none">
            <button class="btn btn-danger" type="submit">
                <?php if ($filter == $ALL): ?>
                    Delete all sessions
                <?php else: ?>
                    Delete session <?= $filter ?>
                <?php endif; ?>
            </button>
        </form>
    </div>

    <!-- Grade Table -->
    <table class="table" id="<?= $GRADES_TABLE ?>">
        <thead>
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Section</th>
            <th>Subject</th>
            <th>Grade</th>
            <th>Session</th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 1 ?>
        <?php while ($row = $rs->fetch_assoc()): ?>
            <tr>
                <td><?= $count ?></td>
                <td><?= $row['Name'] ?></td>
                <td><?= $row['Section'] ?></td>
                <td><?= $row['Subject'] ?></td>
                <td><?= $row['Grade'] ?>%</td>
                <td><?= $row['Session'] ?></td>
            </tr>
            <?php $count++ ?>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>
