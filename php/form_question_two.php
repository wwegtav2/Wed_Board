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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Web Board</title>
        <link rel="stylesheet" href="/css/form_question2.css">
        <link rel="icon" href="/imgs/ino3.png" />
    </head>

    <body>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
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
                            <h2>สงสัยอะไรเขียนลงไปเลย...!?</h2>
                            <div class="row1">
                                <p>หัวข้อของกระทู้:</p><input class="row_text" type="text" name="topic">
                            </div>
                            <div class="row2">
                                <p>เนื้อหาของกระทู้:</p><textarea name="detail" cols="75" rows="10"></textarea>
                            </div>
                            <div class="button-row">
                                <div class="row3">
                                    <input class="su_met" name="submit1" type="submit" value="ตกลง">
                                </div>
                                <div class="row4">
                                    <input type="reset" class="re_set" name="submit2" value="ยกเลิก">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </form>
    </body>

    </html>
<?php
} else {
    $topic = trim($_POST['topic']);
    $detail = trim($_POST['detail']);

    if (empty($topic) || empty($detail)) {
        echo "<script>
            alert('กรุณากรอกข้อมูลให้ครบ');
            window.history.back();
        </script>";
        exit;
    }

    if (strlen($topic) > 255 || strlen($detail) > 5000) {
        echo "<script>alert('ข้อความยาวเกินไป'); window.history.back();</script>";
        exit;
    }


    $link = mysqli_connect("localhost", "root", "", "nom");
    if (!$link) {
        die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้");
    }

    mysqli_set_charset($link, "utf8");

    $sqlCount = "SELECT COUNT(*) AS total FROM question";
    $result = mysqli_query($link, $sqlCount);
    $row = mysqli_fetch_assoc($result);
    $itemno = $row['total'] + 1;

    $stmt = mysqli_prepare($link, "INSERT INTO question (qno, qtopic, qdetail, qname) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param(
        $stmt,
        "isss",
        $itemno,
        $topic,
        $detail,
        $displayName
    );
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo "<script>
            alert('เพิ่มกระทู้ใหม่ลงฐานข้อมูลแล้ว');
            window.location.href = 'form_question.php';
        </script>";
    } else {
        echo "<script>
            alert('เกิดข้อผิดพลาดในการเพิ่มกระทู้');
            window.location.href = 'form_question_two.php';
        </script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
    exit;
}

?>