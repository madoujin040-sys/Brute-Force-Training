<?php
$conn = mysqli_connect("localhost", "root", "", "db_cybernusa_hris");
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>