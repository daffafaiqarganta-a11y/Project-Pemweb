<?php
require 'config.php';
if (!isset($_SESSION['user'])) go('index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    checkToken();
    $action=$_POST['action']??'';
    if ($action==='logout') {
        session_destroy();
        go('index.php');
    }
    $id=(int)($_POST['id']??0);
    if ($action==='delete') {
        $db->prepare('DELETE FROM barang WHERE id=?')->execute([$id]);
        go('dashboard.php');
    }
    if (in_array($action,['add','edit'],true)) {
        $nama=trim($_POST['nama']??'');
        $kategori=trim($_POST['kategori']??'');
        $stok=filter_var($_POST['stok']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>0,'max_range'=>1000000]]);
        $harga=filter_var($_POST['harga']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>0,'max_range'=>999999999999]]);
        if (!$nama || !$kategori || strlen($nama)>100 || strlen($kategori)>80 || $stok===false || $harga===false) $error='Data tidak valid. Periksa kembali isian.';
        else {
            if ($action==='add') $db->prepare('INSERT INTO barang(nama,kategori,stok,harga) VALUES (?,?,?,?)')->execute([$nama,$kategori,$stok,$harga]);
            else $db->prepare('UPDATE barang SET nama=?,kategori=?,stok=?,harga=? WHERE id=?')->execute([$nama,$kategori,$stok,$harga,$id]);
            go('dashboard.php');
        }
    }
}
$rows=$db->query('SELECT * FROM barang ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
$total=0;
$nilai=0;
$rendah=0;
foreach($rows as $r) {
    $total+=(int)$r['stok'];
    $nilai+=$r['stok']*$r['harga'];
    if($r['stok']<5) $rendah++;
}
$edit=null;
if(isset($_GET['edit'])) {
    $q=$db->prepare('SELECT * FROM barang WHERE id=?');
    $q->execute([(int)$_GET['edit']]);
    $edit=$q->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>
            Stokku | Dashboard
        </title>
        <link rel="stylesheet" href="style.css">
        <script src="app.js" defer>
        </script>
    </head>
    <body>
        <header>
            <span class="brand">
                ◈ STOKKU
            </span>
            <div class="actions">
                <span>
                    <?=e($_SESSION['user'])?>
                </span>
                <button id="theme" class="secondary" type="button">
                    Mode gelap
                </button>
                <form method="post">
                    <input type="hidden" name="csrf" value="<?=token()?>">
                    <button name="action" value="logout">
                        Keluar
                    </button>
                </form>
            </div>
        </header>
        <main class="wrap">
            <p class="eyebrow">
                MANAJEMEN INVENTARIS
            </p>
            <h1>
                Dashboard barang
            </h1>
            <p>
                Kelola data barang dan pantau ketersediaan stok secara langsung.
            </p>
            <section class="stats">
                <article class="panel">
                    Jenis barang
                    <strong>
                        <?=count($rows)?>
                    </strong>
                </article>
                <article class="panel">
                    Total unit
                    <strong>
                        <?=$total?>
                    </strong>
                </article>
                <article class="panel">
                    Nilai inventaris
                    <strong>
                        <?=rupiah($nilai)?>
                    </strong>
                </article>
                <article class="panel">
                    Stok menipis
                    <strong>
                        <?=$rendah?>
                    </strong>
                </article>
            </section>
            <section class="panel">
                <h2>
                    <?=$edit?'Edit barang':'Tambah barang'?>
                </h2>
                <?php if($error):?>
                <p class="notice">
                    <?=e($error)?>
                </p>
                <?php endif;?>
                <form method="post" class="fields">
                    <input type="hidden" name="csrf" value="<?=token()?>">
                    <input type="hidden" name="id" value="<?=e($edit['id']??0)?>">
                    <label>
                        Nama barang
                        <input name="nama" required maxlength="100" value="<?=e($edit['nama']??'')?>">
                    </label>
                    <label>
                        Kategori
                        <input name="kategori" required maxlength="80" value="<?=e($edit['kategori']??'')?>">
                    </label>
                    <label>
                        Stok
                        <input id="stok" type="number" name="stok" min="0" max="1000000" required value="<?=e($edit['stok']??'')?>">
                    </label>
                    <label>
                        Harga satuan
                        <input id="harga" type="number" name="harga" min="0" max="999999999999" required value="<?=e($edit['harga']??'')?>">
                    </label>
                    <p id="preview">
                        Estimasi nilai: Rp 0
                    </p>
                    <div class="actions">
                        <button name="action" value="<?=$edit?'edit':'add'?>">
                            Simpan barang
                        </button>
                        <?php if($edit):?>
                        <a href="dashboard.php">
                            Batal
                        </a>
                        <?php endif;?>
                    </div>
                </form>
            </section>
            <section class="panel">
                <div class="tablehead">
                    <h2>
                        Daftar barang
                    </h2>
                    <input id="search" placeholder="Cari nama atau kategori...">
                </div>
                <p id="count">
                    <?=count($rows)?> barang
                </p>
                <div class="scroll">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    Nama
                                </th>
                                <th>
                                    Kategori
                                </th>
                                <th>
                                    Stok
                                </th>
                                <th>
                                    Harga
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($rows as $r):?>
                            <tr>
                                <td>
                                    <?=e($r['nama'])?>
                                </td>
                                <td>
                                    <?=e($r['kategori'])?>
                                </td>
                                <td>
                                    <?=$r['stok']?>
                                </td>
                                <td>
                                    <?=rupiah($r['harga'])?>
                                </td>
                                <td>
                                    <span class="tag <?=$r['stok']<5?'low':''?>">
                                        <?=$r['stok']==0?'Habis':($r['stok']<5?'Menipis':'Tersedia')?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="?edit=<?=$r['id']?>">
                                        Edit
                                    </a>
                                    <form method="post" class="delete">
                                        <input type="hidden" name="csrf" value="<?=token()?>">
                                        <input type="hidden" name="id" value="<?=$r['id']?>">
                                        <button class="danger" name="action" value="delete">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
        <footer>
            Stokku · Project Pemrograman Web
        </footer>
    </body>
</html>
