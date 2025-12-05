<?php
session_start();
require "../config.php";

if (isset($_POST['login'])) {
    $username = trim(htmlspecialchars($_POST['username']));
    $password = trim(htmlspecialchars($_POST['password']));

    $stmt = $koneksi->prepare("SELECT * FROM user WHERE BINARY username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $cekUser = $stmt->get_result();

    if ($cekUser && $cekUser->num_rows == 1) {
        $row = $cekUser->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['ssLoginLaundry'] = true;
            $_SESSION['ssUserLaundry']  = $row['username'];
            $_SESSION['ssUserId']       = $row['id_user'];
?>
            <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="UTF-8">
                <title>Berhasil</title>
                <link rel="shortcut icon" type="image/png" href="<?= $main_url ?>src/assets/images/logos/Geolaundry-removebg-preview.png" />
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>

            <body>
                <script>
                    Swal.fire({
                        title: 'Login Berhasil!',
                        text: 'Sedang mengalihkan ke dashboard...',
                        icon: 'success',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                            setTimeout(() => {
                                window.location.href = '../index.php';
                            }, 2000);
                        }
                    });
                </script>
            </body>

            </html>
<?php
            exit;
        } else {
            $_SESSION['loginError'] = "Password salah!";
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['loginError'] = "Akun tidak ditemukan!";
        header("Location: index.php");
        exit;
    }
}
