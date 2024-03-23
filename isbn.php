<?php

function http_request($url){
    // persiapkan curl
    $ch = curl_init(); 

    // set url 
    curl_setopt($ch, CURLOPT_URL, $url);
    
    // set user agent    
    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      

    // mengembalikan hasil curl
    return $output;
}

if (isset($_GET['isbn']) && $_GET['isbn']) {
    $isbn = $_GET['isbn'];
    $profile = http_request("https://isbn.perpusnas.go.id/Account/GetBuku?kd1=ISBN&kd2=" . $_GET['isbn'] . "&limit=10&offset=0");

    // ubah string JSON menjadi array
    $profile = json_decode($profile, TRUE);

    if (count($profile['rows']) > 0) {
        $regex = "/\/Date\((?<timestamp>\d+)\)\//";
        $timestamp = preg_match($regex, $profile['rows'][0]['created_date']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISBN Getter</title>
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
                                Get Detail Buku With ISBN API
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-body">
                <div class="container-xl">
                    <form action="" method="get">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="isbn" placeholder="ISBN Code" autofocus autocomplete="off" />
                        </div>
                        <button class="btn btn-dark w-100">Go</button>
                    </form>

                    <?php
                        if (isset($_GET['isbn']) && $_GET['isbn']) {
                    ?>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card my-3">
                                    <div class="card-body">
                                        <?php
                                            if (count($profile['rows']) <= 0) {
                                                echo "<p>Data Buku dengan Kode ISBN " . $_GET['isbn'] . " Tidak Ditemukan!</p>";
                                            }else{
                                        ?>

                                        <p class="mt-3 mb-0">Judul : <?= $profile['rows'][0]['Judul']; ?></p>
                                        <p class="mb-0">ISBN : <?= $profile['kd2']; ?></p>
                                        <p class="mb-0">Penerbit : <?= $profile['rows'][0]['Penerbit']; ?></p>
                                        <p class="mb-0">Pengarang : <?= $profile['rows'][0]['Pengarang']; ?></p>
                                        <p class="mb-3">Tahun : <?= $profile['rows'][0]['Tahun']; ?></p>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <pre>
                            <?php
                                print_r($profile);
                            ?>
                        </pre>
                    <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>