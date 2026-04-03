import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    
    // Ambil semua elemen yang dibutuhkan
    const notifBtn = document.getElementById('notif-btn');
    const notifDropdown = document.getElementById('notif-dropdown');
    
    const profileBtn = document.getElementById('profile-btn');
    const profileDropdown = document.getElementById('profile-dropdown');

    // --- 1. Fungsi Klik Lonceng Notifikasi ---
    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', function() {
            notifDropdown.classList.toggle('hidden');
            // Tutup profil jika lonceng diklik
            if (profileDropdown) profileDropdown.classList.add('hidden'); 
        });
    }

    // --- 2. Fungsi Klik Profil ---
    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', function() {
            profileDropdown.classList.toggle('hidden');
            // Tutup notifikasi jika profil diklik
            if (notifDropdown) notifDropdown.classList.add('hidden');
        });
    }

    // --- 3. Fungsi Klik di Luar Menu (Untuk menutup keduanya) ---
    document.addEventListener('click', function(event) {
        
        // Cek apakah klik berada di luar area notifikasi
        if (notifBtn && notifDropdown && !notifBtn.contains(event.target) && !notifDropdown.contains(event.target)) {
            notifDropdown.classList.add('hidden');
        }
        
        // Cek apakah klik berada di luar area profil
        if (profileBtn && profileDropdown && !profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
    
});