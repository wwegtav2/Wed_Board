<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("วิธีการร้องขอไม่ถูกต้อง");
}

$idphone = $_POST['idphone'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($idphone) || empty($password)) {
    echo "<script>alert('กรุณากรอกข้อมูลให้ครบ'); window.history.back();</script>";
    exit;
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = [];
}

if (!isset($_SESSION['login_attempts'][$idphone])) {
    $_SESSION['login_attempts'][$idphone] = 0;
}

if ($_SESSION['login_attempts'][$idphone] >= 3) {
    echo "<script>alert('บัญชีนี้ถูกล็อกชั่วคราวเนื่องจากพยายามเข้าสู่ระบบผิดหลายครั้ง'); window.history.back();</script>";
    exit;
}

$link = mysqli_connect("localhost", "root", "", "nom");
if (!$link) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . mysqli_connect_error());
}

$sql = "SELECT * FROM non_it WHERE idphone = ?";
$stmt = mysqli_prepare($link,  $sql);
mysqli_stmt_bind_param($stmt, "s", $idphone);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);

    if (password_verify($password, $row['password'])) {
        $_SESSION['idphone'] = $row['idphone'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['login_attempts'][$idphone] = 0;
        mysqli_close($link);
        header("Location: form_question.php");
        exit;
    } else {
        $_SESSION['login_attempts'][$idphone]++;
        echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('ไม่มีข้อมูลในระบบ'); window.history.back();</script>";
}

mysqli_stmt_close($stmt);
mysqli_close($link);
exit;

//mysqli_stmt_get_result() คือฟังก์ชันใน PHP ที่ใช้ ดึงผลลัพธ์จาก prepared statement หลังจากที่ได้ทำการ execute แล้ว โดยผลลัพธ์จะถูกเก็บในรูปแบบ result object ซึ่งสามารถใช้ฟังก์ชันอื่นๆ เช่น mysqli_fetch_assoc() หรือ mysqli_fetch_row() เพื่อดึงข้อมูลออกมา

//password_verify() คือฟังก์ชันใน PHP ที่ใช้ ตรวจสอบรหัสผ่าน ว่าตรงกับค่า hash ที่เก็บไว้ในฐานข้อมูลหรือไม่ โดยใช้ฟังก์ชันนี้เพื่อตรวจสอบรหัสผ่านที่ผู้ใช้กรอกเมื่อเข้าสู่ระบบ
