<?php
include 'cors.php';

function navbar() {

    $navbar = [
        [
            "section" => null,
            "icon" => null,
            "menu" => [
                [
                    "menu_name" => "Peminjaman",
                    "route" => "/",
                    "submenu" => []
                ],
                [
                    "menu_name" => "ISBN",
                    "route" => "/isbn",
                    "submenu" => []
                ]
            ]
        ],
    ];

    return json_encode($navbar);
}
header("Content-Type: application/json");
echo navbar();