<?php
global $cn;
include "db.php";

// Constants
$STUDENT_AMT = "student_amt";
$SUBJECT = "subject";
$SECTION_AMT = "section_amt";
$NEXT_SESSION = "next_session";
$SELECTED_SESSION = "selected_session";
$GRADES_TABLE = "#grades_table";

// Forms tags
$SUBMIT_GRADES = "submit_grades";
$FILTER_SESSION = "filter_session";
$ALL = "all";
$DELETE = "delete";

// Fetch inputs from the URL
$student_amt = $_GET[$STUDENT_AMT] ?? null;
$subject = $_GET[$SUBJECT] ?? null;
$section_amt = $_GET[$SECTION_AMT] ?? null;

// Fetch the number of sessions
$sql = "SELECT MAX(Session) FROM grades;";
$sessions = intval($cn->query($sql)->fetch_row()[0]);

// Next session to be added to the database
$next_session = $sessions + 1;

// Selected session to be viewed by the user
$selected_session = $_GET[$SELECTED_SESSION] ?? $ALL;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Grades Stats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css"/>
</head>
<body class="container py-3 d-flex align-content-center flex-column">

<div class="row">
    <div class="col-6">
        <!-- Generate grade submission text boxes -->
        <form action="/" method="GET" class="d-flex flex-column my-3">
            <div class="input-group mb-3">
                <span class="input-group-text">Subject name</span>
                <input class="form-control" type="text" name="<?= $SUBJECT ?>"
                       value="<?= $subject ?>" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Number of sections</span>
                <input class="form-control" min="1" type="number" name="<?= $SECTION_AMT ?>"
                       value="<?= $section_amt ?>" required>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Number of students per section</span>
                <input class="form-control" min="1" type="number" name="<?= $STUDENT_AMT ?>"
                       value="<?= $student_amt ?>" required>
            </div>
            <div class="d-flex justify-content-right">
                <button class="btn btn-dark" type="submit">GENERATE INPUTS</button>
            </div>
        </form>
    </div>
    <div class="col-6">
        <?php include "submit_grades.php" ?>
    </div>
</div>


<?php if ($sessions != 0): ?>
    <?php include 'get_grades.php' ?>
    <div class="row">
        <div class="col-6">
            <?php include "stats_sections.php"; ?>
        </div>
        <div class="col-6">
            <?php include "stats_session.php"; ?>
        </div>
    </div>
<?php endif; ?>


<!-- Script loading -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script>
    $(document).ready(() => {
        // Initialize jQuery DataTable
        $('<?= $GRADES_TABLE ?>').DataTable();
    })
</script>

</body>
</html>
