-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 16 Des 2015 pada 13.37
-- Versi Server: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_mhs`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ypos_AddLvl`(

in lname varchar(35),

in userid varchar(15),

out newID tinyint(3),

OUT error VARCHAR(4)

)
BEGIN

DECLARE proces INT DEFAULT 0;

DECLARE last_id TINYINT(2);

declare modID tinyint(3);

DECLARE c_getModID CURSOR FOR SELECT modulID FROM ypos_modul WHERE modulID != 0 && 1=1;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET proces = 1;

DECLARE CONTINUE HANDLER FOR SQLSTATE '23000' BEGIN END ;

IF EXISTS (SELECT 'x' FROM ypos_level WHERE lvl=lname) THEN

	BEGIN

		SET error = '1000' ;

	END ;

	ELSE -- jika tidak ada, eksekusi

  BEGIN

    INSERT INTO ypos_level SET lvl=lname, aktif='Y', createdBy=userid;

  SET last_id = LAST_INSERT_ID();

		OPEN c_getModID;

		REPEAT

		FETCH c_getModID INTO modID;

		IF NOT proces THEN

	INSERT INTO ypos_grouplvlmdl SET idlevel=last_id, modulID=modID, r='Y', userID=userid;

	END IF;

	UNTIL proces END REPEAT;

	CLOSE c_getModID;

  SET error='';

  select last_id as newID;

  END;

END IF; -- end cek available data

SELECT error;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ypos_modManag`(

IN idlvl tinyint(2),

IN modName varchar(30),

in folder varchar(50),

in aktif varchar(1),

IN users varchar(15),

in menuid tinyint(3),

OUT error varchar(4)

)
BEGIN

DECLARE proces INT DEFAULT 0;

DECLARE j_mods TINYINT(5);

DECLARE mods TINYINT(5);

declare t_lvl tinyint(2);

declare last_id tinyint(3);

DECLARE c_getlvl CURSOR FOR select idlevel from ypos_level where 1=1;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET proces = 1;

DECLARE CONTINUE HANDLER FOR SQLSTATE '23000' BEGIN END ;

if exists (select 'x' from ypos_modul where nama_modul=modName) then

BEGIN

  SET error = '1000' ;

END ;

ELSE -- jika tidak ada, eksekusi 

BEGIN

INSERT into ypos_modul SET nama_modul=modName, modul_folder=folder, aktif=aktif, createdBy=users, menuID=menuId;

set last_id = LAST_INSERT_ID();

		open c_getlvl;

		repeat

		fetch c_getlvl into t_lvl;

		if not proces then

	INSERT INTO ypos_grouplvlmdl SET idlevel=t_lvl, modulID=last_id, userID=users;

	end if;

	until proces end repeat;

	close c_getlvl;

SET error='';

END;

END IF; -- end cek available data

select error;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ypos_trxPembelianDtl`(


  IN kdp VARCHAR (20),


  IN nota VARCHAR (20),


  IN kdsup SMALLINT (1),


  IN userid VARCHAR (15),


  IN tgl DATE,


  IN kdb VARCHAR (5),


  IN q_beli SMALLINT (3),


  IN h_brg DECIMAL (10, 2),


  IN ttl DECIMAL (10, 2),


  OUT error VARCHAR (4)


)
BEGIN


  DECLARE ttl_beli DECIMAL (10, 2) ;


  DECLARE h_beli DECIMAL (10, 2) ;


  DECLARE CONTINUE HANDLER FOR SQLSTATE '23000' 


  BEGIN


  END ;


  -- cek inputan baru atau bukan, jika bukan do it


  IF EXISTS (SELECT 'x' FROM ypos_pembelian WHERE kdPembelian = kdp) THEN 


  BEGIN


  IF EXISTS (SELECT 'x' FROM ypos_pembeliandtl WHERE kdPembelian = kdp && kd_barang = kdb) THEN -- cek barang yg di input, sudah ada apa blm di keranjang


    BEGIN


      SET error = '1001' ;


    END ;


    ELSE


    BEGIN


  INSERT INTO ypos_pembeliandtl SET kdPembelian = kdp,


      kd_barang = kdb,


      qty_beli = q_beli,


      harga_beli = (ttl / q_beli),


      total = ttl ;


  SET ttl_beli = (SELECT DISTINCT SUM(total) AS t_harga FROM ypos_pembeliandtl WHERE kdPembelian = kdp) ;


  SET h_brg = (SELECT harga_beli FROM ypos_barang WHERE kdbarang = kdb) ; -- cek harga barang satuan saat ini


  UPDATE ypos_pembelian SET total_pembelian = ttl_beli WHERE kdPembelian = kdp ; -- update total pembelian


	  


  IF h_brg != (ttl / q_beli) THEN 


  BEGIN


  UPDATE ypos_barang SET harga_beli = (ttl / q_beli) WHERE kdbarang = kdb ;


  END ;


  END IF ; -- end if cek harga barang dan update harga satuan


  SET error = '' ;


  END; 


  END IF; -- end cek item ada/nggak


  END;


  ELSE


  BEGIN


  INSERT INTO ypos_pembelian SET kdPembelian = kdp,


      no_nota = nota,


      kdsup = kdsup,


      userID = userid,


      tgl_input = NOW(),


      tgl_beli = tgl,


      ids = 1 ;


  INSERT INTO ypos_pembeliandtl SET kdPembelian = kdp,


      kd_barang = kdb,


      qty_beli = q_beli,


      harga_beli = (ttl / q_beli),


      total = ttl ;


  SET ttl_beli = (SELECT DISTINCT SUM(total) AS t_harga FROM ypos_pembeliandtl WHERE kdPembelian = kdp) ;


  SET h_brg = (SELECT harga_beli FROM ypos_barang WHERE kdbarang = kdb) ;


	  


  -- cek harga barang satuan saat ini


  UPDATE ypos_pembelian SET total_pembelian = ttl_beli WHERE kdPembelian = kdp ;


  -- update total pembelian


  IF h_brg != (ttl / q_beli) THEN 


    BEGIN


    UPDATE ypos_barang SET harga_beli = (ttl / q_beli) WHERE kdbarang = kdb;


    END ;


  END IF ; -- end if cek harga barang


  SET error = '' ;


  END;


  END IF;


