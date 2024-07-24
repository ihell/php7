<?php
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "php_dasar");

// Periksa apakah koneksi berhasil
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function tambah($data) {
    global $conn;

    $nrp = mysqli_real_escape_string($conn, htmlspecialchars($data["nrp"]));
    $nama = mysqli_real_escape_string($conn, htmlspecialchars($data["nama"]));
    $email = mysqli_real_escape_string($conn, htmlspecialchars($data["email"]));
    $jurusan = mysqli_real_escape_string($conn, htmlspecialchars($data["jurusan"]));
    $gambar = mysqli_real_escape_string($conn, htmlspecialchars($data["gambar"]));

    $query = "INSERT INTO mahasiswa 
              VALUES
              ('', '$nrp', '$nama', '$email', '$jurusan', '$gambar')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function hapus($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");
    return mysqli_affected_rows($conn);
}

function ubah($data) {
    global $conn;

    $id = $data["id"];
    $nrp = mysqli_real_escape_string($conn, htmlspecialchars($data["nrp"]));
    $nama = mysqli_real_escape_string($conn, htmlspecialchars($data["nama"]));
    $email = mysqli_real_escape_string($conn, htmlspecialchars($data["email"]));
    $jurusan = mysqli_real_escape_string($conn, htmlspecialchars($data["jurusan"]));
    $gambar = mysqli_real_escape_string($conn, htmlspecialchars($data["gambar"]));

    $query = "UPDATE mahasiswa SET
              nrp = '$nrp',
              nama = '$nama',
              email = '$email',
              jurusan = '$jurusan',
              gambar = '$gambar'
              WHERE id = $id";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}
?>