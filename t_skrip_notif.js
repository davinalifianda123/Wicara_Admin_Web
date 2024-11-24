// Ambil notifikasi dari PHP
const notifications = JSON.parse(document.getElementById('notifications-data').textContent);

let allNotificationsVisible = false;

// Fungsi untuk mengisi notifikasi
function populateNotifications(type) {
    const container = document.getElementById('notifications');
    const showAllButtonContainer = document.getElementById('showAllButtonContainer');
    const showAllButton = document.getElementById('showAllButton');

    container.innerHTML = '';

    const filteredNotifications = type === 'semua'
        ? notifications
        : notifications.filter(notification => notification.type === type);

    // Urutkan berdasarkan waktu terbaru
    filteredNotifications.sort((a, b) => new Date(b.raw_time) - new Date(a.raw_time));

    const maxNotificationsToShow = 7;
    const notificationsToDisplay = allNotificationsVisible
        ? filteredNotifications
        : filteredNotifications.slice(0, maxNotificationsToShow);

    let unreadCount = 0;

    notificationsToDisplay.forEach(notification => {
        const rawTime = new Date(notification.raw_time);

        const notificationElement = document.createElement('button');
        notificationElement.classList.add('tab-button', 'py-2', 'px-4', 'text-gray-500', 'w-full', 'flex', 'items-start');

        if (notification.status_notif == 0) {
            notificationElement.classList.add('bg-gray-300', 'text-white', 'rounded-lg');
            unreadCount++;
        }

        notificationElement.innerHTML = `
            <img src="${notification.image || './Back-end/foto-profile/default-profile.png'}" alt="User avatar" class="rounded-full mr-4" width="40" height="40">
            <div class="flex-1">
                <h2 class="font-bold">${notification.title}</h2>
                <p class="text-gray-500 text-sm">${notification.time} · ${notification.type.charAt(0).toUpperCase() + notification.type.slice(1)}</p>
                ${
                    notification.type === 'rating'
                    ? `<div class="text-yellow-500">
                        ${'<i class="fas fa-star"></i>'.repeat(notification.rating)}
                        ${'<i class="far fa-star"></i>'.repeat(5 - notification.rating)}
                    </div>`
                    : ''
                }
            </div>
        `;

        notificationElement.addEventListener('click', () => {
            if (notification.type === 'pengaduan') {
                window.location.href = `lihat_pengaduan.php?id=${notification.id}`;
            } else if (notification.type === 'kehilangan') {
                window.location.href = `kehilangan.php?id=${notification.id}`;
            }  else if (notification.type === 'rating') {
                window.location.href = `detail_rating.php?id=${notification.id_instansi}`;
            }

            // Ubah status warna setelah klik
            notificationElement.classList.remove('bg-gray-300', 'text-white', 'rounded-lg');
            notificationElement.classList.add('bg-white');

            // Update status di backend menggunakan AJAX
            fetch('./Back-end/update_status_notif.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: notification.id }),
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Gagal memperbarui status:', data.error);
                }
            })
            .catch(err => console.error('Error:', err));
        });

        container.appendChild(notificationElement);
    });

    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
        button.classList.add('text-gray-500');
    });
    document.getElementById(`tab-${type}`).classList.add('active');

    // Update tombol "Tampilkan Semua"
    if (filteredNotifications.length > maxNotificationsToShow && !allNotificationsVisible) {
        showAllButtonContainer.classList.remove('hidden');
        showAllButton.onclick = () => {
            allNotificationsVisible = true;
            populateNotifications(type);
            showAllButtonContainer.classList.add('hidden');
        };
    } else {
        showAllButtonContainer.classList.add('hidden');
    }

    // Update jumlah notifikasi belum terbaca
    const notificationButton = document.getElementById('notificationButton');
    const redDot = notificationButton.querySelector('.absolute');
    if (unreadCount > 0) {
        redDot.textContent = unreadCount;
        redDot.classList.remove('hidden');
    } else {
        redDot.classList.add('hidden');
    }
}

// Inisialisasi dengan semua notifikasi
populateNotifications('semua');

document.getElementById('notificationButton').addEventListener('click', () => {
    document.getElementById('notificationSidebar').classList.toggle('translate-x-full');

document.getElementById('closeSidebarButton').addEventListener('click', () => {
    document.getElementById('notificationSidebar').classList.add('translate-x-full');
});
});
