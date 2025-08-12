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
    const readerEl  = document.getElementById('reader');
    const resultEl  = document.getElementById('scan-result');
    const btnReset  = document.getElementById('btn-reset');

    // PENTING: pakai URL relatif supaya ikut skema HTTPS dan tidak mixed-content
    const scanStoreUrl = "{{ route('admin.absensi.scan.store', [], false) }}"; // -> "/admin/absensi-magang/scan"

    let isProcessing = false;
    let scanner = null;         // instance Html5Qrcode
    let currentCameraId = null; // id kamera aktif

    function showMessage(html, type = 'info') {
        resultEl.innerHTML = `<div class="alert alert-${type} mb-2">${html}</div>`;
    }

    async function stopScanner() {
        if (!scanner) return;
        try {
            // Hentikan dulu scanning jika masih berjalan
            if (scanner.isScanning) {
                await scanner.stop();
            }
        } catch (e) {
            // abaikan
        }
        try {
            await scanner.clear();
        } catch (e) {
            // abaikan
        }
    }

    async function startScanner() {
        isProcessing = false;
        btnReset.disabled = true;
        showMessage('Siap untuk scan…', 'secondary');

        if (!scanner) {
            scanner = new Html5Qrcode("reader", { verbose: false });
        } else {
            // Pastikan canvas dibersihkan sebelum start ulang
            await stopScanner();
        }

        try {
            const cameras = await Html5Qrcode.getCameras();
            if (!cameras || cameras.length === 0) {
                showMessage('Kamera tidak ditemukan.', 'danger');
                return;
            }
            currentCameraId = cameras[0].id; // ambil kamera pertama
            const config = { fps: 10, qrbox: 250 };

            await scanner.start(
                { deviceId: { exact: currentCameraId } },
                config,
                onScanSuccess,
                onScanFailure
            );
        } catch (err) {
            console.error(err);
            showMessage('Gagal mengakses kamera.', 'danger');
        }
    }

    async function onScanSuccess(decodedText, decodedResult) {
        if (isProcessing) return;
        isProcessing = true;
        btnReset.disabled = false;

        showMessage(`QR terbaca: <code>${decodedText}</code>`, 'info');

        // STOP scanner lebih dulu agar tidak memicu scan berkali-kali
        await stopScanner();

        // Proses ke server
        postPayload(decodedText);
    }

    function onScanFailure(error) {
        // abaikan noise/false positive
    }

    function postPayload(payload) {
        showMessage('Memproses…', 'secondary');

        fetch(scanStoreUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({ payload })
        })
        .then(async (res) => {
            let data = null;
            try { data = await res.json(); } catch (_) {}

            if (res.ok && data && data.ok) {
                showMessage(
                    `<strong>${data.message}</strong><br>
                     Nama: ${data.data.name}<br>
                     Posisi: ${data.data.posisi}<br>
                     Tanggal: ${data.data.tanggal}<br>
                     Waktu: ${data.data.waktu}`,
                    'success'
                );
            } else {
                const msg = (data && data.message) ? data.message : 'Terjadi kesalahan';
                let extra = '';
                if (data && data.data) {
                    extra = `<br>Nama: ${data.data.name ?? '-'}<br>Posisi: ${data.data.posisi ?? '-'}<br>Tanggal: ${data.data.tanggal ?? '-'}<br>Waktu: ${data.data.waktu ?? '-'}`;
                }
                showMessage(`<strong>${msg}</strong>${extra}`, 'warning');
            }
        })
        .catch(err => {
            console.error(err);
            // Biasanya terjadi saat mixed-content/HTTPS, tapi kini sudah dihindari dengan URL relatif.
            showMessage('Gagal mengirim data ke server.', 'danger');
        });
    }

    btnReset.addEventListener('click', async function () {
        await startScanner();
    });

    // Mulai
    startScanner();
});
</script>
@endsection
