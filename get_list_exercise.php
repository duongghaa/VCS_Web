<?php include "connect_server.php";
include "get_info_user_login.php";

$sql = "SELECT DISTINCT exercise FROM document";
$result = $conn->query($sql);
while ($list_exercise = $result->fetch_assoc()) {
    $exercise_info = $list_exercise['exercise'];
    $exercise = explode(";", $exercise_info);
    $topic = $exercise[0];
    $link_file = $exercise[1];
    ?>
    <!--        Hiện các bài tập đã giao-->

    <div class="show-topic"><?php echo $topic ?>
        <a class="dwn" href='<?php echo $link_file ?>' download>Tải xuống</a></div>
    <!--    Nếu người dùng đang là sinh viên, cho thêm phần upload file bài giải-->
    <?php if ($id == 0) { ?>
        <div class="upload-solution">
            <form action="" method="post" enctype="multipart/form-data">
                Nộp bài giải: <input type="file" name="upload_file_solution" value="">
                <button name="solution_topic" value="<?php echo $exercise_info ?>">Tải lên</button>
            </form>
        </div>
    <?php } ?>

<?php } ?>
    <!--Nếu là giáo viên, cho thêm phần upload thêm bài tập-->
<?php if ($id == 1) { ?>
    <div class="upload-exercise">
        <h1>Thêm bài tập</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="text" name="topic" placeholder="Chủ đề bài tập"></br>
            <input type="file" name="file_exercise" value="">
            <input type="submit" name="up" value="Tải lên">
        </form>
        <?php include "upload_exercise.php"; ?>
    </div>
<?php } ?>
<?php
//Xử lý dữ liệu khi sinh viên nộp bài
if (isset($_POST['solution_topic']) && isset($_FILES['upload_file_solution'])) {
    $exercise_info = $_POST['solution_topic'];
    if ($_FILES['upload_file_solution']['error'] > 0) {
        echo "Upload lỗi rồi!";
    } else {
        $link_upload = $_FILES['upload_file_solution']['tmp_name'];
        $link_save = 'resources/document/solution/' . $_FILES['upload_file_solution']['name'];
        move_uploaded_file($link_upload, $link_save);
        $solution_info = $username . ';' . $link_save;
        $sql_insert = "INSERT INTO `document`(`exercise`, `solution`) VALUES ('$exercise_info','$solution_info')";
        $conn->query($sql_insert);
        header("Location: exercise.php");
    }
}
?>