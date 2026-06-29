<section class="tab-pane fade" id="tab-laporan" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">RDKK Pupuk Bersubsidi</p>
            <h1>Cetak Laporan</h1>
        </div>
    </div>

    <article class="admin-card rdkk-controls">
        <div class="admin-title-inline mb-3">
            <h2>Identitas Laporan</h2>
            <button class="btn btn-success px-4" type="button" data-admin-print-report>Cetak Laporan</button>
        </div>

        <div class="row g-3">
            <div class="col-sm-4 col-lg-2">
                <label class="form-label">
                    Tahun
                    <input class="form-control" type="number" min="2020" max="2100" value="{{ now()->year }}" data-rdkk-field="year">
                </label>
            </div>
            <div class="col-sm-8 col-lg-5">
                <label class="form-label">
                    Kecamatan
                    <input class="form-control" type="text" maxlength="150" data-rdkk-field="district">
                </label>
            </div>
            <div class="col-lg-5">
                <label class="form-label">
                    Desa/Kelurahan
                    <input class="form-control" type="text" maxlength="150" data-rdkk-field="village">
                </label>
            </div>
            <div class="col-md-6 col-lg-4">
                <label class="form-label">
                    Kelompok Tani
                    <input class="form-control" type="text" maxlength="150" data-rdkk-field="group">
                </label>
            </div>
            <div class="col-md-6 col-lg-3">
                <label class="form-label">
                    Subsektor
                    <input class="form-control" type="text" maxlength="100" value="Tanaman Pangan" data-rdkk-field="subsector">
                </label>
            </div>
            <div class="col-md-6 col-lg-2">
                <label class="form-label">
                    Komoditas
                    <input class="form-control" type="text" maxlength="100" value="Padi" data-rdkk-field="commodity">
                </label>
            </div>
            <div class="col-md-6 col-lg-3">
                <label class="form-label">
                    Kios
                    <input class="form-control" type="text" maxlength="150" data-rdkk-field="kiosk">
                </label>
            </div>
        </div>
    </article>

    <article class="rdkk-print-area" data-rdkk-print-area>
        <div class="rdkk-report">
            <header class="rdkk-report-header">
                <h2>Rencana Definitif Kebutuhan Kelompok (RDKK) Pupuk Bersubsidi Tahun <span data-rdkk-year>{{ now()->year }}</span></h2>
            </header>

            <dl class="rdkk-meta">
                <div><dt>Kecamatan</dt><dd data-rdkk-meta="district">-</dd></div>
                <div><dt>Desa/Kelurahan</dt><dd data-rdkk-meta="village">-</dd></div>
                <div><dt>Kelompok Tani</dt><dd data-rdkk-meta="group">-</dd></div>
                <div><dt>Subsektor</dt><dd data-rdkk-meta="subsector">Tanaman Pangan</dd></div>
                <div><dt>Komoditas</dt><dd data-rdkk-meta="commodity">Padi</dd></div>
                <div><dt>Kios</dt><dd data-rdkk-meta="kiosk">-</dd></div>
            </dl>

            <div class="rdkk-table-frame">
                <table class="rdkk-table">
                    <thead>
                        <tr>
                            <th rowspan="2" class="rdkk-col-no">No</th>
                            <th rowspan="2" class="rdkk-col-nik">NIK</th>
                            <th rowspan="2" class="rdkk-col-name">Nama</th>
                            <th rowspan="2" class="rdkk-col-tanam">Rencana Tanam (Ha)</th>
                            <th colspan="5">Jumlah Pupuk Bersubsidi (Karung)</th>
                        </tr>
                        <tr>
                            <th>UREA</th>
                            <th>NPK</th>
                            <th>NPK FORMULA</th>
                            <th>ORGANIK</th>
                            <th>ZA</th>
                        </tr>
                    </thead>
                    <tbody data-rdkk-rows></tbody>
                    <tfoot data-rdkk-totals></tfoot>
                </table>
            </div>

            <footer class="rdkk-report-footer">
                Dicetak pada <span data-rdkk-print-date>-</span>
            </footer>
        </div>
    </article>
</section>
