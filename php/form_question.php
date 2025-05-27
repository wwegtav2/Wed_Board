<?php
session_start();

if (!isset($_SESSION['idphone'])) {
    header("Location: ../login.html");
    exit();
}
$idphone = $_SESSION['idphone'];

$link = mysqli_connect("localhost", "root", "", "nom");
if (!$link) {
    die("ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ กรุณาติดต่อผู้ดูแลระบบ");
}

mysqli_set_charset($link, "utf8");

$sql = "SELECT name, namesur FROM non_it WHERE idphone = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "s", $idphone);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$displayName = ($row = mysqli_fetch_assoc($result)) ?
    htmlspecialchars($row['name'] . ' ' . $row['namesur']) : "ไม่พบข้อมูลผู้ใช้";


mysqli_stmt_close($stmt);
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thread</title>
    <link rel="stylesheet" href="/css/form_question.css">
    <link rel="icon" href="/imgs/ino3.png" />
</head>

<body>
    <nav>
        <div class="parent">
            <div class="div1">
                <ul>
                    <li><a href="form_question_two.php">
                            <button class="btn-animated">
                                <span class="btn-text">ตั้งคำถามกัน</span>
                                <span class="btn-icon">
                                    <svg
                                        viewBox="0 0 24 24"
                                        height="24"
                                        width="24"
                                        xmlns="http://www.w3.org/2000/svg"></svg>
                                    <span class="btn-icon-symbol">+</span>
                                </span>
                            </button>
                        </a></li>
                    <li><a href="show_question.php"><button class="forum-btn-animated">
                                <span class="forum-btn-label">กระทู้ทั้งหมด</span>
                                <span class="forum-btn-icon">
                                    <svg
                                        viewBox="0 0 24 24"
                                        height="24"
                                        width="24"
                                        xmlns="http://www.w3.org/2000/svg"></svg>
                                    <span class="forum-btn-plus">=</span>
                                </span>
                            </button>
                        </a></li>
                    <li><a href="logout.php"><button class="noselect"><span class="text">ออกจากระบบ</span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path>
                                    </svg></span></button></a></li>
                </ul>
            </div>
            <div class="div4">
                <ul>
                    <li class="li_a">
                        <a href="form_question.php">
                            <?php echo '<p class="name_p">' . 'ชื่อผู้ใช้งาน:<br><b>'
                                . htmlspecialchars($displayName) . '</b></p>'; ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="div5">
                <div class="div_h1">
                    <h1 class="typewriter">ยินดีต้อนรับเข้าสู่เว็บกระทู้ของเรา</h1>
                    <p>ที่นี่ไม่ใช่แค่ที่พูดคุย... แต่คือที่รวมพลคนช่างคิด ช่างถาม และช่างแชร์ <br>
                        อยากถามก็ถาม อยากเล่าก็เล่า ไม่มีถูก ไม่มีผิด แค่อย่าเงียบเกินไปก็พอ 😉 <br>
                        เริ่มต้นกระทู้แรกของคุณ แล้วปล่อยไอเดียให้โลดแล่นเลย!</p>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>