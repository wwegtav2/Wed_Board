<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['idphone'])) {
    header("Location: ../login.html");
    exit();
}
$idphone = $_SESSION['idphone'];

$link = mysqli_connect("localhost", "root", "", "nom");
mysqli_set_charset($link, "utf8");

// ดึงชื่อผู้ใช้
$sql = "SELECT name, namesur FROM non_it WHERE idphone = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $idphone);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$displayName = ($row = mysqli_fetch_assoc($result)) ? htmlspecialchars($row['name'] . ' ' . $row['namesur']) : "ไม่พบข้อมูลผู้ใช้";
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Web Board</title>
    <link rel="stylesheet" href="/css/form_question3.css">
    <link rel="icon" href="/imgs/ino3.png" />
</head>

<body>
    <nav>
        <div class="parent">
            <div class="div1" id="myNavbar">
                <ul>
                    <li><a href="form_question_two.php"><button class="btn-animated"><span class="btn-text">ตั้งคำถามกัน</span><span class="btn-icon"><span class="btn-icon-symbol">+</span></span></button></a></li>
                    <li><a href="show_question.php"><button class="forum-btn-animated"><span class="forum-btn-label">กระทู้ทั้งหมด</span><span class="forum-btn-icon"><span class="forum-btn-plus">=</span></span></button></a></li>
                    <li><a href="logout.php"><button class="noselect"><span class="text">ออกจากระบบ</span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path>
                                    </svg></span></button></a></li>
                </ul>
            </div>
            <div class="div4">
                <ul>
                    <li class="li_a">
                        <a href="form_question.php">
                            <p class="name_p">ชื่อผู้ใช้งาน:<br><b><?= $displayName ?></b></p>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="div5">
                <div class="div_h1">
                    <h2>กระทู้ทั้งหมดที่คนสงสัยกัน ??</h2>
                    <?php
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }

                    $link = mysqli_connect("localhost", "root", "", "nom");
                    mysqli_set_charset($link, "utf8");

                    if (!isset($_SESSION['idphone'])) {
                        echo "กรุณาเข้าสู่ระบบก่อน";
                        exit();
                    }

                    $myIdphone = $_SESSION['idphone'];

                    $sql = "SELECT q.qno, q.qtopic, q.qname, q.idphone, COUNT(a.ano) AS qcount
                    FROM question q
                    LEFT JOIN answer a ON q.qno = a.aquestionno
                    GROUP BY q.qno ORDER BY q.qno DESC";
                    $result = mysqli_query($link, $sql);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<h2 style='text-align: center; margin: 200px 0;'><i>ยังไม่มีคำถามเลยยย</i></h2>";
                    } else {
                        while ($dbarr = mysqli_fetch_assoc($result)) {
                            echo 'กระทู้: <a href="show_detail.php?item=' . $dbarr['qno'] . '">';
                            echo htmlspecialchars($dbarr['qtopic']) . '</a>';
                            echo '<p class="p_db2">';
                            echo 'ชื่อผู้ใช้: ' . htmlspecialchars($dbarr['qname']) . ' [' . $dbarr['qcount'] . ' คำตอบ]';

                            if ((string)$dbarr['idphone'] === (string)$myIdphone) {
                                echo ' | <a href="delete_answer.php?qno=' . $dbarr['qno'] . '" onclick="return confirm(\'คุณแน่ใจว่าต้องการลบ?\')">ลบ</a>';
                            }
                            echo '</p><br>';
                        }
                    }

                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>