<?php
setlocale(LC_TIME, 'id_ID');
date_default_timezone_set('Asia/Jakarta');
include 'koneksi.php';

if (isset($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];
    $tanggalObj = date_create($tanggal);

    $dataJumlahPeminjaman = mysqli_query($koneksi, "SELECT COUNT(anggota.`No Anggota`) AS jumlah_peminjaman FROM sir_bk_u JOIN anggota ON anggota.`No Anggota` = sir_bk_u.`No Anggota` WHERE sir_bk_u.`Tgl Pinjam` = '$tanggal'");
    $jumlahPeminjaman = mysqli_fetch_array($dataJumlahPeminjaman);
    
    $dataKelas = mysqli_query($koneksi, "SELECT DISTINCT organisasi.NAMA, organisasi.KODE
    FROM organisasi JOIN anggota ON anggota.Organisasi = organisasi.KODE
    WHERE organisasi.NAMA LIKE '%KELAS%'
    ORDER BY organisasi.KODE;
    ");
    $allKelas = array();

    while ($data = mysqli_fetch_array($dataKelas)) {
        $allKelas[] = $data['NAMA'];
    }

    $dataKelasPeminjam = mysqli_query($koneksi, "SELECT DISTINCT anggota.Organisasi AS kelas FROM sir_bk_u JOIN anggota ON anggota.`No Anggota` = sir_bk_u.`No Anggota` WHERE sir_bk_u.`Tgl Pinjam` = '$tanggal' ORDER BY anggota.Organisasi");
    $kelasPeminjam = array();

    while ($kelas = mysqli_fetch_array($dataKelasPeminjam)) {
        $kelasPeminjam[] = $kelas['kelas'];
    }

    header("Content-Type: application/json");
    echo json_encode([
        "kelas_peminjam" => $kelasPeminjam,
        "all_kelas" => $allKelas,
        "jumlah_peminjaman" => $jumlahPeminjaman["jumlah_peminjaman"]
    ], JSON_PRETTY_PRINT);
    exit();
}else{
    header("Content-Type: application/json");
    echo json_encode([
        "kelas_peminjam" => null,
        "all_kelas" => null,
        "jumlah_peminjaman" => null
    ], JSON_PRETTY_PRINT);
    exit();
}