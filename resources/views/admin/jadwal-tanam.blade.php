<section class="tab-pane fade" id="tab-jadwal-tanam" tabindex="0">
    <div class="admin-title">
        <div>
            <p class="text-success fw-bold mb-1">Perkembangan Tanam</p>
            <h1>Jadwal Tanam Petani</h1>
        </div>
    </div>

    <article class="admin-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Progress Petani</h2>
            <span class="badge text-bg-success" data-admin-planting-count>0 petani</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Petani</th>
                        <th>Luas Lahan</th>
                        <th>Proses Aktif</th>
                        <th>Progress</th>
                        <th>Tanggal Semai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody data-admin-planting-progress></tbody>
            </table>
        </div>
    </article>
</section>
