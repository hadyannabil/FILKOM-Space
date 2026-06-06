import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    
    const notifBtn = document.getElementById('notif-btn');
    const notifDropdown = document.getElementById('notif-dropdown');
    
    const profileBtn = document.getElementById('profile-btn');
    const profileDropdown = document.getElementById('profile-dropdown');

    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', function() {
            notifDropdown.classList.toggle('hidden');
            // Tutup profil jika lonceng diklik
            if (profileDropdown) profileDropdown.classList.add('hidden'); 
        });
    }

    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', function() {
            profileDropdown.classList.toggle('hidden');
            // Tutup notifikasi jika profil diklik
            if (notifDropdown) notifDropdown.classList.add('hidden');
        });
    }

    document.addEventListener('click', function(event) {
        
        // Cek apakah klik berada di luar area notifikasi
        if (notifBtn && notifDropdown && !notifBtn.contains(event.target) && !notifDropdown.contains(event.target)) {
            notifDropdown.classList.add('hidden');
        }
        
        if (profileBtn && profileDropdown && !profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
            profileDropdown.classList.add('hidden');
        }
    });
    
});