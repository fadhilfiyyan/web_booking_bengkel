
function validateForm() {
    clearErrors();
    
    let isValid = true;

    
    const nama = document.getElementById('nama').value.trim();
    if (nama === '') {
        showError('error-nama', 'Nama wajib diisi');
        isValid = false;
    } else if (nama.length < 3) {
        showError('error-nama', 'Nama minimal 3 karakter');
        isValid = false;
    }

   
    const hp = document.getElementById('hp').value.trim();
    const hpPattern = /^[0-9]{10,13}$/;
    if (hp === '') {
        showError('error-hp', 'Nomor HP wajib diisi');
        isValid = false;
    } else if (!hpPattern.test(hp)) {
        showError('error-hp', 'Nomor HP harus 10-13 digit angka');
        isValid = false;
    }

    const merek = document.getElementById('merek').value;
    if (merek === '') {
        showError('error-merek', 'Merek mobil wajib dipilih');
        isValid = false;
    }

    
    const model = document.getElementById('model').value.trim();
    if (model === '') {
        showError('error-model', 'Model/tipe mobil wajib diisi');
        isValid = false;
    }

    
    const tahun = document.getElementById('tahun').value;
    const currentYear = new Date().getFullYear();
    if (tahun === '') {
        showError('error-tahun', 'Tahun mobil wajib diisi');
        isValid = false;
    } else if (tahun < 1990 || tahun > currentYear + 1) {
        showError('error-tahun', `Tahun harus antara 1990 - ${currentYear + 1}`);
        isValid = false;
    }

   
    const jenisServis = document.getElementById('jenis_servis').value;
    if (jenisServis === '') {
        showError('error-jenis', 'Jenis servis wajib dipilih');
        isValid = false;
    }

    if (isValid) {
        const confirmMessage = `Konfirmasi Booking:\n\nNama: ${nama}\nHP: ${hp}\nMobil: ${merek} ${model} (${tahun})\nServis: ${jenisServis}\n\nLanjutkan booking?`;
        return confirm(confirmMessage);
    }

    return false;
}


function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
    }
}


function clearErrors() {
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(element => {
        element.textContent = '';
        element.style.display = 'none';
    });
}


function updateHarga() {
    const jenisServis = document.getElementById('jenis_servis').value;
    const hargaDisplay = document.getElementById('harga_display');
    const hargaHidden = document.getElementById('harga');
    
    let harga = 0;
    let hargaText = '';

    switch(jenisServis) {
        case 'Ganti Oli':
            harga = 150000;
            hargaText = 'Rp 150.000';
            break;
        case 'Tune Up':
            harga = 300000;
            hargaText = 'Rp 300.000';
            break;
        case 'Servis Ringan':
            harga = 500000;
            hargaText = 'Rp 500.000';
            break;
        case 'Servis Besar':
            harga = 1000000;
            hargaText = 'Rp 1.000.000';
            break;
        default:
            harga = 0;
            hargaText = '';
    }

    hargaDisplay.value = hargaText;
    hargaHidden.value = harga;
}


function resetHarga() {
    document.getElementById('harga_display').value = '';
    document.getElementById('harga').value = '';
    clearErrors();
}


function confirmDelete(id, nama) {
    const confirmMessage = `Apakah Anda yakin ingin menghapus booking atas nama:\n\n"${nama}"\n\nData yang dihapus tidak dapat dikembalikan!`;
    
    if (confirm(confirmMessage)) {
        window.location.href = `proses_hapus.php?id=${id}`;
    }
}


document.addEventListener('DOMContentLoaded', function() {

    const animatedElements = document.querySelectorAll('.fade-in, .fade-in-delay, .fade-in-delay-2, .fade-in-delay-3');
    
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        setTimeout(() => {
            element.style.transition = 'opacity 1s ease-out';
            element.style.opacity = '1';
        }, 100);
    });

  
    const firstInput = document.querySelector('input[type="text"]');
    if (firstInput) {
        firstInput.focus();
    }
});


const hpInput = document.getElementById('hp');
if (hpInput) {
    hpInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
}

const tahunInput = document.getElementById('tahun');
if (tahunInput) {
    tahunInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        

        if (this.value.length > 4) {
            this.value = this.value.slice(0, 4);
        }
    });
}


window.addEventListener('load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sukses = urlParams.get('sukses');
    const error = urlParams.get('error');
    
    if (sukses) {
        let message = '';
        switch(sukses) {
            case 'tambah':
                message = '✅ Booking berhasil ditambahkan!';
                break;
            case 'edit':
                message = '✅ Data booking berhasil diupdate!';
                break;
            case 'hapus':
                message = '✅ Data booking berhasil dihapus!';
                break;
        }
        
        if (message) {
            alert(message);
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }
    
    if (error) {
        let message = '';
        switch(error) {
            case 'tambah':
                message = '❌ Gagal menambahkan booking!';
                break;
            case 'edit':
                message = '❌ Gagal mengupdate data!';
                break;
            case 'hapus':
                message = '❌ Gagal menghapus data!';
                break;
            case 'koneksi':
                message = '❌ Koneksi database gagal!';
                break;
        }
        
        if (message) {
            alert(message);
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }


    window.addEventListener('load', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const sukses = urlParams.get('sukses');
        const kode = urlParams.get('kode');
        
        if (sukses && kode) {
            let message = '';
            if (sukses === 'tambah') {
                message = `✅ Booking berhasil!\n\nKode Akses Anda:\n${kode}\n\n⚠️ SIMPAN kode ini untuk cek status booking!`;
            }
            
            if (message) {
                alert(message);
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        }
    });
});
