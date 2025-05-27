<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['idphone'])) {
    header("Location: ../login.html");
    exit();
}

$link = mysqli_connect("localhost", "root", "", "nom");
if (!$link) {
    die("ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ กรุณาติดต่อผู้ดูแลระบบ");
}
mysqli_set_charset($link, "utf8");

$idphone = $_SESSION['idphone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ano'])) {
    $ano = intval($_POST['ano']);

    $check = mysqli_prepare($link, "SELECT * FROM answer WHERE ano = ? AND idphone = ?");
    mysqli_stmt_bind_param($check, "is", $ano, $idphone);
    mysqli_stmt_execute($check);
    $result = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($result) == 1) {
        $delete_answer = mysqli_prepare($link, "DELETE FROM answer WHERE ano = ?");
        mysqli_stmt_bind_param($delete_answer, "i", $ano);
        mysqli_stmt_execute($delete_answer);

        if (mysqli_stmt_affected_rows($delete_answer) > 0) {
            header("Location: form_question.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการลบคำตอบ";
        }

        mysqli_stmt_close($delete_answer);
    } else {
        echo "
        <script>
                alert('คุณไม่สามารถลบคำตอบนี้ได้ เนื่องจากคุณไม่ใช่เจ้าของคำตอบ');
                window.location.href = 'show_question.php';
            </script> ";
    }

    mysqli_stmt_close($check);
    mysqli_close($link);
}
