// toast.js

function showToast(message) {
    // Buat container toast jika belum ada
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        document.body.appendChild(toastContainer);
    }
    toastContainer.className = 'fixed top-5 right-5 z-50';

    // Buat elemen toast
    const toast = document.createElement('div');
    toast.className = 'flex items-center w-full max-w-xs p-4 mb-4 text-gray-500 bg-white rounded-lg shadow';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class='inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg'>
            <svg class='w-5 h-5' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 20 20'>
                <path d='M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z'/>
            </svg>
            <span class='sr-only'>Check icon</span>
        </div>
        <div class='ms-3 text-sm font-normal'>${message}</div>
    `;

    // Tambahkan toast ke container
    toastContainer.appendChild(toast);

    // Hapus toast setelah beberapa detik
    setTimeout(() => {
        toast.remove();
    }, 3000); // 3 detik
}
