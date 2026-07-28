<?php
require_once 'Mahasiswa.php';
$mhsObj = new Mahasiswa();

// --- 1. Handler Hapus ---
if (isset($_GET['hapus_mhs'])) {
    $mhsObj->hapus($_GET['hapus_mhs']);
    echo "<script>window.location.href='dashboard.php?page=mahasiswa';</script>";
    exit();
}

// --- 2. Handler Tambah Data ---
if (isset($_POST['tambah_mhs'])) {
    $foto = $mhsObj->uploadGambar($_FILES['foto']);
    if ($foto) {
        $mhsObj->tambah($_POST['nim'], $_POST['nama'], $_POST['jurusan'], $_POST['email'], $foto);
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='dashboard.php?page=mahasiswa';</script>";
    } else {
        echo "<script>alert('Gagal upload gambar. Pastikan format JPG/PNG.');</script>";
    }
    exit();
}

// --- 3. Handler Update Data ---
if (isset($_POST['update_mhs'])) {
    $id = $_POST['id'];
    $fotoLama = $_POST['fotoLama'];
    // Jika user tidak upload foto baru (error 4), pakai foto lama
    $foto = ($_FILES['foto']['error'] === 4) ? $fotoLama : $mhsObj->uploadGambar($_FILES['foto']);

    $mhsObj->update($id, $_POST['nim'], $_POST['nama'], $_POST['jurusan'], $_POST['email'], $foto);
    echo "<script>window.location.href='dashboard.php?page=mahasiswa';</script>";
    exit();
}

// --- 4. Logika Pencarian ---
$keyword = (isset($_POST['cari'])) ? $_POST['keyword'] : "";
$dataMhs = ($keyword != "") ? $mhsObj->cariData($keyword) : $mhsObj->tampilkanSemua();
?>

<div class="card border-0 shadow-sm" style="border-radius: 25px;">
    <div class="card-header bg-white py-4 px-4" style="border-bottom: 2px dashed #f8bbd0;">
        <div class="row g-3 align-items-center">
            <div class="col-lg-4">
                <h5 class="fw-bold mb-0 text-primary">
                    <i class="fas fa-user-graduate me-2"></i>Data Mahasiswa
                </h5>
            </div>
            <div class="col-lg-5">
                <form method="POST" class="input-group shadow-sm rounded-pill overflow-hidden border">
                    <input type="text" name="keyword" class="form-control border-0 px-4" 
                           placeholder="Cari NIM, Nama..." value="<?= htmlspecialchars($keyword) ?>">
                    <button type="submit" name="cari" class="btn btn-primary px-3">
                        <i class="fas fa-search"></i>
                    </button>
                    <?php if($keyword != ""): ?>
                        <a href="dashboard.php?page=mahasiswa" class="btn btn-light px-3 border-start">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
            
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-secondary small uppercase">
                <tr>
                    <th class="ps-4">Foto</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th class="text-center">QR</th>
                    <th>Jurusan</th>
                    <th>Status</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                $modalsData = []; // Penampung data modal
                if ($dataMhs->num_rows > 0): 
                    while($m = $dataMhs->fetch_assoc()): 
                        $modalsData[] = $m; 
                ?>
                    <tr>
                        <td class="ps-4">
                            <div class="zoom-container">
                                <img src="img/<?= $m['foto'] ?>" class="zoom-image img-thumbnail border-0 shadow-sm" 
                                     style="width: 45px; height: 45px; border-radius: 10px; object-fit: cover;" 
                                     onclick="toggleZoom(this)">
                            </div>
                        </td>
                        <td class="fw-bold text-secondary small"><?= $m['nim'] ?></td>
                        <td class="fw-bold"><?= $m['nama'] ?></td>
                        <td class="text-center">
                            <img src="<?= $mhsObj->generateQRCode($m['nim']) ?>" class="rounded border p-1" style="width: 40px;">
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-light text-primary border border-primary-subtle px-3 py-2">
                                <?= $m['jurusan'] ?>
                            </span>
                        </td>
                        
                        <td class="text-center">
                            <span class="badge rounded-pill bg-success small" style="font-size: 0.7rem;">Aktif</span>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted small">Data tidak ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php foreach($modalsData as $m): ?>
<div class="modal fade" id="editMhs<?= $m['id'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h6 class="fw-bold m-0"><i class="fas fa-edit me-2"></i>Perbarui Data Mahasiswa</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light text-start">
                <input type="hidden" name="id" value="<?= $m['id'] ?>">
                <input type="hidden" name="fotoLama" value="<?= htmlspecialchars($m['foto']) ?>">
                
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">NIM</label>
                        <input type="text" name="nim" class="form-control rounded-3 shadow-sm" value="<?= htmlspecialchars($m['nim']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control rounded-3 shadow-sm" value="<?= htmlspecialchars($m['nama']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control rounded-3 shadow-sm" value="<?= htmlspecialchars($m['jurusan']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Email</label>
                        <input type="email" name="email" class="form-control rounded-3 shadow-sm" value="<?= htmlspecialchars($m['email']) ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Foto Profil (Opsional)</label>
                        <input type="file" name="foto" class="form-control rounded-3 shadow-sm">
                        <div class="mt-2" style="font-size: 11px; color: #6c757d;">*Biarkan kosong jika tidak ingin ganti foto.</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="update_mhs" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<div class="modal fade" id="addMhs" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-success text-white border-0 py-3">
                <h6 class="fw-bold m-0"><i class="fas fa-plus-circle me-2"></i>Registrasi Mahasiswa Baru</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light text-start">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">NIM</label>
                        <input type="text" name="nim" class="form-control rounded-3 shadow-sm" placeholder="Contoh: 3202216xxx" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control rounded-3 shadow-sm" placeholder="Masukkan Nama Lengkap" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control rounded-3 shadow-sm" placeholder="Contoh: Manajemen Informatika" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Email</label>
                        <input type="email" name="email" class="form-control rounded-3 shadow-sm" placeholder="mhs@poltesa.ac.id" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold mb-1">Upload Foto Profil</label>
                        <input type="file" name="foto" class="form-control rounded-3 shadow-sm" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="tambah_mhs" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">Simpan Mahasiswa</button>
            </div>
        </form>
    </div>
</div>

<?= $mhsObj->getZoomScript(); ?>