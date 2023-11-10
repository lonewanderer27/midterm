<?php
include "db.php";
global $cn;

include "constants.php";
global $STUDENT_AMT, $SUBJECT, $SECTION_AMT, $GRADES, $GRADES_TABLE, $all;
global $SELECTED_SESSION;
global $SUBMIT_GRADES;

// fetch student_amt, section_amt & subject from the url
$student_amt = $_GET[$STUDENT_AMT] ?? null;
$subject = $_GET[$SUBJECT] ?? null;
$section_amt = $_GET[$SECTION_AMT] ?? null;

// fetch the number of sessions
$sql = "SELECT MAX(Session) FROM grades;";
$sessions = intval($cn->query($sql)->fetch_row()[0]);

// next session to be added to the db
$next_session = $sessions + 1;

// selected session to be viewed by the user
$selected_session = $_GET[$SELECTED_SESSION] ?? $all;
?>


<!DOCTYPE html>
<html lang="en">
<?php include "head.php" ?>
<body class="container py-3">

<h1>Grading System</h1>

<!-- Generate grade submission text boxes -->
<form action="/" method="GET" class="d-flex my-3">
    <input class="form-control me-2" type="text" name="<?= $SUBJECT ?>" placeholder="Enter subject name"
           value="<?= $subject ?>" required>
    <input class="form-control me-2" min="1" type="number" name="<?= $SECTION_AMT ?>"
           placeholder="Enter number of sections"
           value="<?= $section_amt ?>" required>
    <input class="form-control me-2" min="1" type="number" name="<?= $STUDENT_AMT ?>"
           placeholder="Enter number of students per section"
           value="<?= $student_amt ?>" required>
    <button class="btn btn-primary" type="submit">GENERATE</button>
</form>

<?php include "grade_submission.php" ?>
<?php include "grade_table_viewer.php" ?>

<?php include 'stats_one_session.php' ?>
<?php include "stats_all_sections.php" ?>
</body>
</html>