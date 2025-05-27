<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['idphone'])) {
        die("กรุณาเข้าสู่ระบบก่อนตอบคำถาม");
    }

    $idphone = $_SESSION['idphone'];
    $a_name = $_POST['a_name'];
    $a_answer = $_POST['a_answer'];
    $answerno = intval($_GET['answerno']);

    if ($answerno <= 0) {
        die("รหัสคำถามไม่ถูกต้อง");
    }

    $link = mysqli_connect("localhost", "root", "", "nom");
    mysqli_set_charset($link, "utf8");

    if (!$link) {
        die("ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ กรุณาติดต่อผู้ดูแลระบบ");
    }

    $a_answer = mysqli_real_escape_string($link, $a_answer);
    $a_name = mysqli_real_escape_string($link, $a_name);
    $idphone = mysqli_real_escape_string($link, $idphone);

    $sql_check = "SELECT COUNT(*) FROM answer WHERE aquestionno = $answerno AND idphone = '$idphone'";
    $result_check = mysqli_query($link, $sql_check);
    $row_check = mysqli_fetch_row($result_check);

    if ($row_check[0] == 0) {
        $sql = "INSERT INTO answer (aquestionno, adetail, aname, idphone) 
                VALUES ($answerno, '$a_answer', '$a_name', '$idphone')";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $sql = "UPDATE question SET qcount = qcount + 1 WHERE qno = $answerno";
            mysqli_query($link, $sql);

            echo "<script>
                alert('บันทึกคำตอบเรียบร้อย');
                window.location.href = 'show_question.php';
            </script>";
        } else {
            echo "<script>
                alert('บันทึกคำตอบไม่สำเร็จ');
                window.location.href = 'form_question.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('คุณเคยให้คำตอบแล้ว');
            window.location.href = 'show_question.php';
        </script>";
    }
    mysqli_close($link);
}
