<?php
require_once '../helper/connection.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

// Ambil filter dari GET (opsional)
$filter_kategori = isset($_GET['filter_kategori']) ? $_GET['filter_kategori'] : '';
$filter_menu = isset($_GET['filter_menu']) ? $_GET['filter_menu'] : '';

// Query data SES (ganti sesuai kebutuhan, misal tb_ses)
$where = [];
if ($filter_kategori) $where[] = "kategori = '" . mysqli_real_escape_string($connection, $filter_kategori) . "'";
if ($filter_menu) $where[] = "nama_menu = '" . mysqli_real_escape_string($connection, $filter_menu) . "'";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$query = "  SELECT * FROM tb_ses $where_sql
  ORDER BY kategori, nama_menu,
    STR_TO_DATE(periode, '%M %Y')
";
$result = mysqli_query($connection, $query);

$logoPath = '../assets/img/Logo-1.png';
$logoData = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/png;base64,' . $logoData;


// Siapkan HTML untuk PDF
$html = '
<table width="100%" style="margin-bottom:20px;">
  <tr>
    <td colspan="2" style="text-align: center;">
      <img src="' . $logoSrc . '" width="80" style="position: absolute; left: 40px; top: -30px;">
      <h2 style="margin:0;">Laporan Hasil Perhitungan SES CNB</h2>
    </td>
  </tr>
</table>


<table border="1" cellpadding="5" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th>Kategori</th>
      <th>Menu</th>
      <th>Periode</th>
      <th>Ramalan</th>
      <th>Alpha</th>
      <th>Error</th>
    </tr>
  </thead>
  <tbody>
';

if ($result && mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $html .= '<tr>
      <td>' . htmlspecialchars($row['kategori']) . '</td>
      <td>' . htmlspecialchars($row['nama_menu']) . '</td>
      <td>' . htmlspecialchars($row['periode']) . '</td>
      <td>' . number_format($row['ramalan'], 2) . '</td>
      <td>' . htmlspecialchars($row['alpha']) . '</td>
      <td>' . number_format($row['error'], 2) . '</td>
    </tr>';
  }
} else {
  $html .= '<tr><td colspan="6" align="center">Data tidak ditemukan.</td></tr>';
}
$html .= '</tbody></table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('laporan_ses.pdf', ['Attachment' => false]);
exit;