SELECT error;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ypos_trxPembelianDtl_delProd`(


  in idp int(5),


  IN kdp VARCHAR (20),


  OUT error VARCHAR (4)


)
BEGIN


  DECLARE ttl_beli DECIMAL (10, 2) ;


  DECLARE CONTINUE HANDLER FOR SQLSTATE '23000' 


  BEGIN


  END ;


  


  delete from ypos_pembeliandtl where idDtlPembelian=idp && kdPembelian=kdp;


  SET ttl_beli = (SELECT DISTINCT SUM(total) AS t_harga FROM ypos_pembeliandtl WHERE kdPembelian = kdp) ;


  UPDATE ypos_pembelian SET total_pembelian = ttl_beli WHERE kdPembelian = kdp ; -- update total pembelian


  SET error = '' ;


SELECT error;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ypos_trxPenjualanDtl`(


  IN kdp VARCHAR (20),


  IN cust VARCHAR (25),


  IN tgl DATE,


  IN kdb VARCHAR (5),


  IN h_jual DECIMAL (10, 2),


  IN q_jual SMALLINT (3),


  IN userid VARCHAR (15),


  OUT error VARCHAR (4)


)
BEGIN


  DECLARE ttl_h_brg DECIMAL (10, 2) ;


  DECLARE brg_diskon DECIMAL (9, 2) ;


  DECLARE h_beli DECIMAL (10, 2) ;


  DECLARE ttl_harga_jual DECIMAL (10, 2) ;


  DECLARE stok SMALLINT (3) ;


  DECLARE CONTINUE HANDLER FOR SQLSTATE '23000' BEGIN END ;


  SET ttl_h_brg = (h_jual * q_jual) ;


  SET stok = 


  (SELECT 


    a.stok 


  FROM


    ypos_barang a


  WHERE a.kdbarang = kdb);


  -- select stok;


  IF q_jual <= stok 


  THEN -- cek stok yg ada dengan qty jual


  BEGIN


    IF EXISTS 


    (SELECT 


      'x' 


    FROM


      ypos_penjualan 


    WHERE kd_penjualan = kdp) 


    THEN -- cek inputan baru apa bukan, jika buka do it now


    BEGIN


      IF EXISTS 


      (SELECT 


        'x' 


      FROM


        ypos_penjualandtl 


      WHERE kd_penjualan = kdp && kd_barang = kdb) 


      THEN -- cek barang yg di input, sudah ada apa blm di keranjang


      BEGIN


        SET error = '1001' ;


      END ;


      ELSE -- jika tidak ada, eksekusi 


      BEGIN


        SET brg_diskon = 


        (SELECT DISTINCT 


          SUM((harga_jual) - h_jual) 


        FROM


          ypos_barang 


        WHERE kdbarang = kdb) ;


        SET h_beli = 


        (SELECT 


          harga_beli 


        FROM


          ypos_barang 


        WHERE kdbarang = kdb) ;


        INSERT INTO ypos_penjualandtl SET kd_penjualan = kdp,


        kd_barang = kdb,


        harga_jual = h_jual,


        diskon = brg_diskon,


        qty = q_jual,


        harga_beli = h_beli,


        total_harga = ttl_h_brg,


        userID = userid,


        date_lastUpdate = NOW(),


        user_lastUpdate = userid ;


        SET ttl_harga_jual = 


        (SELECT DISTINCT 


          SUM(total_harga) 


        FROM


          ypos_penjualandtl 


        WHERE kd_penjualan = kdp) ;


        UPDATE 


          ypos_penjualan 


        SET


          subtotal = ttl_harga_jual 


        WHERE kd_penjualan = kdp ;


        SET error = '' ;


      END ;


      END IF ;


      -- end cek kd_penjualan dan kd_barang


    END ;


    ELSE 


    BEGIN


      -- jika data baru


      SET brg_diskon = 


      (SELECT DISTINCT 


        SUM((harga_jual) - h_jual) 


      FROM


        ypos_barang 


      WHERE kdbarang = kdb) ;


      SET h_beli = 


      (SELECT 


        harga_beli 


      FROM


        ypos_barang 


      WHERE kdbarang = kdb) ;


      INSERT INTO ypos_penjualan SET kd_penjualan = kdp,


      ids = 1,


      customer = cust,


      tgl_input = NOW(),


      tgl_jual = tgl,


      userID = userid ;


      INSERT INTO ypos_penjualandtl SET kd_penjualan = kdp,


      kd_barang = kdb,


      harga_jual = h_jual,


      diskon = brg_diskon,


      qty = q_jual,


      harga_beli = h_beli,


      total_harga = ttl_h_brg,


      userID = userid,


      date_lastUpdate = NOW(),


      user_lastUpdate = userid ;


      -- Dapetin total harga pembelian


      SET ttl_harga_jual = 


      (SELECT DISTINCT 


        SUM(total_harga) 


      FROM


        ypos_penjualandtl 


      WHERE kd_penjualan = kdp) ;


      UPDATE 


        ypos_penjualan 


      SET


        subtotal = ttl_harga_jual 


      WHERE kd_penjualan = kdp ;


      SET error = '' ;


    END ;


    END IF ;


    -- end cek input baru atau bukan


  END ;


  ELSE


  BEGIN


    SET error = '1002' ;


  END ;


  END IF ;


  -- end cek stok


  SELECT error;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ypos_trxPenjualanDtl_update`(


  IN idp INT (8),


  IN kdp VARCHAR (20),


  IN q_jual SMALLINT (3),


  IN h_jual DECIMAL (10, 2),


  IN t_harga DECIMAL (10, 2),


  OUT error VARCHAR (4)


)
BEGIN


DECLARE stok SMALLINT (3) ;


DECLARE CONTINUE HANDLER FOR SQLSTATE '23000' BEGIN END ;


SET stok = (SELECT 


    a.stok 


  FROM


    ypos_barang a, ypos_penjualandtl b


  WHERE idDtlPenjualan=idp && a.kdbarang = b.kd_barang);


  -- select stok;


  


  IF q_jual <= stok THEN -- cek stok yg ada dengan qty jual


  BEGIN


  UPDATE 


    ypos_penjualandtl 


  SET


    qty = q_jual,


    harga_jual = h_jual,


    total_harga = t_harga 


  WHERE idDtlPenjualan = idp && kd_penjualan = kdp ;


  UPDATE 


    ypos_penjualan 


  SET


    subtotal = 


    (SELECT DISTINCT 


      SUM(total_harga) AS t_harga 


    FROM


      ypos_penjualandtl 


    WHERE ypos_penjualandtl.kd_penjualan = kdp) 


  WHERE kd_penjualan = kdp ;


  SET error = '' ;


  END;


  ELSE


  BEGIN


  SET error = '1002' ;


  END;


  END IF;


  SELECT error ;


END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_barang`
--

