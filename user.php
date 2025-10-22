<?php
require_once './helper/connection.php';

$karyawan = [
  ['002025001', 'Andi Saputra', 'Staff IT'],
  ['002025002', 'Rina Kartika', 'Admin HR'],
  ['002025003', 'Budi Santoso', 'Finance Officer'],
  ['002025004', 'Siti Aminah', 'Marketing'],
  ['002025005', 'Dedi Pratama', 'Designer'],
  ['002025006', 'Lina Marlina', 'Staff IT'],
  ['002025007', 'Agus Haryanto', 'Admin HR'],
  ['002025008', 'Fitriani Dewi', 'Finance Officer'],
  ['002025009', 'Joko Susilo', 'Marketing'],
  ['002025010', 'Maya Sari', 'Designer'],
  ['002025011', 'Eko Wahyudi', 'Staff IT'],
  ['002025012', 'Nur Aini', 'Admin HR'],
  ['002025013', 'Hendra Gunawan', 'Finance Officer'],
  ['002025014', 'Yuni Lestari', 'Marketing'],
  ['002025015', 'Rudi Hartono', 'Designer'],
  ['002025016', 'Tina Agustina', 'Staff IT'],
  ['002025017', 'Fajar Nugroho', 'Admin HR'],
  ['002025018', 'Mega Putri', 'Finance Officer'],
  ['002025019', 'Dian Permata', 'Marketing'],
  ['002025020', 'Bayu Saputra', 'Designer'],
  ['002025021', 'Sari Wulandari', 'Staff IT'],
  ['002025022', 'Rama Prasetyo', 'Admin HR'],
  ['002025023', 'Nina Oktaviani', 'Finance Officer'],
  ['002025024', 'Taufik Hidayat', 'Marketing'],
  ['002025025', 'Citra Ayu', 'Designer'],
  ['002025026', 'Dian Sasmita', 'Staff IT'],
  ['002025027', 'Rizky Ramadhan', 'Admin HR'],
  ['002025028', 'Laila Hasanah', 'Finance Officer'],
  ['002025029', 'Yoga Pratama', 'Marketing'],
  ['002025030', 'Novi Anggraini', 'Designer'],
  ['002025031', 'Dimas Aditya', 'Staff IT'],
  ['002025032', 'Sinta Maharani', 'Admin HR'],
  ['002025033', 'Galih Saputra', 'Finance Officer'],
  ['002025034', 'Melati Indah', 'Marketing'],
  ['002025035', 'Reza Fahmi', 'Designer'],
  ['002025036', 'Ayu Lestari', 'Staff IT'],
  ['002025037', 'Fikri Hidayat', 'Admin HR'],
  ['002025038', 'Nadia Putri', 'Finance Officer'],
  ['002025039', 'Rangga Pratama', 'Marketing'],
  ['002025040', 'Dewi Sartika', 'Designer'],
  ['002025041', 'Ilham Maulana', 'Staff IT'],
  ['002025042', 'Yulia Rahma', 'Admin HR'],
  ['002025043', 'Fauzan Hakim', 'Finance Officer'],
  ['002025044', 'Nisa Kamila', 'Marketing'],
  ['002025045', 'Rendy Saputra', 'Designer'],
  ['002025046', 'Vina Oktavia', 'Staff IT'],
  ['002025047', 'Aditya Nugraha', 'Admin HR'],
  ['002025048', 'Salsa Amelia', 'Finance Officer'],
  ['002025049', 'Daffa Pratama', 'Marketing'],
  ['002025050', 'Zahra Aulia', 'Designer']
];

foreach ($karyawan as $index => [$id, $nama, $jabatan]) {
  $alamat = "Jl. Mawar No." . ($index + 1);
  $telp = "0811000" . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
  $status = $index < 25 ? 'Tetap' : 'Kontrak';

  // Insert ke tb_karyawan
  $insertKaryawan = mysqli_query($connection, "
    INSERT INTO tb_karyawan (id_karyawan, nama_lengkap, alamat, no_telp, jabatan_posisi, status)
    VALUES ('$id', '$nama', '$alamat', '$telp', '$jabatan', '$status')
  ");

  // Insert ke login
  $username = mysqli_real_escape_string($connection, $nama);
  $password = password_hash($id, PASSWORD_DEFAULT);
  $role = 'karyawan';

  $insertLogin = mysqli_query($connection, "
    INSERT INTO login (username, password, role)
    VALUES ('$username', '$password', '$role')
  ");
}

echo "✅ 50 data karyawan dan akun login berhasil ditambahkan.";
?>