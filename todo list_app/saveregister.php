<?php

include 'koneksi.php'; 


$username = $_POST['username'];


$password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

 dienkripsi
$query = "INSERT INTO user (username, password) VALUES ('$username', '$password')";


$result = mysqli_query($koneksi, $query);


if($result){

    header("Location: login.php?pesan=daftar_sukses");
}else{

    echo "Registrasi gagal: " . mysqli_error($koneksi);
}
?>