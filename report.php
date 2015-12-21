<?php
    session_start();
    include "config/connect.php";
	include 'config/function.php';
	include 'config/config.php';
    require('class/fpdf.php');
    
	
	$query = yposSQL('SHOW','ypos_barang a, ypos_pembeliandtl b, ypos_pembelian c','a.nama_barang, b.kdPembelian, qty_beli, b.harga_beli, total, tgl_beli','kdbarang=kd_barang && b.kdPembelian=c.kdPembelian','tgl_beli');
    //$db_query = mysql_query($query) or die("Query gagal");
    //Variabel untuk iterasi
    $i = 0;
    //Mengambil nilai dari query database
    while($data=$query->fetch_row())
    {
        $cell[$i][0] = $data[0];
        $cell[$i][1] = $data[1];
        $cell[$i][2] = $data[2];
        $cell[$i][3] = $data[3];
		$cell[$i][4] = $data[4];
        $i++;
    }
    //memulai pengaturan output PDF
    class PDF extends FPDF
    {
        //untuk pengaturan header halaman
        function Header()
        {
            //Pengaturan Font Header
            $this->SetFont('Times','B',14); //jenis font : Times New Romans, Bold, ukuran 14
            //untuk warna background Header
            $this->SetFillColor(255,255,255);
            //untuk warna text
            $this->SetTextColor(0,0,0);
            //Menampilkan tulisan di halaman
            $this->Cell(20,1,'Laporan Pembelian','TBLR',0,'C',1); //TBLR (untuk garis)=> B = Bottom,
            // L = Left, R = Right
            //untuk garis, C = center
        }
    }
    //pengaturan ukuran kertas P = Portrait
    $pdf = new PDF('P','cm','A4');
    $pdf->Open();
    $pdf->AddPage();
    //Ln() = untuk pindah baris
    $pdf->Ln();
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(1,1,'No','LRTB',0,'C');
    $pdf->Cell(3,1,'Kode Jual','LRTB',0,'C');
    $pdf->Cell(4,1,'Produk','LRTB',0,'C');
    $pdf->Cell(5,1,'Qty','LRTB',0,'C');
    $pdf->Cell(6,1,'Harga','LRTB',0,'C');
	$pdf->Cell(7,1,'Total','LRTB',0,'C');
    $pdf->Ln();
    $pdf->SetFont('Times',"",10);
    for($j=0;$j<$i;$j++)
    {
        //menampilkan data dari hasil query database
        $pdf->Cell(1,1,$j+1,'LBTR',0,'C');
        $pdf->Cell(3,1,$cell[$j][1],'LBTR',0,'C');
        $pdf->Cell(4,1,$cell[$j][0],'LBTR',0,'C');
        $pdf->Cell(5,1,$cell[$j][2],'LBTR',0,'C');
        $pdf->Cell(6,1,$cell[$j][3],'LBTR',0,'C');
		$pdf->Cell(7,1,$cell[$j][4],'LBTR',0,'C');
        $pdf->Ln();
    }
    //menampilkan output berupa halaman PDF
    $pdf->Output();
?>