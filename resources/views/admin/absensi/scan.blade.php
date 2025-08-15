@extends('admin.master')
@section('title', 'Scan QR Presensi')
@section('page-title', 'Scan QR Presensi Magang')

@section('content')
<div class="card">
    <div class="card-body">

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

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const resultEl  = document.getElementById('scan-result');
    const btnReset  = document.getElementById('btn-reset');
    const scanStoreUrl = "{{ route('admin.absensi.scan.store', [], false) }}";

    let isProcessing = false;
    let scanner = null;
    let currentCameraId = null;

    function showMessage(html, type = 'info') {
        resultEl.innerHTML = `<div class="alert alert-${type} mb-2">${html}</div>`;
    }

    async function stopScanner() {
        if (!scanner) return;
        try { if (scanner.isScanning) await scanner.stop(); } catch (e) {}
        try { await scanner.clear(); } catch (e) {}
    }

    async function startScanner() {
        isProcessing = false;
        btnReset.disabled = true;
        showMessage('Siap untuk scan…', 'secondary');

        if (!scanner) {
            scanner = new Html5Qrcode("reader", { verbose: false });
        } else {
            await stopScanner();
        }

        try {
            const cameras = await Html5Qrcode.getCameras();
            if (!cameras || cameras.length === 0) {
                showMessage('Kamera tidak ditemukan.', 'danger');
                return;
            }
            currentCameraId = cameras[0].id;
            const config = { fps: 10, qrbox: 250 };

            await scanner.start(
                { deviceId: { exact: currentCameraId } },
                config,
                onScanSuccess,
                () => {} // ignore failures
            );
        } catch (err) {
            console.error(err);
            showMessage('Gagal mengakses kamera.', 'danger');
        }
    }

    async function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;
        btnReset.disabled = false;

        await stopScanner(); // cegah double-scan

        // Kirim langsung ke server, tanpa menampilkan payload
        postPayload(decodedText);
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
                     Jenis: <b>${data.data.jenis}</b><br>
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
                    extra = `<br>Jenis: <b>${data.data.jenis ?? '-'}</b><br>Nama: ${data.data.name ?? '-'}<br>Posisi: ${data.data.posisi ?? '-'}<br>Tanggal: ${data.data.tanggal ?? '-'}<br>Waktu: ${data.data.waktu ?? '-'}`;
                }
                showMessage(`<strong>${msg}</strong>${extra}`, 'warning');
            }
        })
        .catch(err => {
            console.error(err);
            showMessage('Gagal mengirim data ke server.', 'danger');
        });
    }

    btnReset.addEventListener('click', async function () { await startScanner(); });

    startScanner();
});
</script>
@endsection
