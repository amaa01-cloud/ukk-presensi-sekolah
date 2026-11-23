<?php
require_once __DIR__ . '/config.php';
foreach (['Database', 'BaseModel', 'User', 'Kelas', 'Siswa'] as $c) {
    require_once __DIR__ . "/classes/$c.php";
}
header('Content-Type: text/plain; charset=utf-8');
$db = (new Database())->getConnection();

$user = new User($db);
if (!$user->findByUsername('admin')) {
    $u = new User($db);
    $u->setUsername('admin');
    $u->setPassword('admin123');
    $u->create();
    echo "Admin dibuat (admin / admin123)\n";
}

foreach (['X PPLG 1', 'X PPLG 2', 'XI PPLG 1', 'XI PPLG 2', 'XII PPLG 1', 'XII PPLG 2'] as $nama) {
    $k = new Kelas($db);
    $k->setNamaKelas($nama);
    $k->setJurusan('PPLG');
    if (!$k->namaExists()) { $k->create(); echo "Kelas $nama dibuat\n"; }
}