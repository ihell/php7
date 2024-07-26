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

    // upload gambar
    $gambar = upload();
    if ( !$gambar ) {
        return false;
    }

    $query = "INSERT INTO mahasiswa 
              VALUES
              ('', '$nrp', '$nama', '$email', '$jurusan', '$gambar')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}


function upload() {
    
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFIle = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    // cek apakah tidak ada gambar yang diupload
    if( $error === 4 ) {
        echo "<script>
                alert('pilih gambar terlebih dahulu');
              </script>";
        return false;
    }

    // cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo "<script>
                alert('yang anda upload bukan gambar!');
             </script>";
        return false;
    }

    // cek jika ukurannya terlalu besar
    if( $ukuranFIle > 1000000 ) {
        echo "<script>
                 alert('ukuran gambar terlalu besar!');
              </script>";
        return false;
    }

    // lolos pengecekan, gambar siap diupload
    // generate nama gambar baru
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar; 

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;

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
    $gambarLama = mysqli_real_escape_string($conn, htmlspecialchars($data["gambarLama"]));

    // cek apakah user pilih gambar baru atau tidak
    if( $_FILES['gambar']['error'] === 4 ) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload();
    }

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


function cari($keyword) {
    $query = "SELECT * FROM mahasiswa
                WHERE
              nama LIKE '%$keyword%' OR 
              nrp LIKE '%$keyword%' OR 
              email LIKE '%$keyword%' OR 
              jurusan LIKE '%$keyword%'
            ";
    return query($query);
}


function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // cek username sudah ada atau belum
   $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
   if( mysqli_fetch_assoc($result) ) {
        echo "<script>
                alert('username sudah terdaftar!');
            </script>";
        return false;
   }

    // cek konfirmasi password
    if( $password !== $password2 ) {
        echo "<script>
                alert('konfirmasi password tidak sesuai!');
            </script>";
        return false;
    }

    // enkripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // tambahkan password ke database
    mysqli_query($conn, "INSERT INTO user VALUES('', '$username', '$password')");

    return mysqli_affected_rows($conn);

}


?>