$(function() {
    // Hilangkan tombol cari
    $('#tombol-cari').hide();
    
    // Event ketika keyword ditulis
    $('#keyword').on('keyup', function() {
        // munculkan icon loading
        $('.loader'),show();
        $('#container').load('ajax/mahasiswa.php?keyword=' + $('#keyword').val());
    });
});
