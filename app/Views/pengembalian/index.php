<?php
/**
 * Halaman Transaksi Pengembalian.
 * Data dari Pengembalian::index():
 *   $peminjamanList = transaksi status 'dipinjam' (+ nama_anggota, kelas, nama_petugas)
 *      items[] = buku yang BELUM kembali: id_detail, id_buku, jumlah, judul, kode_buku
 */
$fmtTgl = static fn ($d) => $d ? date('d/m/Y', strtotime($d)) : '-';
$today  = strtotime(date('Y-m-d'));
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title mb-1">Peminjaman Aktif</h4>
                <p class="text-muted mb-3">
                    Transaksi yang masih memiliki buku belum dikembalikan, diurutkan dari jatuh tempo terdekat.
                </p>

                <table id="tabel-pengembalian" class="table table-bordered table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Anggota</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Buku Belum Kembali</th>
                            <th style="width:150px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peminjamanList as $i => $p): ?>
                            <?php
                            $telat = max(0, (int) floor(($today - strtotime($p['tanggal_jatuh_tempo'])) / 86400));
                            $items = $p['items'];
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <?= esc($p['nama_anggota']) ?>
                                    <div class="text-muted font-size-12"><?= esc($p['kelas']) ?></div>
                                </td>
                                <td data-order="<?= esc($p['tanggal_pinjam'], 'attr') ?>"><?= $fmtTgl($p['tanggal_pinjam']) ?></td>
                                <td data-order="<?= esc($p['tanggal_jatuh_tempo'], 'attr') ?>">
                                    <?= $fmtTgl($p['tanggal_jatuh_tempo']) ?>
                                    <?php if ($telat > 0): ?>
                                        <div><span class="badge bg-danger">Terlambat <?= $telat ?> hari</span></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php foreach ($items as $it): ?>
                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                            <span>
                                                <?= esc($it['judul']) ?>
                                                <span class="text-muted">&times; <?= (int) $it['jumlah'] ?></span>
                                            </span>
                                            <button type="button" class="btn btn-sm btn-outline-success waves-effect btn-kembali"
                                                data-url="<?= esc(base_url('pengembalian/kembalikan/' . $it['id_detail']), 'attr') ?>"
                                                data-label="<?= esc($it['judul'], 'attr') ?>"
                                                data-pinjam="<?= esc($p['tanggal_pinjam'], 'attr') ?>"
                                                data-jatuh-tempo="<?= esc($p['tanggal_jatuh_tempo'], 'attr') ?>"
                                                data-n="1">
                                                Kembalikan
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (count($items) > 0): ?>
                                        <button type="button" class="btn btn-sm btn-success waves-effect btn-kembali"
                                            data-url="<?= esc(base_url('pengembalian/kembalikan-semua/' . $p['id_peminjaman']), 'attr') ?>"
                                            data-label="Semua buku milik <?= esc($p['nama_anggota'], 'attr') ?> (<?= count($items) ?> judul)"
                                            data-pinjam="<?= esc($p['tanggal_pinjam'], 'attr') ?>"
                                            data-jatuh-tempo="<?= esc($p['tanggal_jatuh_tempo'], 'attr') ?>"
                                            data-n="<?= count($items) ?>">
                                            <i class="mdi mdi-check-all me-1"></i> Kembalikan Semua
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Pengembalian -->
<div class="modal fade" id="modal-kembali" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" id="form-kembali" method="post" action="">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Mengembalikan: <strong id="kembali-label"></strong></p>

                <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" required>

                <div id="kembali-info" class="alert alert-success mt-3 mb-0"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success waves-effect waves-light">Proses Pengembalian</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var DENDA_PER_HARI = 1000;   // samakan dengan DetailPeminjamanModel::DENDA_PER_HARI
    var modal = new bootstrap.Modal(document.getElementById('modal-kembali'));
    var form  = document.getElementById('form-kembali');
    var tgl   = document.getElementById('tanggal_kembali');
    var info  = document.getElementById('kembali-info');
    var cur   = null;

    $('#tabel-pengembalian').DataTable({
        responsive: true,
        order: [],                       // urutan dari server (jatuh tempo terdekat)
        columnDefs: [{ orderable: false, targets: [4, 5] }],
        language: {
            search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data', zeroRecords: 'Data tidak ditemukan',
            emptyTable: 'Tidak ada peminjaman aktif',
            paginate: { previous: '&laquo;', next: '&raquo;' }
        }
    });

    function iso(d) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }

    // Estimasi denda (hanya info; hitungan resmi tetap di server)
    function updateInfo() {
        var hari = Math.round((Date.parse(tgl.value) - Date.parse(cur.jatuhTempo)) / 86400000);
        if (isNaN(hari) || hari <= 0) {
            info.className = 'alert alert-success mt-3 mb-0';
            info.textContent = 'Tepat waktu, tidak ada denda.';
            return;
        }
        var total = hari * DENDA_PER_HARI * cur.n;
        info.className = 'alert alert-danger mt-3 mb-0';
        info.textContent = 'Terlambat ' + hari + ' hari. Estimasi denda: Rp ' + total.toLocaleString('id-ID') + '.';
    }

    $('#tabel-pengembalian').on('click', '.btn-kembali', function () {
        var d = $(this).data();
        cur = { jatuhTempo: d.jatuhTempo, n: parseInt(d.n, 10) || 1 };
        form.action = d.url;
        document.getElementById('kembali-label').textContent = d.label;
        tgl.min   = d.pinjam;
        tgl.value = iso(new Date());
        updateInfo();
        modal.show();
    });

    tgl.addEventListener('change', updateInfo);
});
</script>