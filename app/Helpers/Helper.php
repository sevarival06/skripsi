<?php
// app/helpers.php

if (!function_exists('bulan')) {
    function bulan($bulan) {
        $bulanList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        return $bulanList[$bulan] ?? '';
    }

    if (!function_exists('getDropdownList')) {
        function getDropdownList($table, $columns)
        {
            // Misalnya, mengambil data dari tabel 'akun' dengan kolom 'no_reff' dan 'nama_reff'
            return \DB::table($table)->pluck($columns[1], $columns[0])->toArray();
        }
    }
}