// Ambil elemen-elemen yang dibutuhkan
var keyword = document.getElementById('keyword');
var tombolCari = document.getElementById('tombol-cari');
var container = document.getElementById('container');

// Tambahkan event ketika keyword ditulis
keyword.addEventListener('keyup', function() {

    var xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function() {
        if( xhr.readyState == 4 && 200 ) {
            container.innerHTML = xhr.responseText;
        }
    }

    xhr.open('GET', 'ajax/mahasiswa.php?keyword=' + keyword.value, true);
    xhr.send();
});
