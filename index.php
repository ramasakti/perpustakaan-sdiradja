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
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpus SDI RADJA</title>
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
</head>
<style>
    html,
    body {
    padding: 0;
    margin: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen,
        Ubuntu, Cantarell, Fira Sans, Droid Sans, Helvetica Neue, sans-serif;
    }
</style>
<body>
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-sm navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="#">
                    <img src="rj.jpg" width="110" height="32" alt="Perpustakaan SDI RJ" class="navbar-brand-image">
                    </a>
                </h1>
                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-lg-3">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">
                            <span class="nav-link-title">
                                Data Peminjaman
                            </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="isbn.php">
                            <span class="nav-link-title">
                                ISBN
                            </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>
        <div class="page-wrapper">
            <div class="page-header d-print-none">
                <div class="container-xl">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <h2 class="page-title">
                                Data Peminjaman Buku 
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-body">
                <div class="container-xl">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-10">
                                <input class="form-control" type="date" name="tanggal" id="tanggal" value="<?php if (isset($_GET['tanggal'])) { echo $_GET['tanggal']; } ?>">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-dark">Search</button>
                            </div>
                        </div>
                    </form>
                    <?php
                        if (isset($_GET['tanggal'])) {
                    ?>
                        <div class="mt-5">
                            <p>📚📚📚📚📚📚📚📚📚📚</p>
                            <p>Bismillahirrahmanirrahim..</p>
                            <p>Laporan Harian Peminjaman Buku</p>
                            <p>*<?= date_format($tanggalObj, 'l, d F Y'); ?>*</p>
                                <?php
                                    $jenjang = array("1", "2", "3", "4", "5", "6");

                                    foreach ($jenjang as $j) {
                                      echo "<br><h3>JENJANG $j</h3>";
                                  
                                      $kelasJenjang = array_filter($allKelas, function($kelas) use ($j) {
                                        return preg_match("/^KELAS $j/", $kelas);
                                      });
                                  
                                      $jumlahKolom = 4;
                                      $baris = 1;
                                  
                                      echo "<table border='1' cellpadding='5' cellspacing='0'>";
                                      foreach ($kelasJenjang as $kelas) {
                                        if ($baris == 1) {
                                          echo "<tr>";
                                        }
                                  
                                        $kelas2 = $kelas . "2";
                                  
                                        echo "<td>$kelas " . (in_array($kelas, $kelasPeminjam) ? "✅" : "❌") . "</td>";
                                        echo "<td>$kelas2 " . (in_array($kelas2, $kelasPeminjam) ? "✅" : "❌") . "</td>";
                                  
                                        if ($baris % $jumlahKolom == 0) {
                                          echo "</tr>";
                                          $baris = 1;
                                        } else {
                                          $baris++;
                                        }
                                      }
                                  
                                      if ($baris != 1) {
                                        echo "</tr>";
                                      }
                                  
                                      echo "</table>";
                                    }
                                ?>
                            <p class="mt-3">*<?= $jumlahPeminjaman[0] ?> buku terpinjam*</p>
                            <p>Mohon wali kelas menghimbau siswa-siswi nya untuk gemar membaca dan meminjam buku di perpustakaan</p>
                            <p>📚📚📚📚📚📚📚📚📚📚</p>
                        </div>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script>

    </script>
</body>
</html>