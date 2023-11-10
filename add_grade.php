<?php
include 'config.php';
global $cn, $student_amt, $section_amt, $subject, $next_session;
global $SUBMIT_GRADES, $NEXT_SESSION;

function submitGrades($cn, $section_amt, $student_amt, $subject, $next_session)
{
    if (isset($_POST[$GLOBALS['SUBMIT_GRADES']])) {
        for ($x = 1; $x <= $section_amt; $x++) {
            for ($y = 1; $y <= $student_amt; $y++) {
                $input_name = "sec$x" . "_" . "stud$y";
                $input_val = $_POST[$input_name];

                $stmt = $cn->prepare("INSERT INTO grades (Name, Subject, Section, Grade, Session) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssiii", $input_name, $subject, $x, $input_val, $next_session);
                $stmt->execute();
            }
        }

        header("location:index.php");
    }
}

function renderGradeForm($subject, $section_amt, $student_amt)
{
    global $SUBMIT_GRADES;
    if (isset($subject) && isset($section_amt) && isset($student_amt)): ?>
        <form method="POST" class="my-5">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
                </thead>
                <?php for ($i = 1; $i <= $section_amt; $i++): ?>
                    <?php for ($j = 1; $j <= $student_amt; $j++): ?>
                        <?php $student_name = "sec$i" . "_" . "stud$j" ?>
                        <tr>
                            <td>
                                <span><?= $student_name ?></span>
                            </td>
                            <td>
                                <span><?= $subject ?></span>
                            </td>
                            <td>
                                <div class="input-group">
                                    <span class="input-group-text">Grade for <?= $student_name ?></span>
                                    <input class="form-control" type="number" required />
                                </div>
                            </td>
                        </tr>
                    <?php endfor; ?>
                <?php endfor; ?>
            </table>
            <input class="" name="<?= $SUBMIT_GRADES ?>" style="display: none" value="true">
            <div class="d-grid">
                <button class="btn btn-success" type="submit">SAVE GRADES</button>
            </div>
        </form>

    <?php endif;
}

submitGrades($cn, $section_amt, $student_amt, $subject, $next_session);
?>

<?php
renderGradeForm($subject, $section_amt, $student_amt);
?>