CREATE TABLE IF NOT EXISTS `ypos_barang` (
  `kdbarang` varchar(5) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `stok` smallint(3) NOT NULL,
  `lokasi` varchar(20) NOT NULL,
  `gambar` varchar(100) NOT NULL,
  `idkat` int(3) NOT NULL,
  `ids` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_barang`
--

INSERT INTO `ypos_barang` (`kdbarang`, `nama_barang`, `harga_beli`, `harga_jual`, `stok`, `lokasi`, `gambar`, `idkat`, `ids`) VALUES
('B0001', 'Oli mesran', '2.80', '15000.00', 14, 'A1', '', 2, 1),
('B0002', 'Knalpot (Harga 1)', '88000.00', '98000.00', 90, 'C2', '', 8, 1),
('B0003', 'Knalpot', '125000.00', '125000.00', 99, 'C2', '', 8, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_grouplvlmdl`
--

CREATE TABLE IF NOT EXISTS `ypos_grouplvlmdl` (
`idGroupLM` int(8) unsigned NOT NULL,
  `idlevel` tinyint(2) DEFAULT NULL,
  `modulID` tinyint(3) DEFAULT NULL,
  `r` enum('Y','N') NOT NULL DEFAULT 'N',
  `c` varchar(1) NOT NULL DEFAULT 'N',
  `e` varchar(1) NOT NULL DEFAULT 'N',
  `d` varchar(1) NOT NULL DEFAULT 'N',
  `userID` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_grouplvlmdl`
--

INSERT INTO `ypos_grouplvlmdl` (`idGroupLM`, `idlevel`, `modulID`, `r`, `c`, `e`, `d`, `userID`) VALUES
(1, 1, 0, 'Y', 'Y', 'Y', 'Y', 'yuda'),
(2, 1, 1, 'Y', 'Y', 'Y', 'Y', 'CED'),
(3, 1, 2, 'Y', 'Y', 'Y', 'Y', 'CED'),
(4, 1, 3, 'Y', 'Y', 'Y', 'Y', 'CED'),
(5, 1, 4, 'Y', 'Y', 'Y', 'Y', 'CED'),
(6, 1, 5, 'Y', 'Y', 'Y', 'Y', 'CED'),
(7, 1, 6, 'Y', 'Y', 'Y', 'Y', 'CED'),
(8, 1, 7, 'Y', 'Y', 'Y', 'Y', 'CED'),
(9, 1, 8, 'Y', 'Y', 'Y', 'Y', 'CED'),
(10, 1, 15, 'Y', 'Y', 'Y', 'Y', 'CED'),
(180, 18, 1, 'Y', 'Y', 'Y', 'Y', 'owner'),
(181, 18, 2, 'Y', 'Y', 'Y', 'Y', 'owner'),
(182, 18, 3, 'Y', 'Y', 'Y', 'Y', 'owner'),
(183, 18, 4, 'N', 'N', 'N', 'N', 'owner'),
(184, 18, 5, 'Y', 'Y', 'Y', 'Y', 'owner'),
(185, 18, 6, 'Y', 'Y', 'Y', 'Y', 'owner'),
(186, 18, 7, 'Y', 'Y', 'N', 'N', 'owner'),
(187, 18, 8, 'Y', 'N', 'N', 'N', 'owner'),
(188, 18, 15, 'N', 'N', 'N', 'N', 'owner'),
(198, 20, 1, 'Y', 'N', 'N', 'N', 'owner'),
(199, 20, 2, 'Y', 'N', 'N', 'N', 'owner'),
(200, 20, 3, 'Y', 'N', 'N', 'N', 'owner'),
(201, 20, 4, 'Y', 'N', 'N', 'N', 'owner'),
(202, 20, 5, 'Y', 'N', 'N', 'N', 'owner'),
(203, 20, 6, 'Y', 'N', 'N', 'N', 'owner'),
(204, 20, 7, 'Y', 'N', 'N', 'N', 'owner'),
(205, 20, 8, 'Y', 'N', 'N', 'N', 'owner'),
(206, 20, 15, 'Y', 'N', 'N', 'N', 'owner'),
(207, 21, 1, 'Y', 'Y', 'Y', 'Y', 'owner'),
(208, 21, 2, 'Y', 'Y', 'Y', 'Y', 'owner'),
(209, 21, 3, 'Y', 'Y', 'Y', 'Y', 'owner'),
(210, 21, 4, 'Y', 'Y', 'Y', 'Y', 'owner'),
(211, 21, 5, 'Y', 'Y', 'Y', 'Y', 'owner'),
(212, 21, 6, 'Y', 'Y', 'Y', 'Y', 'owner'),
(213, 21, 7, 'Y', 'Y', 'Y', 'Y', 'owner'),
(214, 21, 8, 'Y', 'Y', 'Y', 'Y', 'owner'),
(215, 21, 15, 'Y', 'Y', 'Y', 'Y', 'owner');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_kategori`
--

CREATE TABLE IF NOT EXISTS `ypos_kategori` (
`idkat` tinyint(3) unsigned NOT NULL,
  `ids` tinyint(1) unsigned NOT NULL,
  `nama_kat` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_kategori`
--

INSERT INTO `ypos_kategori` (`idkat`, `ids`, `nama_kat`) VALUES
(2, 1, 'Oli Mesran 0,8'),
(3, 1, 'Baud'),
(4, 1, 'Seher'),
(5, 1, 'Ring'),
(6, 1, 'Ban Motor'),
(8, 1, 'Knalpot');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_level`
--

CREATE TABLE IF NOT EXISTS `ypos_level` (
`idlevel` tinyint(2) NOT NULL,
  `lvl` varchar(35) NOT NULL,
  `aktif` enum('Y','N') NOT NULL,
  `CreatedBy` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_level`
--

INSERT INTO `ypos_level` (`idlevel`, `lvl`, `aktif`, `CreatedBy`) VALUES
(1, 'Super Administrator', 'Y', 'yuda'),
(18, 'Kasir ', 'Y', 'owner'),
(20, 'Kasir 1', 'Y', 'owner'),
(21, 'Administrator', 'Y', 'owner');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_lgnhistories`
--

CREATE TABLE IF NOT EXISTS `ypos_lgnhistories` (
`idLgn` int(8) unsigned NOT NULL,
  `username` varchar(15) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `jam` datetime DEFAULT 0,
  `hostname` varchar(100) NOT NULL,
  `browser` varchar(17) NOT NULL,
  `ket` enum('IN','OUT') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_lgnhistories`
--

INSERT INTO `ypos_lgnhistories` (`idLgn`, `username`, `ip`, `jam`, `hostname`, `browser`, `ket`) VALUES
(1, 'remoxp', '::1', '2015-12-11 21:31:04', 'HANGOVER-PC', 'Chrome', 'IN'),
(2, 'remoxp', '::1', '2015-12-11 21:45:10', 'HANGOVER-PC', 'Chrome', 'OUT'),
(3, 'owner', '::1', '2015-12-11 21:45:13', 'HANGOVER-PC', 'Chrome', 'IN'),
(4, 'remoxp', '::1', '2015-12-11 22:18:50', 'HANGOVER-PC', 'Chrome', 'OUT'),
(5, 'warsito', '::1', '2015-12-11 22:19:08', 'HANGOVER-PC', 'Chrome', 'IN'),
(6, 'warsito', '::1', '2015-12-11 23:25:11', 'HANGOVER-PC', 'Chrome', 'OUT'),
(7, 'goro', '::1', '2015-12-11 23:25:21', 'HANGOVER-PC', 'Chrome', 'IN'),
(8, 'goro', '::1', '2015-12-11 23:25:32', 'HANGOVER-PC', 'Chrome', 'OUT'),
(9, 'owner', '::1', '2015-12-11 23:25:37', 'HANGOVER-PC', 'Chrome', 'IN'),
(10, 'owner', '::1', '2015-12-12 01:22:50', 'HANGOVER-PC', 'Chrome', 'OUT'),
(11, 'owner', '::1', '2015-12-13 13:53:44', 'HANGOVER-PC', 'Chrome', 'IN'),
(12, 'owner', '::1', '2015-12-13 13:58:37', 'HANGOVER-PC', 'Chrome', 'OUT'),
(13, 'goro', '::1', '2015-12-13 13:58:45', 'HANGOVER-PC', 'Chrome', 'IN'),
(14, 'goro', '::1', '2015-12-13 14:00:21', 'HANGOVER-PC', 'Chrome', 'OUT'),
(15, 'owner', '::1', '2015-12-13 14:00:51', 'HANGOVER-PC', 'Chrome', 'IN'),
(16, 'owner', '::1', '2015-12-13 14:02:13', 'HANGOVER-PC', 'Chrome', 'OUT'),
(17, 'warsito', '::1', '2015-12-13 14:02:24', 'HANGOVER-PC', 'Chrome', 'IN'),
(18, 'warsito', '::1', '2015-12-13 14:11:17', 'HANGOVER-PC', 'Chrome', 'OUT'),
(19, 'warsito', '::1', '2015-12-13 14:11:28', 'HANGOVER-PC', 'Chrome', 'IN'),
(20, 'warsito', '::1', '2015-12-13 14:11:38', 'HANGOVER-PC', 'Chrome', 'OUT'),
(21, 'owner', '::1', '2015-12-13 14:11:43', 'HANGOVER-PC', 'Chrome', 'IN'),
(22, 'owner', '::1', '2015-12-13 14:14:39', 'HANGOVER-PC', 'Chrome', 'OUT'),
(23, 'warsito', '::1', '2015-12-13 14:14:48', 'HANGOVER-PC', 'Chrome', 'IN'),
(24, 'warsito', '::1', '2015-12-13 14:15:35', 'HANGOVER-PC', 'Chrome', 'OUT'),
(25, 'owner', '::1', '2015-12-13 14:15:38', 'HANGOVER-PC', 'Chrome', 'IN'),
(26, 'owner', '::1', '2015-12-13 14:37:17', 'HANGOVER-PC', 'Chrome', 'OUT'),
(27, 'warsito', '::1', '2015-12-13 14:37:27', 'HANGOVER-PC', 'Chrome', 'IN'),
(28, 'warsito', '::1', '2015-12-13 14:46:34', 'HANGOVER-PC', 'Chrome', 'OUT'),
(29, 'owner', '::1', '2015-12-13 14:46:38', 'HANGOVER-PC', 'Chrome', 'IN'),
(30, 'owner', '::1', '2015-12-13 14:47:05', 'HANGOVER-PC', 'Chrome', 'OUT'),
(31, 'goro', '::1', '2015-12-13 14:47:15', 'HANGOVER-PC', 'Chrome', 'IN'),
(32, 'goro', '::1', '2015-12-13 15:33:30', 'HANGOVER-PC', 'Chrome', 'OUT'),
(33, 'owner', '::1', '2015-12-13 15:33:35', 'HANGOVER-PC', 'Chrome', 'IN'),
(34, 'owner', '::1', '2015-12-13 15:37:31', 'HANGOVER-PC', 'Chrome', 'OUT'),
(35, 'goro', '::1', '2015-12-13 15:37:42', 'HANGOVER-PC', 'Chrome', 'IN'),
(36, 'goro', '::1', '2015-12-13 15:38:32', 'HANGOVER-PC', 'Chrome', 'OUT'),
(37, 'goro', '::1', '2015-12-13 15:38:51', 'HANGOVER-PC', 'Chrome', 'IN'),
(38, 'goro', '::1', '2015-12-13 15:39:18', 'HANGOVER-PC', 'Chrome', 'OUT'),
(39, 'owner', '::1', '2015-12-13 15:39:21', 'HANGOVER-PC', 'Chrome', 'IN'),
(40, 'owner', '::1', '2015-12-13 15:39:25', 'HANGOVER-PC', 'Chrome', 'OUT'),
(41, 'goro', '::1', '2015-12-13 15:39:32', 'HANGOVER-PC', 'Chrome', 'IN'),
(42, 'goro', '::1', '2015-12-13 15:40:22', 'HANGOVER-PC', 'Chrome', 'OUT'),
(43, 'owner', '::1', '2015-12-13 15:40:30', 'HANGOVER-PC', 'Chrome', 'IN'),
(44, 'owner', '::1', '2015-12-13 15:43:59', 'HANGOVER-PC', 'Chrome', 'OUT'),
(45, 'goro', '::1', '2015-12-13 15:44:06', 'HANGOVER-PC', 'Chrome', 'IN'),
(46, 'goro', '::1', '2015-12-13 15:45:01', 'HANGOVER-PC', 'Chrome', 'OUT'),
(47, 'owner', '::1', '2015-12-13 15:45:03', 'HANGOVER-PC', 'Chrome', 'IN'),
(48, 'owner', '::1', '2015-12-13 15:46:48', 'HANGOVER-PC', 'Chrome', 'OUT'),
(49, 'goro', '::1', '2015-12-13 15:46:55', 'HANGOVER-PC', 'Chrome', 'IN'),
(50, 'goro', '::1', '2015-12-13 15:55:14', 'HANGOVER-PC', 'Chrome', 'OUT'),
(51, 'owner', '::1', '2015-12-13 15:55:19', 'HANGOVER-PC', 'Chrome', 'IN'),
(52, 'owner', '::1', '2015-12-13 16:03:43', 'HANGOVER-PC', 'Chrome', 'OUT'),
(53, 'goro', '::1', '2015-12-13 16:03:52', 'HANGOVER-PC', 'Chrome', 'IN'),
(54, 'goro', '::1', '2015-12-13 16:04:34', 'HANGOVER-PC', 'Chrome', 'OUT'),
(55, 'owner', '::1', '2015-12-13 16:04:37', 'HANGOVER-PC', 'Chrome', 'IN'),
(56, 'owner', '::1', '2015-12-13 16:08:37', 'HANGOVER-PC', 'Chrome', 'OUT'),
(57, 'goro', '::1', '2015-12-13 16:08:53', 'HANGOVER-PC', 'Chrome', 'IN'),
(58, 'goro', '::1', '2015-12-13 16:16:42', 'HANGOVER-PC', 'Chrome', 'OUT'),
(59, 'owner', '::1', '2015-12-13 16:16:58', 'HANGOVER-PC', 'Chrome', 'IN'),
(60, 'owner', '::1', '2015-12-13 16:17:45', 'HANGOVER-PC', 'Chrome', 'OUT'),
(61, 'goro', '::1', '2015-12-13 16:17:52', 'HANGOVER-PC', 'Chrome', 'IN'),
(62, 'goro', '::1', '2015-12-13 16:23:38', 'HANGOVER-PC', 'Chrome', 'OUT'),
(63, 'owner', '::1', '2015-12-13 16:23:41', 'HANGOVER-PC', 'Chrome', 'IN'),
(64, 'owner', '::1', '2015-12-13 16:31:41', 'HANGOVER-PC', 'Chrome', 'OUT'),
(65, 'owner', '::1', '2015-12-13 16:31:44', 'HANGOVER-PC', 'Chrome', 'IN'),
(66, 'owner', '::1', '2015-12-13 16:32:02', 'HANGOVER-PC', 'Chrome', 'OUT'),
(67, 'warsito', '::1', '2015-12-13 16:32:24', 'HANGOVER-PC', 'Chrome', 'IN'),
(68, 'warsito', '::1', '2015-12-13 17:06:19', 'HANGOVER-PC', 'Chrome', 'OUT'),
(69, 'owner', '::1', '2015-12-13 19:20:59', 'HANGOVER-PC', 'Chrome', 'IN'),
(70, 'owner', '::1', '2015-12-13 22:50:07', 'HANGOVER-PC', 'Chrome', 'OUT'),
(71, 'owner', '::1', '2015-12-16 19:21:28', 'HANGOVER-PC', 'Chrome', 'IN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_menu`
--

CREATE TABLE IF NOT EXISTS `ypos_menu` (
`menuID` tinyint(3) NOT NULL,
  `menu` varchar(30) NOT NULL,
  `aktif` enum('Y','N') DEFAULT NULL,
  `sort` tinyint(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_menu`
--

INSERT INTO `ypos_menu` (`menuID`, `menu`, `aktif`, `sort`) VALUES
(1, 'Data Master', 'Y', 2),
(2, 'Transaksi', 'Y', 3),
(3, 'Laporan', 'Y', 4),
(5, 'My Account', 'Y', 1),
(6, 'Settings', 'Y', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_modul`
--

CREATE TABLE IF NOT EXISTS `ypos_modul` (
`modulID` tinyint(3) NOT NULL,
  `nama_modul` varchar(30) NOT NULL,
  `modul_folder` varchar(50) DEFAULT NULL,
  `aktif` enum('Y','N') NOT NULL DEFAULT 'N',
  `createdBy` varchar(15) NOT NULL,
  `menuID` tinyint(3) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_modul`
--

INSERT INTO `ypos_modul` (`modulID`, `nama_modul`, `modul_folder`, `aktif`, `createdBy`, `menuID`) VALUES
(0, 'system', 'system', 'Y', 'yuda', NULL),
(1, 'kategori', 'kategori', 'Y', 'yuda', 1),
(2, 'suplier', 'suplier', 'Y', 'yuda', 1),
(3, 'barang', 'barang', 'Y', 'yuda', 1),
(4, 'user', 'user', 'Y', 'yuda', 1),
(5, 'penjualan', 'trx_penjualan', 'Y', 'yuda', 2),
(6, 'pembelian', 'trx_pembelian', 'Y', 'yuda', 2),
(7, 'all reports', 'laporan', 'Y', 'yuda', 3),
(8, 'profile', 'profile', 'Y', 'yuda', 5),
(15, 'general', 'settings', 'Y', 'yuda', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_paramchild`
--

CREATE TABLE IF NOT EXISTS `ypos_paramchild` (
`idpc` tinyint(5) NOT NULL,
  `idpm` int(3) NOT NULL,
  `child_name` varchar(50) NOT NULL,
  `ket` text,
  `aktif` varchar(1) DEFAULT 'Y'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_paramchild`
--

INSERT INTO `ypos_paramchild` (`idpc`, `idpm`, `child_name`, `ket`, `aktif`) VALUES
(1, 1, 'Y', 'Ya', 'Y'),
(2, 1, 'N', 'Tidak', 'Y'),
(3, 2, 'Pembelian', 'report/pembelian/rpt_pembelian', 'Y'),
(4, 2, 'Penjualan', 'report/penjualan/rpt_penjualan', 'Y'),
(5, 2, 'Stok Barang', 'report/stok/rpt_stok', 'Y'),
(6, 2, 'abc', 'abc', 'N');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_parameter`
--

CREATE TABLE IF NOT EXISTS `ypos_parameter` (
`idpm` int(3) NOT NULL,
  `nama_param` varchar(50) NOT NULL,
  `ket` text,
  `userid` varchar(15) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_parameter`
--

INSERT INTO `ypos_parameter` (`idpm`, `nama_param`, `ket`, `userid`) VALUES
(1, 'stdChoices', 'Pilihan Y/N', ''),
(2, 'RptParam', 'Parameter untuk laporan', 'yuda'),
(3, 'TestParam', 'cuma tester', 'yuda');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_pembelian`
--

CREATE TABLE IF NOT EXISTS `ypos_pembelian` (
  `kdPembelian` varchar(20) NOT NULL,
  `no_nota` varchar(20) NOT NULL,
  `total_pembelian` decimal(10,2) NOT NULL,
  `kdsup` smallint(1) NOT NULL,
  `userID` varchar(15) NOT NULL,
  `tgl_input` datetime NOT NULL DEFAULT 0,
  `tgl_beli` date NOT NULL,
  `ids` tinyint(1) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_pembelian`
--

INSERT INTO `ypos_pembelian` (`kdPembelian`, `no_nota`, `total_pembelian`, `kdsup`, `userID`, `tgl_input`, `tgl_beli`, `ids`) VALUES
('P-mhs201512110000001', '01', '14.00', 1, 'owner', '2015-12-11 23:36:06', '2015-12-11', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_pembeliandtl`
--

CREATE TABLE IF NOT EXISTS `ypos_pembeliandtl` (
`idDtlPembelian` int(5) NOT NULL,
  `kdPembelian` varchar(20) NOT NULL,
  `kd_barang` varchar(5) NOT NULL,
  `qty_beli` smallint(3) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_pembeliandtl`
--

INSERT INTO `ypos_pembeliandtl` (`idDtlPembelian`, `kdPembelian`, `kd_barang`, `qty_beli`, `harga_beli`, `total`) VALUES
(1, 'P-HYG201504040000001', 'B0012', 2, '52400.00', '104800.00'),
(2, 'P-HYG201504040000001', 'B0013', 3, '52400.00', '157200.00'),
(3, 'P-HYG201504090000002', 'B0018', 2, '26000.00', '52000.00'),
(4, 'P-HYG201504090000002', 'B0019', 1, '14000.00', '14000.00'),
(5, 'P-HYG201504090000003', 'B0018', 1, '26000.00', '26000.00'),
(6, 'P-HYG201504090000003', 'B0019', 2, '14000.00', '28000.00'),
(7, 'P-HYG201504090000004', 'B0019', 2, '14000.00', '28000.00'),
(8, 'P-HYG201504090000004', 'B0018', 1, '26000.00', '26000.00'),
(9, 'P-HYG201504090000005', 'B0019', 2, '14000.00', '28000.00'),
(10, 'P-HYG201504090000005', 'B0018', 1, '26000.00', '26000.00'),
(11, 'P-HYG201504090000006', 'B0018', 2, '26000.00', '52000.00'),
(12, 'P-HYG201504090000006', 'B0019', 1, '14000.00', '14000.00'),
(13, 'P-HYG201504190000007', 'B0011', 1, '38900.00', '38900.00'),
(14, 'P-HYG201504190000008', 'B0003', 2, '46900.00', '93800.00'),
(15, 'P-HYG201504190000008', 'B0002', 1, '46900.00', '46900.00'),
(16, 'P-HYG201504190000008', 'B0021', 2, '46900.00', '93800.00'),
(17, 'P-HYG201504190000008', 'B0022', 1, '46900.00', '46900.00'),
(18, 'P-HYG201504190000009', 'B0024', 3, '56300.00', '168900.00'),
(19, 'P-HYG201504190000009', 'B0025', 1, '56300.00', '56300.00'),
(20, 'P-HYG201504190000009', 'B0026', 2, '56300.00', '112600.00'),
(21, 'P-HYG201504190000010', 'B0027', 3, '48300.00', '144900.00'),
(22, 'P-HYG201504190000010', 'B0028', 3, '36233.33', '108700.00'),
(23, 'P-HYG201504190000010', 'B0029', 1, '48181.00', '48181.00'),
(24, 'P-HYG201504190000010', 'B0030', 1, '47792.00', '47792.00'),
(25, 'P-HYG201504190000011', 'B0031', 2, '51500.00', '103000.00'),
(26, 'P-HYG201504190000012', 'B0019', 4, '19000.00', '76000.00'),
(27, 'P-HYG201504190000013', 'B0003', 3, '47532.33', '142597.00'),
(28, 'P-HYG201504190000013', 'B0032', 3, '47402.33', '142207.00'),
(29, 'P-mhs201512110000001', 'B0001', 5, '2.80', '14.00');

--
-- Trigger `ypos_pembeliandtl`
--
DELIMITER //
CREATE TRIGGER `T_UpdStok_AD_Pembelian` AFTER DELETE ON `ypos_pembeliandtl`
 FOR EACH ROW BEGIN


	UPDATE ypos_barang SET stok=stok - OLD.qty_beli WHERE kdbarang=OLD.kd_barang;


    END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `T_UpdStok_AI_Pembelian` AFTER INSERT ON `ypos_pembeliandtl`
 FOR EACH ROW BEGIN


	UPDATE ypos_barang SET stok=stok + NEW.qty_beli WHERE kdbarang=NEW.kd_barang;


    END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `T_UpdStok_AU_Pembelian` AFTER UPDATE ON `ypos_pembeliandtl`
 FOR EACH ROW BEGIN


	UPDATE ypos_barang SET stok=stok - old.qty_beli WHERE kdbarang=old.kd_barang;


	UPDATE ypos_barang SET stok=stok + new.qty_beli WHERE kdbarang=new.kd_barang;


    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_penjualan`
--

CREATE TABLE IF NOT EXISTS `ypos_penjualan` (
  `kd_penjualan` varchar(20) NOT NULL,
  `ids` tinyint(1) unsigned NOT NULL,
  `customer` varchar(25) NOT NULL DEFAULT 'Guest',
  `tgl_input` datetime DEFAULT 0,
  `tgl_jual` date DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `diskon` decimal(8,2) NOT NULL,
  `diskon_rp` int(100) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `uang_bayar` decimal(10,2) NOT NULL,
  `uang_kembali` decimal(10,2) NOT NULL,
  `keterangan` text,
  `userID` varchar(15) NOT NULL,
  `date_lastUpdate` datetime DEFAULT NULL,
  `user_lastUpdate` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_penjualan`
--

INSERT INTO `ypos_penjualan` (`kd_penjualan`, `ids`, `customer`, `tgl_input`, `tgl_jual`, `subtotal`, `diskon`, `diskon_rp`, `grand_total`, `uang_bayar`, `uang_kembali`, `keterangan`, `userID`, `date_lastUpdate`, `user_lastUpdate`) VALUES
('INV-2015121100000001', 1, 'tatang', '2015-12-11 23:37:40', '2015-12-11', '882000.00', '0.00', 0, '882000.00', '88000.00', '-794000.00', '', 'owner', NULL, NULL),
('INV-2015121200000002', 1, 'umum', '2015-12-12 00:02:15', '2015-12-12', '15000.00', '0.00', 0, '15000.00', '15000.00', '0.00', '', 'owner', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_penjualandtl`
--

CREATE TABLE IF NOT EXISTS `ypos_penjualandtl` (
`idDtlPenjualan` int(8) unsigned NOT NULL,
  `kd_penjualan` varchar(20) NOT NULL,
  `kd_barang` varchar(5) NOT NULL,
  `harga_jual` decimal(10,2) NOT NULL,
  `diskon` decimal(10,2) NOT NULL,
  `qty` smallint(3) NOT NULL,
  `harga_beli` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_harga` decimal(10,2) NOT NULL,
  `userID` varchar(15) NOT NULL,
  `tgl_input` datetime NOT NULL DEFAULT 0,
  `date_lastUpdate` datetime DEFAULT NULL,
  `user_lastUpdate` varchar(15) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_penjualandtl`
--

INSERT INTO `ypos_penjualandtl` (`idDtlPenjualan`, `kd_penjualan`, `kd_barang`, `harga_jual`, `diskon`, `qty`, `harga_beli`, `total_harga`, `userID`, `tgl_input`, `date_lastUpdate`, `user_lastUpdate`) VALUES
(37, 'INV-2015121100000001', 'B0002', '98000.00', '0.00', 9, '88000.00', '882000.00', 'owner', '2015-12-11 23:37:40', '2015-12-11 23:37:40', 'owner'),
(38, 'INV-2015121200000002', 'B0001', '15000.00', '0.00', 1, '2.80', '15000.00', 'owner', '2015-12-12 00:02:15', '2015-12-12 00:02:15', 'owner');

--
-- Trigger `ypos_penjualandtl`
--
DELIMITER //
CREATE TRIGGER `T_UpdStok_AD_Penjualan` AFTER DELETE ON `ypos_penjualandtl`
 FOR EACH ROW BEGIN


	UPDATE ypos_barang SET stok=stok + OLD.qty WHERE kdbarang=OLD.kd_barang;


    END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `T_UpdStok_AI_Penjualan` AFTER INSERT ON `ypos_penjualandtl`
 FOR EACH ROW BEGIN


	UPDATE ypos_barang SET stok=stok - NEW.qty WHERE kdbarang=NEW.kd_barang;


    END
//
DELIMITER ;
DELIMITER //
CREATE TRIGGER `T_UpdStok_AU_Penjualan` AFTER UPDATE ON `ypos_penjualandtl`
 FOR EACH ROW BEGIN


	UPDATE ypos_barang SET stok=stok - old.qty WHERE kdbarang=old.kd_barang;


	UPDATE ypos_barang SET stok=stok + new.qty WHERE kdbarang=new.kd_barang;


    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_rptpriv`
--

CREATE TABLE IF NOT EXISTS `ypos_rptpriv` (
  `idparam` tinyint(5) NOT NULL,
  `idlevel` tinyint(2) NOT NULL,
  `userid` varchar(15) NOT NULL,
  `akses` enum('Y','N') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_rptpriv`
--

INSERT INTO `ypos_rptpriv` (`idparam`, `idlevel`, `userid`, `akses`) VALUES
(3, 1, 'yuda', 'Y'),
(4, 1, 'yuda', 'Y'),
(5, 1, 'yuda', 'Y'),
(0, 1, 'yuda', 'N');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_settings`
--

CREATE TABLE IF NOT EXISTS `ypos_settings` (
`ids` tinyint(1) unsigned NOT NULL,
  `kdSET` varchar(3) NOT NULL,
  `nama_toko` varchar(100) NOT NULL,
  `alamat` text,
  `keckab` varchar(200) DEFAULT NULL,
  `tlp` varchar(20) DEFAULT NULL,
  `printer` varchar(100) NOT NULL,
  `url_web` varchar(200) NOT NULL,
  `logo_toko` varchar(100) NOT NULL,
  `last_update` varchar(20) NOT NULL,
  `folder_modul` varchar(20) NOT NULL,
  `limit_page` tinyint(1) unsigned DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_settings`
--

INSERT INTO `ypos_settings` (`ids`, `kdSET`, `nama_toko`, `alamat`, `keckab`, `tlp`, `printer`, `url_web`, `logo_toko`, `last_update`, `folder_modul`, `limit_page`) VALUES
(1, 'mhs', 'MHS', 'Bengkel Motor', 'Cisalak Subang', '-', 'Canon MP230 series Printer', 'Bengkel Motor', '', 'owner', 'modul', 100);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_suplier`
--

CREATE TABLE IF NOT EXISTS `ypos_suplier` (
`kdsup` smallint(1) unsigned NOT NULL,
  `ids` tinyint(1) unsigned NOT NULL,
  `nama_sup` varchar(100) DEFAULT NULL,
  `tlp` varchar(20) NOT NULL,
  `alamat` text,
  `date_create` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_suplier`
--

INSERT INTO `ypos_suplier` (`kdsup`, `ids`, `nama_sup`, `tlp`, `alamat`, `date_create`) VALUES
(1, 1, 'Delima Motor', '081283741777', 'Setia Budhi, Bandung', '2015-05-24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ypos_users`
--

CREATE TABLE IF NOT EXISTS `ypos_users` (
  `username` varchar(15) NOT NULL,
  `nama_lengkap` varchar(40) NOT NULL,
  `pass` varchar(35) NOT NULL,
  `hp` varchar(15) NOT NULL,
  `sessionID` varchar(100) NOT NULL DEFAULT '0',
  `online` enum('Y','N') NOT NULL DEFAULT 'N',
  `aktif` enum('Y','N') NOT NULL DEFAULT 'Y',
  `level` tinyint(2) NOT NULL,
  `ids` tinyint(1) unsigned NOT NULL,
  `last_seen` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ypos_users`
--

INSERT INTO `ypos_users` (`username`, `nama_lengkap`, `pass`, `hp`, `sessionID`, `online`, `aktif`, `level`, `ids`, `last_seen`) VALUES
('goro', 'goro', 'd4884e2747a82cbbbe7883b2b5b5e079', '08999976543', '', 'N', 'Y', 18, 1, '2015-12-13 16:23:39'),
('owner', 'owner', '72122ce96bfec66e2396d2e25225d70a', '08987654312', 'p12v9k2lcm79gjsqreqj9cgs91', 'Y', 'Y', 1, 1, '2015-12-16 19:21:28'),
('warsito', 'warsito', 'dcfef106f51c01e432aafeaa5f76bf7e', '08999976543', '', 'N', 'Y', 21, 1, '2015-12-13 17:06:19');

-- --------------------------------------------------------

--
-- Stand-in structure for view `ypos_vpembeliandtl`
--
CREATE TABLE IF NOT EXISTS `ypos_vpembeliandtl` (
`kdbarang` varchar(5)
,`nama_barang` varchar(100)
,`harga_pokok_beli` decimal(10,2)
,`harga_pokok_jual` decimal(10,2)
,`kdsup` smallint(1) unsigned
,`nama_sup` varchar(100)
,`no_nota` varchar(20)
,`total_pembelian` decimal(10,2)
,`userID` varchar(15)
,`tgl_beli` date
,`idDtlPembelian` int(5)
,`kdPembelian` varchar(20)
,`qty_beli` smallint(3)
,`harga_beli_satBaru` decimal(10,2)
,`total_beli` decimal(10,2)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `ypos_vpenjualandtl`
--
CREATE TABLE IF NOT EXISTS `ypos_vpenjualandtl` (
`kdbarang` varchar(5)
,`nama_barang` varchar(100)
,`harga_beli` decimal(10,2)
,`harga_jualstd` decimal(10,2)
,`idDtlPen` int(8) unsigned
,`kd_penjualan` varchar(20)
,`th_beli` decimal(37,2)
,`harga_jualreal` decimal(10,2)
,`qty` smallint(3)
,`diskon_produk` decimal(33,2)
,`pendapatan` decimal(39,2)
,`th_jual` decimal(10,2)
,`customer` varchar(25)
,`tgl_jual` date
,`diskon_final` decimal(8,2)
,`userID` varchar(15)
);
-- --------------------------------------------------------

--
-- Struktur untuk view `ypos_vpembeliandtl`
--
DROP TABLE IF EXISTS `ypos_vpembeliandtl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ypos_vpembeliandtl` AS select `a`.`kdbarang` AS `kdbarang`,`a`.`nama_barang` AS `nama_barang`,`a`.`harga_beli` AS `harga_pokok_beli`,`a`.`harga_jual` AS `harga_pokok_jual`,`ypos_suplier`.`kdsup` AS `kdsup`,`ypos_suplier`.`nama_sup` AS `nama_sup`,`b`.`no_nota` AS `no_nota`,`b`.`total_pembelian` AS `total_pembelian`,`b`.`userID` AS `userID`,`b`.`tgl_beli` AS `tgl_beli`,`c`.`idDtlPembelian` AS `idDtlPembelian`,`c`.`kdPembelian` AS `kdPembelian`,`c`.`qty_beli` AS `qty_beli`,`c`.`harga_beli` AS `harga_beli_satBaru`,`c`.`total` AS `total_beli` from (((`ypos_barang` `a` join `ypos_suplier`) join `ypos_pembelian` `b`) join `ypos_pembeliandtl` `c`) where ((`a`.`kdbarang` = `c`.`kd_barang`) and (`b`.`kdsup` = `ypos_suplier`.`kdsup`) and (`b`.`kdPembelian` = `c`.`kdPembelian`));

-- --------------------------------------------------------

--
-- Struktur untuk view `ypos_vpenjualandtl`
--
DROP TABLE IF EXISTS `ypos_vpenjualandtl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `ypos_vpenjualandtl` AS select `a`.`kdbarang` AS `kdbarang`,`a`.`nama_barang` AS `nama_barang`,`a`.`harga_beli` AS `harga_beli`,`a`.`harga_jual` AS `harga_jualstd`,`b`.`idDtlPenjualan` AS `idDtlPen`,`b`.`kd_penjualan` AS `kd_penjualan`,sum((`a`.`harga_beli` * `b`.`qty`)) AS `th_beli`,`b`.`harga_jual` AS `harga_jualreal`,`b`.`qty` AS `qty`,(case when (`a`.`harga_jual` >= `b`.`harga_jual`) then sum((`a`.`harga_jual` - `b`.`harga_jual`)) when (`a`.`harga_jual` <= `b`.`harga_jual`) then 0 end) AS `diskon_produk`,sum(((`b`.`total_harga` - (`a`.`harga_beli` * `b`.`qty`)) - `c`.`diskon`)) AS `pendapatan`,`b`.`total_harga` AS `th_jual`,`c`.`customer` AS `customer`,`c`.`tgl_jual` AS `tgl_jual`,`c`.`diskon` AS `diskon_final`,`c`.`userID` AS `userID` from ((`ypos_barang` `a` join `ypos_penjualandtl` `b`) join `ypos_penjualan` `c`) where ((`a`.`kdbarang` = `b`.`kd_barang`) and (`b`.`kd_penjualan` = `c`.`kd_penjualan`)) group by `b`.`idDtlPenjualan`;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ypos_barang`
--
ALTER TABLE `ypos_barang`
 ADD PRIMARY KEY (`kdbarang`);

--
-- Indexes for table `ypos_grouplvlmdl`
--
ALTER TABLE `ypos_grouplvlmdl`
 ADD PRIMARY KEY (`idGroupLM`), ADD KEY `idlevel` (`idlevel`), ADD KEY `modulID` (`modulID`);

--
-- Indexes for table `ypos_kategori`
--
ALTER TABLE `ypos_kategori`
 ADD PRIMARY KEY (`idkat`);

--
-- Indexes for table `ypos_level`
--
ALTER TABLE `ypos_level`
 ADD PRIMARY KEY (`idlevel`);

--
-- Indexes for table `ypos_lgnhistories`
--
ALTER TABLE `ypos_lgnhistories`
 ADD PRIMARY KEY (`idLgn`);

--
-- Indexes for table `ypos_menu`
--
ALTER TABLE `ypos_menu`
 ADD PRIMARY KEY (`menuID`);

--
-- Indexes for table `ypos_modul`
--
ALTER TABLE `ypos_modul`
 ADD PRIMARY KEY (`modulID`);

--
-- Indexes for table `ypos_paramchild`
--
ALTER TABLE `ypos_paramchild`
 ADD PRIMARY KEY (`idpc`);

--
-- Indexes for table `ypos_parameter`
--
ALTER TABLE `ypos_parameter`
 ADD PRIMARY KEY (`idpm`);

--
-- Indexes for table `ypos_pembelian`
--
ALTER TABLE `ypos_pembelian`
 ADD PRIMARY KEY (`kdPembelian`);

--
-- Indexes for table `ypos_pembeliandtl`
--
ALTER TABLE `ypos_pembeliandtl`
 ADD PRIMARY KEY (`idDtlPembelian`);

--
-- Indexes for table `ypos_penjualan`
--
ALTER TABLE `ypos_penjualan`
 ADD PRIMARY KEY (`kd_penjualan`);

--
-- Indexes for table `ypos_penjualandtl`
--
ALTER TABLE `ypos_penjualandtl`
 ADD PRIMARY KEY (`idDtlPenjualan`), ADD KEY `kd_penjualan` (`kd_penjualan`);

--
-- Indexes for table `ypos_rptpriv`
--
ALTER TABLE `ypos_rptpriv`
 ADD KEY `idlevel` (`idlevel`);

--
-- Indexes for table `ypos_settings`
--
ALTER TABLE `ypos_settings`
 ADD PRIMARY KEY (`ids`);

--
-- Indexes for table `ypos_suplier`
--
ALTER TABLE `ypos_suplier`
 ADD PRIMARY KEY (`kdsup`);

--
-- Indexes for table `ypos_users`
--
ALTER TABLE `ypos_users`
 ADD PRIMARY KEY (`username`), ADD KEY `ypos_users_ibfk_1` (`level`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ypos_grouplvlmdl`
--
ALTER TABLE `ypos_grouplvlmdl`
MODIFY `idGroupLM` int(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=216;
--
-- AUTO_INCREMENT for table `ypos_kategori`
--
ALTER TABLE `ypos_kategori`
MODIFY `idkat` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `ypos_level`
--
ALTER TABLE `ypos_level`
MODIFY `idlevel` tinyint(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `ypos_lgnhistories`
--
ALTER TABLE `ypos_lgnhistories`
MODIFY `idLgn` int(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT for table `ypos_menu`
--
ALTER TABLE `ypos_menu`
MODIFY `menuID` tinyint(3) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ypos_modul`
--
ALTER TABLE `ypos_modul`
MODIFY `modulID` tinyint(3) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `ypos_paramchild`
--
ALTER TABLE `ypos_paramchild`
MODIFY `idpc` tinyint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ypos_parameter`
--
ALTER TABLE `ypos_parameter`
MODIFY `idpm` int(3) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ypos_pembeliandtl`
--
ALTER TABLE `ypos_pembeliandtl`
MODIFY `idDtlPembelian` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `ypos_penjualandtl`
--
ALTER TABLE `ypos_penjualandtl`
MODIFY `idDtlPenjualan` int(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `ypos_settings`
--
ALTER TABLE `ypos_settings`
MODIFY `ids` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ypos_suplier`
--
ALTER TABLE `ypos_suplier`
MODIFY `kdsup` smallint(1) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `ypos_grouplvlmdl`
--
ALTER TABLE `ypos_grouplvlmdl`
ADD CONSTRAINT `ypos_grouplvlmdl_ibfk_1` FOREIGN KEY (`idlevel`) REFERENCES `ypos_level` (`idlevel`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `ypos_grouplvlmdl_ibfk_2` FOREIGN KEY (`modulID`) REFERENCES `ypos_modul` (`modulID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ypos_penjualandtl`
--
ALTER TABLE `ypos_penjualandtl`
ADD CONSTRAINT `ypos_penjualandtl_ibfk_1` FOREIGN KEY (`kd_penjualan`) REFERENCES `ypos_penjualan` (`kd_penjualan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ypos_rptpriv`
--
ALTER TABLE `ypos_rptpriv`
ADD CONSTRAINT `ypos_rptpriv_ibfk_1` FOREIGN KEY (`idlevel`) REFERENCES `ypos_level` (`idlevel`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ypos_users`
--
ALTER TABLE `ypos_users`
ADD CONSTRAINT `ypos_users_ibfk_1` FOREIGN KEY (`level`) REFERENCES `ypos_level` (`idlevel`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
