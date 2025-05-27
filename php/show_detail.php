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

$item = intval($_GET['item']);

function renHTML($strTemp)
{
    return nl2br(htmlspecialchars($strTemp));
}

$sql = "SELECT * FROM question WHERE qno=?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $item);
mysqli_stmt_execute($stmt);
$questionResult = mysqli_stmt_get_result($stmt);
$questionRow = mysqli_fetch_assoc($questionResult);
mysqli_stmt_close($stmt);

$sql = "SELECT * FROM answer WHERE aquestionno=?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $item);
mysqli_stmt_execute($stmt);
$answerResult = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Board</title>
    <link rel="stylesheet" href="/css/form_question3.css">
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
                                    <span class="btn-icon-symbol">+</span>
                                </span>
                            </button>
                        </a></li>
                    <li><a href="show_question.php"><button class="forum-btn-animated">
                                <span class="forum-btn-label">กระทู้ทั้งหมด</span>
                                <span class="forum-btn-icon">
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
                            <p class="name_p">ชื่อผู้ใช้งาน:<br><b><?php echo $displayName; ?></b></p>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="div5">
                <div class="div_h1">
                    <h2>ตอบกระทู้ !!</h2>
                    <div class="div_h2">
                        <h3>คำถาม: <?php echo renHTML($questionRow['qtopic']); ?></h3>
                        <table width="100%" border="1" bgcolor="#E0E0E0" bordercolor="black">
                            <tr>
                                <td>
                                    <h3>รายละเอียด: <?php echo renHTML($questionRow['qdetail']); ?></h3>
                                    <p class="p_db2">ชื่อผู้ใช้ผู้ตั้งคำถาม: <?php echo htmlspecialchars($questionRow['qname']); ?></p>
                                </td>
                            </tr>
                        </table>

                        <br>
                        <?php while ($answerRow = mysqli_fetch_assoc($answerResult)) { ?>
                            <p>คำตอบ <?php $answerRow['ano']; ?></p>
                            <table width="100%" border="1">
                                <tr>
                                    <td>
                                        <?php echo renHTML($answerRow['adetail']); ?>
                                        <p>โดย: <?php echo renHTML($answerRow['aname']); ?></p>

                                        <form method="post" action="delete_answer.php" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบคำตอบนี้?');">
                                            <input type="hidden" name="ano"
                                                value="<?php echo $answerRow['ano']; ?>">
                                            <input class="su_met" type="submit" value="ลบคำตอบ">
                                        </form>
                                    </td>
                                </tr>
                            </table><br>
                        <?php } ?>


                        <form method='post' action='add_answer.php?answerno=<?php echo $item; ?>'>
                            <h3>คำตอบ:</h3>
                            <textarea cols="40" rows="5" name="a_answer" required></textarea><br>
                            <input type="hidden" name="a_name" value="<?php echo $displayName; ?>">
                            <p>ชื่อ: <b><?php echo $displayName; ?></b></p>
                            <input class="su_met" type="submit" value="ส่งคำตอบ">
                            <input class="su_met" type="reset" value="ยกเลิก">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</body>

</html>
<?php mysqli_close($link); ?>