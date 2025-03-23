<?php
include "koneksi.php"; // Hubungkan ke database

$nama = $email = $nomor = $mobil = $alamat = "";
$namaErr = $emailErr = $nomorErr = $alamatErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $nomor = trim($_POST["nomor"]);
    $mobil = $_POST["mobil"];
    $alamat = trim($_POST["alamat"]);

    if (empty($nama)) $namaErr = "Nama wajib diisi";
    if (empty($email)) $emailErr = "Email wajib diisi";
    if (empty($nomor) || !ctype_digit($nomor)) $nomorErr = "Nomor telepon harus berupa angka";
    if (empty($alamat)) $alamatErr = "Alamat wajib diisi";

    if (!$namaErr && !$emailErr && !$nomorErr && !$alamatErr) {
        $stmt = $conn->prepare("INSERT INTO pembelian (nama, email, nomor, mobil, alamat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama, $email, $nomor, $mobil, $alamat);
        if ($stmt->execute()) {
            echo "<p style='color:green; text-align:center;'>Data berhasil disimpan!</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pembelian Mobil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h2>Form Pembelian Mobil</h2>
    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <div class="form-group">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($nama); ?>">
            <span class="error"><?php echo $namaErr ? "* $namaErr" : ""; ?></span>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <span class="error"><?php echo $emailErr ? "* $emailErr" : ""; ?></span>
        </div>

        <div class="form-group">
            <label for="nomor">Nomor Telepon:</label>
            <input type="text" id="nomor" name="nomor" value="<?php echo htmlspecialchars($nomor); ?>">
            <span class="error"><?php echo $nomorErr ? "* $nomorErr" : ""; ?></span>
        </div>

        <div class="form-group">
            <label for="mobil">Pilih Mobil:</label>
            <select id="mobil" name="mobil">
                <option value="Sedan" <?php echo ($mobil == "Sedan") ? "selected" : ""; ?>>Sedan</option>
                <option value="SUV" <?php echo ($mobil == "SUV") ? "selected" : ""; ?>>SUV</option>
                <option value="Hatchback" <?php echo ($mobil == "Hatchback") ? "selected" : ""; ?>>Hatchback</option>
            </select>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat Pengiriman:</label>
            <textarea id="alamat" name="alamat"><?php echo htmlspecialchars($alamat); ?></textarea>
            <span class="error"><?php echo $alamatErr ? "* $alamatErr" : ""; ?></span>
        </div>

        <div class="button-container">
            <button type="submit">Beli Mobil</button>
        </div>
    </form>
</div>

<div class="container">
    <h3>Data Pembelian:</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor Telepon</th>
                    <th>Mobil</th>
                    <th>Alamat</th>
                    <th>Tanggal Pembelian</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM pembelian ORDER BY tanggal_pembelian DESC");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['nama']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['nomor']}</td>
                            <td>{$row['mobil']}</td>
                            <td>{$row['alamat']}</td>
                            <td>{$row['tanggal_pembelian']}</td>
                          </tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
