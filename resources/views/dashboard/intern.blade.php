@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="slide-up">
        <!-- Welcome Banner -->
        <div class="card mb-6"
            style="background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(168, 85, 247, 0.2)); border-color: rgba(99, 102, 241, 0.3);">
            <div class="d-flex justify-between align-center" style="flex-wrap: wrap; gap: 20px;">
                <div>
                    <h2 style="font-size: 28px; margin-bottom: 8px;">
                        Selamat Datang, {{ auth()->user()->name }}! 👋
                    </h2>
                    <p class="text-muted">
                        {{ $intern->school }} - {{ $intern->department }}
                    </p>
                    <p class="text-muted" style="margin-top: 8px;">
                        <i class="fas fa-calendar"></i> Periode: {{ $intern->start_date->format('d M Y') }} -
                        {{ $intern->end_date->format('d M Y') }}
                    </p>
                </div>
                <div class="d-flex gap-2">
                    @if(!$todayAttendance)
                        <form action="{{ route('attendance.checkIn') }}" method="POST" id="checkInForm">
                            @csrf
                            <input type="hidden" name="latitude" id="lat">
                            <input type="hidden" name="longitude" id="lon">
                            <input type="hidden" name="late_reason" id="lateReasonInput">
                            <button type="submit" id="checkInBtn" class="btn btn-secondary" disabled>
                                <i class="fas fa-spinner fa-spin"></i> Menunggu Lokasi...
                            </button>
                        </form>

                        <!-- Late Reason Modal -->
                        <div id="lateReasonModal" class="modal-overlay"
                            style="display: {{ session('show_late_reason_form') ? 'flex' : 'none' }};">
                            <div class="modal-content" style="max-width: 500px;">
                                <div class="modal-header">
                                    <h3><i class="fas fa-clock text-warning"></i> Anda Terlambat!</h3>
                                </div>
                                <div class="modal-body">
                                    <p class="text-muted mb-4">Anda datang lewat dari jam toleransi. Silakan masukkan alasan
                                        keterlambatan untuk melanjutkan check-in.</p>
                                    <div class="form-group">
                                        <label class="form-label">Alasan Keterlambatan *</label>
                                        <textarea id="lateReasonText" class="form-control" rows="3"
                                            placeholder="Contoh: Macet di jalan, kendaraan mogok, hujan deras..."
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex gap-3">
                                    <button type="button" id="cancelLateBtn" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Batal
                                    </button>
                                    <button type="button" id="submitLateBtn" class="btn btn-primary">
                                        <i class="fas fa-check"></i> Kirim & Check In
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif(!$todayAttendance->check_out)
                        <form action="{{ route('attendance.checkOut') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-sign-out-alt"></i> Check Out
                            </button>
                        </form>
                    @else
                        <span class="badge badge-success" style="padding: 12px 24px; font-size: 14px;">
                            <i class="fas fa-check"></i> Presensi Lengkap Hari Ini
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Map & Location -->
        <div class="card mb-6">
            <div class="card-header border-0 pb-0">
                <h3 class="card-title"><i class="fas fa-map-marked-alt text-danger"></i> Area Presensi</h3>
                <small class="text-muted">Masuk ke dalam lingkaran merah untuk check-in (Max
                    {{ $maxDist ?? 100 }}m).</small>
            </div>
            <div class="card-body">
                <div id="map"></div>
                <div class="d-flex justify-between align-center mt-3 p-3 bg-tertiary rounded"
                    style="background: var(--bg-tertiary); border: 1px solid var(--border-color);">
                    <div>
                        <div class="text-xs text-muted">Jarak ke Kantor</div>
                        <div id="distance-val" class="text-lg fw-bold" style="font-size: 1.25rem;">-- m</div>
                    </div>
                    <div id="gps-status" class="badge badge-secondary">Menunggu GPS...</div>
                </div>
            </div>
        </div>

        <!-- Task Submission Stats -->
        <div class="card mb-6">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistik Pengumpulan Tugas</h3>
            </div>
            <div class="submission-stats">
                <div class="submission-stat-item on-time">
                    <div class="stat-number">{{ $taskStats['completed_on_time'] }}</div>
                    <div class="stat-label">Tepat Waktu</div>
                    <div class="stat-icon-bg"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="submission-stat-item late">
                    <div class="stat-number">{{ $taskStats['completed_late'] }}</div>
                    <div class="stat-label">Terlambat</div>
                    <div class="stat-icon-bg"><i class="fas fa-clock"></i></div>
                </div>
                <div class="submission-stat-item pending">
                    <div class="stat-number">{{ $taskStats['in_progress'] + $taskStats['pending'] }}</div>
                    <div class="stat-label">Dalam Proses</div>
                    <div class="stat-icon-bg"><i class="fas fa-spinner"></i></div>
                </div>
                <div class="submission-stat-item overdue">
                    <div class="stat-number">{{ $taskStats['overdue'] }}</div>
                    <div class="stat-label">Tenggat Lewat</div>
                    <div class="stat-icon-bg"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>

            <div class="mt-6" style="padding: 0 20px 20px;">
                <div class="d-flex justify-between mb-2">
                    <span>Tingkat Pengumpulan Tepat Waktu</span>
                    <strong>{{ $onTimeRate }}%</strong>
                </div>
                <div class="progress" style="height: 12px;">
                    <div class="progress-bar bg-success" style="width: {{ $onTimeRate }}%;"></div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon primary">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-value">{{ $completedTasks }} / {{ $totalTasks }}</div>
                <div class="stat-label">Tugas Selesai</div>
                <div class="progress mt-4">
                    <div class="progress-bar"
                        style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon success">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value">{{ $attendancePercentage }}%</div>
                <div class="stat-label">Tingkat Kehadiran</div>
                <div class="progress mt-4">
                    <div class="progress-bar"
                        style="width: {{ $attendancePercentage }}%; background: linear-gradient(90deg, #22c55e, #16a34a);">
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon info">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <div class="stat-value">{{ $averageSpeed }}%</div>
                <div class="stat-label">Kecepatan Kerja</div>
                <div class="progress mt-4">
                    <div class="progress-bar"
                        style="width: {{ min($averageSpeed, 100) }}%; background: linear-gradient(90deg, #06b6d4, #0891b2);">
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value">{{ $overallScore }}</div>
                <div class="stat-label">Skor Rata-rata</div>
                <div class="progress mt-4">
                    <div class="progress-bar"
                        style="width: {{ $overallScore }}%; background: linear-gradient(90deg, #f59e0b, #d97706);"></div>
                </div>
            </div>
        </div>

        <div class="grid-2">
            <!-- My Tasks -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks"></i> Tugas Saya
                    </h3>
                    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-secondary">
                        Lihat Semua
                    </a>
                </div>

                @if($tasks->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h4 class="empty-state-title">Tidak Ada Tugas</h4>
                        <p class="empty-state-text">Anda belum memiliki tugas yang diberikan.</p>
                    </div>
                @else
                    @foreach($tasks as $task)
                        <div class="task-item"
                            style="padding: 16px; border: 1px solid var(--border-color); border-radius: var(--radius-md); margin-bottom: 12px; transition: all 0.3s;">
                            <div class="d-flex justify-between align-center" style="margin-bottom: 8px;">
                                <div>
                                    <strong>{{ Str::limit($task->title, 30) }}</strong>
                                    @if($task->is_late && $task->status === 'completed')
                                        <span class="badge badge-warning" style="margin-left: 8px; font-size: 10px;">Terlambat</span>
                                    @endif
                                </div>
                                <span class="badge badge-{{ $task->priority_color }}">{{ ucfirst($task->priority) }}</span>
                            </div>

                            <div class="d-flex justify-between align-center mb-3">
                                <span class="badge badge-{{ $task->status_color }}">
                                    {{ $task->status_label }}
                                </span>

                                <span class="text-muted" style="font-size: 11px;">
                                    @if($task->submission_type === 'github')
                                        <i class="fab fa-github"></i> Via GitHub
                                    @elseif($task->submission_type === 'file')
                                        <i class="fas fa-folder"></i> Via Upload
                                    @else
                                        <i class="fas fa-layer-group"></i> GitHub/File
                                    @endif
                                </span>
                            </div>

                            <div class="text-muted mb-3" style="font-size: 12px;">
                                @if($task->deadline)
                                    <i class="fas fa-clock"></i>
                                    {{ $task->deadline->format('d M Y') }}
                                    @if($task->deadline_time)
                                        {{ $task->deadline_time }}
                                    @endif
                                    @if($task->isOverdue())
                                        <span class="text-danger fw-bold">(Lewat!)</span>
                                    @endif
                                @else
                                    <span>-</span>
                                @endif
                            </div>

                            <div class="d-flex justify-between align-center gap-2">
                                @if($task->status !== 'completed')
                                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="d-flex"
                                        style="flex: 1;">
                                        @csrf
                                        @if($task->status === 'pending')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="btn btn-sm btn-secondary w-100">
                                                <i class="fas fa-play"></i> Mulai
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary w-100" disabled>
                                                <i class="fas fa-spinner"></i> Dikerjakan
                                            </button>
                                        @endif
                                    </form>

                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-primary" style="flex: 1;">
                                        <i class="fas fa-paper-plane"></i> Kumpulkan
                                    </a>
                                @else
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-success w-100">
                                        <i class="fas fa-check"></i> Selesai (Detail)
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Recent Attendance -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt"></i> Riwayat Presensi
                    </h3>
                    <a href="{{ route('attendances.index') }}" class="btn btn-sm btn-secondary">
                        Lihat Semua
                    </a>
                </div>

                @if($attendances->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h4 class="empty-state-title">Belum Ada Riwayat</h4>
                        <p class="empty-state-text">Anda belum memiliki riwayat presensi.</p>
                    </div>
                @else
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Check In</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date->format('d M Y') }}</td>
                                        <td>{{ $attendance->check_in ?? '-' }}</td>
                                        <td>
                                            <span class="badge badge-{{ $attendance->status_color }}">
                                                {{ $attendance->status_label }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #map {
                height: 300px;
                width: 100%;
                border-radius: var(--radius-md);
                margin-bottom: 24px;
                border: 2px solid var(--border-color);
                z-index: 1;
            }

            .submission-stats {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 16px;
                padding: 0 20px 20px;
            }

            @media (max-width: 768px) {
                .submission-stats {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            .submission-stat-item {
                position: relative;
                padding: 24px;
                border-radius: var(--radius-md);
                overflow: hidden;
                text-align: center;
                background: var(--bg-tertiary);
                border: 1px solid var(--border-color);
            }

            /* Solid borders for stats, no gradients */
            .submission-stat-item.on-time {
                border-bottom: 4px solid var(--success);
            }

            .submission-stat-item.late {
                border-bottom: 4px solid var(--warning);
            }

            .submission-stat-item.pending {
                border-bottom: 4px solid var(--accent-primary);
            }

            .submission-stat-item.overdue {
                border-bottom: 4px solid var(--danger);
            }

            .submission-stat-item .stat-number {
                font-size: 36px;
                font-weight: 800;
                line-height: 1.2;
                margin-bottom: 8px;
            }

            .submission-stat-item.on-time .stat-number {
                color: var(--success);
            }

            .submission-stat-item.late .stat-number {
                color: var(--warning);
            }

            .submission-stat-item.pending .stat-number {
                color: var(--accent-primary);
            }

            .submission-stat-item.overdue .stat-number {
                color: var(--danger);
            }

            .submission-stat-item .stat-label {
                font-size: 13px;
                font-weight: 600;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .submission-stat-item .stat-icon-bg {
                position: absolute;
                top: 10px;
                right: 10px;
                font-size: 24px;
                opacity: 0.2;
            }

            .submission-stat-item.on-time .stat-icon-bg {
                color: var(--success);
            }

            .submission-stat-item.late .stat-icon-bg {
                color: var(--warning);
            }

            .submission-stat-item.pending .stat-icon-bg {
                color: var(--accent-primary);
            }

            .submission-stat-item.overdue .stat-icon-bg {
                color: var(--danger);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            // Config from Controller (Blade injection)
            // Jika null, fallback ke default sendangmulyo
            const officeLat = {{ $officeLat ?? -7.052683 }};
            const officeLon = {{ $officeLon ?? 110.469375 }};
            const maxDist = {{ $maxDist ?? 100 }}; // meters

            // Initialize Map
            const map = L.map('map').setView([officeLat, officeLon], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Office Marker & Circle (Red Zone)
            const officeMarker = L.marker([officeLat, officeLon]).addTo(map)
                .bindPopup("<b>Kantor PT. DUTA SOLUSI INFORMATIKA</b><br>Titik Pusat Presensi").openPopup();

            const officeCircle = L.circle([officeLat, officeLon], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.15,
                radius: maxDist
            }).addTo(map);

            // User Marker
            let userMarker;
            let checkInBtn = document.getElementById('checkInBtn');
            let latInput = document.getElementById('lat');
            let lonInput = document.getElementById('lon');
            let distVal = document.getElementById('distance-val');
            let gpsStatus = document.getElementById('gps-status');

            function updateStatus(isInside, dist) {
                if (distVal) distVal.innerText = Math.round(dist) + " m";

                if (!checkInBtn) return; // Jika tombol gak ada (sudah checkin)

                if (isInside) {
                    checkInBtn.disabled = false;
                    checkInBtn.classList.remove('btn-secondary');
                    checkInBtn.classList.add('btn-success');
                    checkInBtn.innerHTML = '<i class="fas fa-map-marker-alt"></i> CHECK IN SEKARANG';

                    if (gpsStatus) {
                        gpsStatus.className = "badge badge-success";
                        gpsStatus.innerText = "Dalam Jangkauan";
                    }
                } else {
                    checkInBtn.disabled = true;
                    checkInBtn.classList.remove('btn-success');
                    checkInBtn.classList.add('btn-secondary');
                    checkInBtn.innerHTML = '<i class="fas fa-ban"></i> Diluar Jangkauan';

                    if (gpsStatus) {
                        gpsStatus.className = "badge badge-danger";
                        gpsStatus.innerText = "Terlalu Jauh";
                    }
                }
            }

            // Geolocation Watch
            if (navigator.geolocation) {
                if (gpsStatus) gpsStatus.innerText = "Mencari Lokasi...";

                navigator.geolocation.watchPosition((position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Update Inputs
                    if (latInput) latInput.value = lat;
                    if (lonInput) lonInput.value = lng;

                    // Update Map Marker for User
                    if (userMarker) {
                        userMarker.setLatLng([lat, lng]);
                    } else {
                        userMarker = L.marker([lat, lng], {
                            title: "Lokasi Kamu"
                        }).addTo(map).bindPopup("Lokasi Kamu").openPopup();

                        // First time found, fit bounds to show both if needed
                        // const group = new L.featureGroup([officeMarker, userMarker]);
                        // map.fitBounds(group.getBounds().pad(0.2));
                    }

                    // Calculate Distance (Leaflet method)
                    const dist = map.distance([officeLat, officeLon], [lat, lng]);
                    updateStatus(dist <= maxDist, dist);

                }, (error) => {
                    console.error(error);
                    let errorMessage = "Terjadi kesalahan pada GPS.";

                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = "Akses lokasi ditolak! Harap klik ikon 'Gembok' atau 'Pengaturan Situs' di pojok kiri atas browser Anda, lalu izinkan akses Lokasi (Location).";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = "Informasi lokasi tidak tersedia. Pastikan GPS di perangkat Anda sudah AKTIF.";
                            break;
                        case error.TIMEOUT:
                            errorMessage = "Waktu habis saat mencari lokasi. Sinyal GPS mungkin lemah. Coba refresh halaman.";
                            break;
                        default:
                            errorMessage = "Terjadi kesalahan tidak dikenal (Error Code: " + error.code + ").";
                            break;
                    }

                    if (gpsStatus) {
                        gpsStatus.className = "badge badge-danger";
                        gpsStatus.innerText = "GPS Gagal";
                    }
                    if (distVal) distVal.innerText = "Error";

                    // Tampilkan Alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Akses GPS Bermasalah',
                        text: errorMessage,
                        confirmButtonText: 'Saya Mengerti',
                        footer: '<small>Pastikan Anda mengakses via localhost atau HTTPS</small>'
                    });
                }, {
                    enableHighAccuracy: false, // Ubah ke false agar lebih stabil di Laptop/PC (via WiFi)
                    maximumAge: 30000,         // Simpan cache lokasi 30 detik
                    timeout: 30000             // Tunggu maksimal 30 detik
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
            // Late Reason Modal Handlers
            const lateModal = document.getElementById('lateReasonModal');
            const cancelLateBtn = document.getElementById('cancelLateBtn');
            const submitLateBtn = document.getElementById('submitLateBtn');
            const lateReasonText = document.getElementById('lateReasonText');
            const lateReasonInput = document.getElementById('lateReasonInput');
            const checkInFormEl = document.getElementById('checkInForm');

            if (cancelLateBtn) {
                cancelLateBtn.addEventListener('click', function () {
                    lateModal.style.display = 'none';
                });
            }

            if (submitLateBtn) {
                submitLateBtn.addEventListener('click', function () {
                    const reason = lateReasonText.value.trim();
                    if (!reason) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Alasan Diperlukan',
                            text: 'Silakan masukkan alasan keterlambatan Anda.',
                        });
                        return;
                    }

                    // Set the reason and submit
                    lateReasonInput.value = reason;
                    lateModal.style.display = 'none';
                    checkInFormEl.submit();
                });
            }

            // Close modal on overlay click
            if (lateModal) {
                lateModal.addEventListener('click', function (e) {
                    if (e.target === lateModal) {
                        lateModal.style.display = 'none';
                    }
                });
            }
        </script>
    @endpush
@endsection
