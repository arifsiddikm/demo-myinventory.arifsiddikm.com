<?php
// $target = '/home/u306985438/domains/arifsiddikm.com/public_html/demo/demo-myinventory.arifsiddikm.com/storage/app/public';
// $link   = '/home/u306985438/domains/arifsiddikm.com/public_html/demo/demo-myinventory.arifsiddikm.com/public/storage';
$target = '/home/arifsidd/public_html/demo-myinventory.arifsiddikm.biz.id/storage/app/public';
$link   = '/home/arifsidd/public_html/demo-myinventory.arifsiddikm.biz.id/public/storage';
if (symlink($target, $link)) {
    echo "Symlink berhasil dibuat";
} else {
    echo "Gagal buat symlink";
}