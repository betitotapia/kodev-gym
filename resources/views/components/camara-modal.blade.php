<div id="modal-camara"
    style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.85);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:20px;width:90%;max-width:480px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
            <p style="font-size:15px;font-weight:600;color:#111827;margin:0;">Tomar fotografía</p>
            <button type="button" onclick="cerrarCamaraFilament()"
                style="background:none;border:none;cursor:pointer;font-size:22px;color:#6b7280;line-height:1;">×</button>
        </div>

        <div style="border-radius:12px;overflow:hidden;background:#000;margin-bottom:12px;">
            <video id="camara-video" autoplay playsinline
                style="width:100%;max-height:300px;display:block;"></video>
            <img id="camara-preview" style="width:100%;max-height:300px;display:none;border-radius:12px;" />
        </div>

        <canvas id="camara-canvas" style="display:none;"></canvas>

        <div style="display:flex;gap:8px;justify-content:center;">
            <button id="btn-capturar" type="button" onclick="capturarFoto()"
                style="padding:10px 24px;border-radius:10px;background:#f59e0b;color:#fff;border:none;font-size:14px;font-weight:600;cursor:pointer;">
                Capturar
            </button>
            <button id="btn-repetir" type="button" onclick="repetirFoto()" style="display:none;padding:10px 24px;border-radius:10px;background:#6b7280;color:#fff;border:none;font-size:14px;font-weight:500;cursor:pointer;">
                Repetir
            </button>
            <button id="btn-usar" type="button" onclick="usarFotoCapturada()" style="display:none;padding:10px 24px;border-radius:10px;background:#16a34a;color:#fff;border:none;font-size:14px;font-weight:600;cursor:pointer;">
                Usar esta foto
            </button>
        </div>
    </div>
</div>

<script>
let camaraStream = null;

async function abrirCamaraFilament() {
    try {
        camaraStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: 640, height: 480 },
            audio: false
        });
        const video = document.getElementById('camara-video');
        video.srcObject = camaraStream;
        video.style.display = 'block';
        document.getElementById('camara-preview').style.display = 'none';
        document.getElementById('btn-capturar').style.display = 'inline-block';
        document.getElementById('btn-repetir').style.display = 'none';
        document.getElementById('btn-usar').style.display = 'none';
    } catch (e) {
        alert('No se pudo acceder a la cámara: ' + e.message);
        document.getElementById('modal-camara').style.display = 'none';
    }
}

function capturarFoto() {
    const video   = document.getElementById('camara-video');
    const canvas  = document.getElementById('camara-canvas');
    const preview = document.getElementById('camara-preview');

    let w = video.videoWidth, h = video.videoHeight;
    const max = 800;
    if (w > max) { h = Math.round(h * max / w); w = max; }

    canvas.width = w; canvas.height = h;
    canvas.getContext('2d').drawImage(video, 0, 0, w, h);

    preview.src = canvas.toDataURL('image/jpeg', 0.85);
    preview.style.display = 'block';
    video.style.display = 'none';

    document.getElementById('btn-capturar').style.display = 'none';
    document.getElementById('btn-repetir').style.display = 'inline-block';
    document.getElementById('btn-usar').style.display = 'inline-block';
}

function repetirFoto() {
    document.getElementById('camara-video').style.display = 'block';
    document.getElementById('camara-preview').style.display = 'none';
    document.getElementById('btn-capturar').style.display = 'inline-block';
    document.getElementById('btn-repetir').style.display = 'none';
    document.getElementById('btn-usar').style.display = 'none';
}

function usarFotoCapturada() {
    const dataUrl = document.getElementById('camara-preview').src;

    // Convertir a File y asignarlo al FileUpload de Filament
    fetch(dataUrl)
        .then(r => r.blob())
        .then(blob => {
            const file = new File([blob], 'foto-' + Date.now() + '.jpg', { type: 'image/jpeg' });
            const dt   = new DataTransfer();
            dt.items.add(file);

            // Buscar el input file del campo fotografía
            const inputs = document.querySelectorAll('input[type="file"]');
            let asignado = false;
            inputs.forEach(input => {
                if (!asignado) {
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    asignado = true;
                }
            });

            if (asignado) {
                cerrarCamaraFilament();
            } else {
                alert('No se encontró el campo de fotografía. Asegúrate de estar en el formulario del socio.');
            }
        });
}

function cerrarCamaraFilament() {
    if (camaraStream) {
        camaraStream.getTracks().forEach(t => t.stop());
        camaraStream = null;
    }
    document.getElementById('modal-camara').style.display = 'none';
}
</script>