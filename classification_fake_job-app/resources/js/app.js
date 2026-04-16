import './bootstrap';

// Daftarkan ke 'window' agar bisa dibaca oleh file Blade (HTML)
window.previewFile = function() {
    const file = document.getElementById('job_file').files[0];
    const previewContainer = document.getElementById('preview-container');
    const placeholder = document.getElementById('placeholder-content');
    const previewImage = document.getElementById('preview-image');
    const previewVideo = document.getElementById('preview-video');

    if (file) {
        previewContainer.classList.remove('hidden');
        placeholder.classList.add('hidden');

        const reader = new FileReader();
        reader.onload = function(e) {
            if (file.type.startsWith('image/')) {
                previewImage.src = e.target.result;
                previewImage.classList.remove('hidden');
                previewVideo.classList.add('hidden');
            } else if (file.type.startsWith('video/')) {
                previewVideo.src = e.target.result;
                previewVideo.classList.remove('hidden');
                previewImage.classList.add('hidden');
            }
        }
        reader.readAsDataURL(file);
    }
}

window.resetFile = function(event) {
    event.stopPropagation(); // Mencegah klik tombol hapus memicu jendela pilih file lagi
    document.getElementById('job_file').value = "";
    document.getElementById('preview-container').classList.add('hidden');
    document.getElementById('placeholder-content').classList.remove('hidden');
}

// Efek loading saat form dikirim
document.querySelector('form').addEventListener('submit', function() {
    const btn = document.getElementById('submit-btn');
    const text = document.getElementById('btn-text');
    const loading = document.getElementById('btn-loading');

    // Ubah tampilan jadi muter-muter
    text.classList.add('hidden');
    loading.classList.remove('hidden');
    btn.classList.add('opacity-75', 'cursor-not-allowed');

    // JURUS RAHASIA: Kasih jeda sebelum tombol dimatikan
    // Supaya browser tetap sempat mengirim data form-nya
    setTimeout(() => {
        btn.disabled = true;
    }, 50);
});