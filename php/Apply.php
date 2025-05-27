<?php
if (!isset($_POST['send'])) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Applied</title>
        <link rel="stylesheet" href="/css/login_create.css">
        <link rel="icon" href="/imgs/ino3.png" />
    </head>

    <body>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="container"> <br>
                <h2 class="typewriter">สร้างบัญชีใหม่</h2>
                <div class="Name_Surname">
                    ชื่อ: <input class="na_me" type="text" name="name" required>
                    นามสกุล: <input class="sur_name" type="text" name="namesur" required> <br>
                </div>
                <div class="form-row">
                    <div class="Sex">
                        เพศ:
                        <select class="sex_gender" name="gender" required>
                            <option value="ไม่ระบุ">ไม่ระบุเพศ</option>
                            <option value="ชาย">ชาย</option>
                            <option value="หญิง">หญิง</option>
                        </select> <br>
                    </div>
                    <div class="Birth">
                        วันเกิด: <input class="date_birth" type="date" name="birth" required>
                    </div>
                </div>
                <div class="Address">
                    ที่อยู่: <input class="text_address" type="text" name="address" required>
                </div>
                <div class="Telephone">
                    เบอร์โทรศัพท์: <input class="telephone_text" type="text" name="idphone" pattern="[0-9]{10}" title="กรุณากรอกเบอร์โทร 10 หลัก" required maxlength="10">
                </div>
                <div class="password_num">
                    รหัสผ่าน: <input class="password_pass" type="password" name="password" minlength="8" required>
                </div>
                <div class="lion">
                    <p>ฉันมีแล้วบัญชี <a class="a_lion" href="../login.html">สมัครแล้ว.</a></p>
                </div>
                <div class="_num">
                    <div class="Apply">
                        <button class="apply_send" name="send" type="submit">สมัคร</button>
                    </div>
                    <div class="Cancel">
                        <input class="apply_Cancel" type="reset" name="cancel" value="ยกเลิก">
                    </div>
                </div>
            </div>
        </form>
    </body>

    </html>
<?php
} else {
    $name = trim($_POST['name']);
    $namesur = trim($_POST['namesur']);
    $idphone = trim($_POST['idphone']);
    $gender = $_POST['gender'];
    $birth = $_POST['birth'];
    $address = trim($_POST['address']);
    $password = $_POST['password'];

    if (empty($idphone) || empty($password) || empty($name) || empty($namesur) || empty($gender) || empty($birth) || empty($address)) {
        die("<script>alert('กรุณากรอกข้อมูลให้ครบ'); window.history.back();</script>");
    }

    if (!preg_match('/^[0-9]{10}$/', $idphone)) {
        die("<script>alert('กรุณากรอกเบอร์โทร 10 หลักให้ถูกต้อง'); window.history.back();</script>");
    }

    if (strlen($password) < 8) {
        die("<script>alert('รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร'); window.history.back();</script>");
    }

    $link = mysqli_connect("localhost", "root", "", "nom");
    if (!$link) {
        die("<script>alert('ไม่สามารถเชื่อมต่อฐานข้อมูลได้'); window.history.back();</script>");
    }

    mysqli_set_charset($link, "utf8");

    $check = mysqli_prepare($link, "SELECT idphone FROM non_it WHERE idphone = ?");
    mysqli_stmt_bind_param($check, "s", $idphone);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        mysqli_stmt_close($check);
        mysqli_close($link);
        die("<script>alert('เบอร์โทรนี้ถูกใช้ไปแล้ว'); window.history.back();</script>");
    }
    mysqli_stmt_close($check);

    $hash_pass = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $link,
        "INSERT INTO non_it (name, namesur, idphone, gender, birth, address, password) 
         VALUES (?, ?, ?, ?, ?, ?, ?)" // ? ไม่ระบุค่า
    );                                              //String
    mysqli_stmt_bind_param($stmt, "sssssss", $name, $namesur, $idphone, $gender, $birth, $address, $hash_pass);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header("Location: ../login.html");
        exit;
    } else {
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        die("<script>alert('เกิดข้อผิดพลาดในการสมัครสมาชิก'); window.history.back();</script>");
    }
}

//mysqli_stmt_execute() คือฟังก์ชันใน PHP ที่ใช้ รันคำสั่ง SQL ที่ได้เตรียมไว้และผูกค่าตัวแปรเรียบร้อยแล้ว ด้วย MySQLi Prepared Statement

// window.history.back() คือคำสั่ง JavaScriptที่ใช้กลับไปยังหน้าที่ก่อนหน้าในประวัติการเยี่ยมชมเว็บไซต์

//<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ใช้เพื่อแสดง URL ของไฟล์ PHP ปัจจุบัน อย่างปลอดภัย โดยป้องกัน XSS (Cross-Site Scripting)

//mysqli_prepare() คือฟังก์ชันใน PHP ที่ใช้ เตรียมคำสั่ง SQL เพื่อรันในฐานข้อมูล MySQLi

//mysqli_stmt_bind_param() คือฟังก์ชันใน PHP ที่ใช้ ผูกค่าตัวแปรกับตัวแปรแทน (?) ใน SQL ที่เตรียมไว้ด้วย mysqli_prepare() โดยใช้ MySQLi_Prepared Statement

//mysqli_stmt_store_result() คือฟังก์ชันใน PHP ที่ใช้ เก็บผลลัพธ์ทั้งหมดของคำสั่ง SELECT ไว้ในหน่วยความจำ (buffer) หลังจากใช้ mysqli_stmt_execute() เพื่อให้สามารถใช้งานเช่น mysqli_stmt_num_rows() ได้

//mysqli_stmt_num_rows() คือฟังก์ชันใน PHP ที่ใช้ นับจำนวนแถวของผลลัพธ์ที่ได้จาก SELECT เมื่อใช้ร่วมกับ mysqli_prepare() (prepared statement)

//mysqli_stmt_close() คือฟังก์ชันใน PHP ที่ใช้ ปิด prepared statement หลังจากที่ทำงานเสร็จ เพื่อ ปล่อยหน่วยความจำ และทรัพยากรที่ใช้ไป

//$hash_pass = password_hash($password, PASSWORD_DEFAULT); เป็นฟังก์ชันใน PHP ที่ใช้ แฮช (hash) รหัสผ่าน โดยใช้ อัลกอริธึมการแฮชที่ปลอดภัย เช่น bcrypt ซึ่งสามารถเลือกใช้ได้ผ่าน PASSWORD_DEFAULT

?>