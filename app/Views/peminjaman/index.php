<?php
/**
 * Halaman Transaksi Peminjaman.
 * Data dari Peminjaman::index():
 *   $peminjamanList (peminjaman.* + nama_anggota, kelas, nama_petugas, items[])
 *      items[]: id_detail, id_buku, jumlah, tanggal_kembali, denda, judul, kode_buku
 *   $anggotaList    (id_anggota, nis, nama, kelas)
 *   $bukuList       (buku dengan stok > 0)
 */
$fmtTgl = static fn ($d) => $d ? date('d/m/Y', strtotime($d)) : '-';
$fmtRp  = static fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                    <h4 class="card-title mb-0">Daftar Transaksi Peminjaman</h4>
                    <button type="button" class="btn btn-warning waves-effect waves-light" id="btn-tambah">
                        <i class="mdi mdi-plus me-1"></i> Peminjaman Baru
                    </button>
                </div>

                <table id="tabel-peminjaman" class="table table-bordered table-striped dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Anggota</th>
                            <th>Tgl Pinjam</th>
                            <th>Jatuh Tempo</th>
                            <th>Buku</th>
                            <th>Petugas</th>
                            <th>Denda</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($peminjamanList as $i => $p): ?>
                            <?php
                            $totalDenda = 0;
                            foreach ($p['items'] as $it) {
                                $totalDenda += (float) ($it['denda'] ?? 0);
                            }
                            ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td>
                                    <?= esc($p['nama_anggota']) ?>
                                    <div class="text-muted font-size-12"><?= esc($p['kelas']) ?></div>
                                </td>
                                <td data-order="<?= esc($p['tanggal_pinjam'], 'attr') ?>"><?= $fmtTgl($p['tanggal_pinjam']) ?></td>
                                <td data-order="<?= esc($p['tanggal_jatuh_tempo'], 'attr') ?>"><?= $fmtTgl($p['tanggal_jatuh_tempo']) ?></td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($p['items'] as $it): ?>
                                            <li>
                                                <?= esc($it['judul']) ?>
                                                <span class="text-muted">&times; <?= (int) $it['jumlah'] ?></span>
                                                <?php if (! empty($it['tanggal_kembali'])): ?>
                                                    <span class="badge bg-soft-success text-success ms-1">
                                                        kembali <?= $fmtTgl($it['tanggal_kembali']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td><?= esc($p['nama_petugas']) ?></td>
                                <td data-order="<?= (float) $totalDenda ?>"><?= $totalDenda > 0 ? $fmtRp($totalDenda) : '-' ?></td>
                                <td class="text-center">
                                    <?php if ($p['status'] === 'selesai'): ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Dipinjam</span>
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

<!-- Modal Peminjaman Baru -->
<div class="modal fade" id="modal-peminjaman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" id="form-peminjaman" method="post" action="<?= base_url('peminjaman/store') ?>">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title">Peminjaman Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="id_anggota" class="form-label">Anggota <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_anggota" name="id_anggota" required>
                            <option value="">-- Pilih anggota --</option>
                            <?php foreach ($anggotaList as $a): ?>
                                <option value="<?= esc($a['id_anggota'], 'attr') ?>">
                                    <?= esc($a['nama']) ?> (<?= esc($a['nis']) ?> - <?= esc($a['kelas']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tgl Pinjam <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="tanggal_jatuh_tempo" class="form-label">Jatuh Tempo <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_jatuh_tempo" name="tanggal_jatuh_tempo" required>
                    </div>
                </div>

                <hr class="mt-0">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label mb-0">Buku yang dipinjam <span class="text-danger">*</span></label>
                    <button type="button" class="btn btn-sm btn-outline-warning waves-effect" id="btn-add-row"
                        <?= empty($bukuList) ? 'disabled' : '' ?>>
                        <i class="mdi mdi-plus me-1"></i> Tambah Buku
                    </button>
                </div>

                <?php if (empty($bukuList)): ?>
                    <div class="alert alert-warning mb-0">Tidak ada buku dengan stok tersedia.</div>
                <?php endif; ?>

                <div id="item-rows"></div>

                <div class="form-text">Satu baris = satu judul buku. Jumlah tidak boleh melebihi stok.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning waves-effect waves-light"
                    <?= empty($bukuList) ? 'disabled' : '' ?>>Simpan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<!-- Template baris buku -->
<template id="tpl-item">
    <div class="row g-2 item-row mb-2">
        <div class="col-md-8">
            <select name="id_buku[]" class="form-select sel-buku" required>
                <option value="">-- Pilih buku --</option>
                <?php foreach ($bukuList as $b): ?>
                    <option value="<?= esc($b['id_buku'], 'attr') ?>" data-stok="<?= (int) $b['stok'] ?>">
                        <?= esc($b['judul']) ?> (<?= esc($b['kode_buku']) ?>) - stok <?= (int) $b['stok'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-8 col-md-3">
            <input type="number" name="jumlah[]" class="form-control inp-jumlah" min="1" value="1" required>
        </div>
        <div class="col-4 col-md-1">
            <button type="button" class="btn btn-outline-danger w-100 btn-remove" title="Hapus baris">
                <i class="mdi mdi-close"></i>
            </button>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal   = new bootstrap.Modal(document.getElementById('modal-peminjaman'));
    var form    = document.getElementById('form-peminjaman');
    var rows    = document.getElementById('item-rows');
    var tpl     = document.getElementById('tpl-item');
    var tglPin  = document.getElementById('tanggal_pinjam');
    var tglTemp = document.getElementById('tanggal_jatuh_tempo');

    $('#tabel-peminjaman').DataTable({
        responsive: true,
        order: [],                       // pakai urutan dari server (tanggal pinjam terbaru)
        columnDefs: [{ orderable: false, targets: 4 }],
        language: {
            search: 'Cari:', lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data', zeroRecords: 'Data tidak ditemukan',
            emptyTable: 'Belum ada transaksi peminjaman',
            paginate: { previous: '&laquo;', next: '&raquo;' }
        }
    });

    function iso(d) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }
    function plusDays(isoStr, n) {
        var p = isoStr.split('-');
        return iso(new Date(+p[0], +p[1] - 1, +p[2] + n));
    }
    function addRow() { rows.appendChild(tpl.content.cloneNode(true)); }

    // Buka form baru: reset, tanggal default hari ini & jatuh tempo +7 hari
    document.getElementById('btn-tambah').addEventListener('click', function () {
        form.reset();
        rows.innerHTML = '';
        addRow();
        var today = iso(new Date());
        tglPin.value  = today;
        tglTemp.value = plusDays(today, 7);
        tglTemp.min   = today;
        modal.show();
    });

    // Ubah tgl pinjam -> jatuh tempo ikut +7 hari
    tglPin.addEventListener('change', function () {
        if (!tglPin.value) return;
        tglTemp.min   = tglPin.value;
        tglTemp.value = plusDays(tglPin.value, 7);
    });

    document.getElementById('btn-add-row').addEventListener('click', addRow);

    // Pilih buku -> batasi jumlah maksimal sesuai stok
    rows.addEventListener('change', function (e) {
        if (!e.target.classList.contains('sel-buku')) return;
        var opt = e.target.selectedOptions[0];
        var inp = e.target.closest('.item-row').querySelector('.inp-jumlah');
        var stok = opt ? parseInt(opt.getAttribute('data-stok'), 10) : NaN;
        if (stok) {
            inp.max = stok;
            if (+inp.value > stok) inp.value = stok;
        } else {
            inp.removeAttribute('max');
        }
    });

    // Hapus baris (minimal tersisa 1)
    rows.addEventListener('click', function (e) {
        var btn = e.target.closest('.btn-remove');
        if (btn && rows.querySelectorAll('.item-row').length > 1) {
            btn.closest('.item-row').remove();
        }
    });
});
</script>