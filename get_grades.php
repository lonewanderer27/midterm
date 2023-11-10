<?php
global $sessions;
include 'db.php';
global $ALL, $FILTER_SESSION, $cn, $DELETE;

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

$filter = $_GET[$FILTER_SESSION] ?? $ALL;

if (isset($_GET[$DELETE])) handleDelete($filter);

$rs = getFilteredGrades($filter);
?>

<?php if ($sessions != 0): ?>
    <div class="d-flex my-3">
        <form action="/" method="GET" class="input-group mb-3">
            <label class="input-group-text">Session</label>
            <select class="form-select" name="<?= $FILTER_SESSION ?>">
                <option name="<?= $ALL ?>" value="<?= $ALL ?>" <?= $filter == $ALL ? 'selected' : '' ?>>All Sessions
                </option>
                <?php for ($k = 1; $k <= $sessions; $k++): ?>
                    <option name="<?= $k ?>" value="<?= $k ?>" <?= $k == $filter ? 'selected' : '' ?>><?= $k ?></option>
                <?php endfor ?>
            </select>
            <button class="btn btn-primary" type="submit">Apply Filter</button>
        </form>
        <form action="/" method="GET" class="ms-2">
            <input name="delete" value="true" style="display: none">
            <input name="filter_session" value="<?= $filter ?>" style="display: none">
            <button class="btn btn-danger" type="submit">
                <?= $filter == $ALL ? 'Delete all sessions' : 'Delete session ' . $filter ?>
            </button>
        </form>
    </div>

    <table class="table table-striped table-hover rounded-4" id="<?= $GRADES_TABLE ?>">
        <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Section</th>
            <th>Subject</th>
            <th>Grade</th>
            <th>Session</th>
        </tr>
        </thead>
        <tbody class="table-group-divider">
        <?php $count = 1 ?>
        <?php while ($row = $rs->fetch_assoc()): ?>
            <tr>
                <td><?= $count++ ?></td>
                <td><?= $row['Name'] ?></td>
                <td><?= $row['Section'] ?></td>
                <td><?= $row['Subject'] ?></td>
                <td><?= $row['Grade'] ?>%</td>
                <td><?= $row['Session'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php endif; ?>
