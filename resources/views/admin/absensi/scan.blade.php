{{-- resources/views/admin/absensi/scan.blade.php --}}
@extends('admin.master')
@section('title', 'Scan QR Absensi')
@section('page-title', 'Scan QR Absensi Magang')

@section('content')
<div class="card">
    <div class="card-body">

        <div class="alert alert-info">
            Arahkan kamera ke QR milik peserta magang. Setelah terbaca, sistem akan otomatis mencatat absensi hari ini.
        </div>

        <div class="row">
            <div class="col-md-7">
                <div id="reader" style="width:100%; max-width: 600px;"></div>
            </div>
            <div class="col-md-5">
                <h5>Hasil Scan</h5>
                <div id="scan-result" class="mb-3">
                    <div class="text-muted">Belum ada hasil.</div>
                </div>

                <button id="btn-reset" class="btn btn-secondary btn-sm" disabled>Reset Scanner</button>
            </div>
        </div>

    </div>
</div>

{{-- html5-qrcode CDN --}}
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const readerEl = document.getElementById('reader');
    const resultEl = document.getElementById('scan-result');
    const btnReset = document.getElementById('btn-reset');

    let isProcessing = false;
    let html5QrcodeScanner;

    function showMessage(html, type = 'info') {
        resultEl.innerHTML = `<div class="alert alert-${type}">${html}</div>`;
    }

    function postPayload(payload) {
        if (isProcessing) return;
        isProcessing = true;
        btnReset.disabled = false;

        showMessage('Memproses…', 'secondary');

        fetch("{{ route('admin.absensi.scan.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ payload })
        })
        .then(async res => {
            const data = await res.json();
            if (res.ok && data.ok) {
                showMessage(
                    `<strong>${data.message}</strong><br>
                     Nama: ${data.data.name}<br>
                     Posisi: ${data.data.posisi}<br>
                     Tanggal: ${data.data.tanggal}<br>
                     Waktu: ${data.data.waktu}`,
                    'success'
                );
            } else {
                const msg = data && data.message ? data.message : 'Terjadi kesalahan';
                let extra = '';
                if (data && data.data) {
                    extra = `<br>Nama: ${data.data.name ?? '-'}<br>Posisi: ${data.data.posisi ?? '-'}<br>Tanggal: ${data.data.tanggal ?? '-'}<br>Waktu: ${data.data.waktu ?? '-'}`;
                }
                showMessage(`<strong>${msg}</strong>${extra}`, 'warning');
            }
        })
        .catch(err => {
            console.error(err);
            showMessage('Gagal mengirim data ke server.', 'danger');
        })
        .finally(() => {
            // Hentikan kamera agar tidak scanning berulang-ulang setelah satu hasil
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear().catch(() => {});
            }
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        showMessage(`QR terbaca: <code>${decodedText}</code>`, 'info');
        postPayload(decodedText);
    }

    function onScanFailure(error) {
        // abaikan noise
    }

    function startScanner() {
        isProcessing = false;
        showMessage('Siap untuk scan…', 'secondary');

        html5QrcodeScanner = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: 250 };

        Html5Qrcode.getCameras().then(cameras => {
            const cameraId = cameras && cameras[0] ? cameras[0].id : null;
            if (!cameraId) {
                showMessage('Kamera tidak ditemukan.', 'danger');
                return;
            }
            html5QrcodeScanner.start(
                cameraId,
                config,
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                console.error(err);
                showMessage('Gagal mengakses kamera.', 'danger');
            });
        }).catch(err => {
            console.error(err);
            showMessage('Tidak dapat mengakses daftar kamera.', 'danger');
        });
    }

    btnReset.addEventListener('click', function () {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(() => {
                startScanner();
                btnReset.disabled = true;
            }).catch(() => {
                startScanner();
                btnReset.disabled = true;
            });
        } else {
            startScanner();
            btnReset.disabled = true;
        }
    });

    // Mulai
    startScanner();
});
</script>
@endsection
