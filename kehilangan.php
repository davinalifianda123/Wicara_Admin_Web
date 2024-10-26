<?php
    // buat update profile
    session_start();
    include './Back-end/config.php';
    $db = new database();

    if (!isset($_SESSION['id_user'])) {
        header("Location: ../login.php"); // Jika belum login, redirect ke halaman login
    }

    $id_user = $_SESSION['id_user'];
    $user_data = mysqli_query($db->koneksi, "SELECT * FROM user WHERE id_user = '$id_user'");
    $user = mysqli_fetch_assoc($user_data);
    $user_image = $user['image'] ? './Back-end'.$user['image'] : './assets/default-profile.png';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />
    <style>
      body {
        font-family: "Poppins", sans-serif !important;
        background-image: url('assets/Pattern-cover.png');
        background-size: cover;
        background-color: #F6F6F6;
      }

      .color-linear {
        background: rgb(6, 10, 71);
        background: linear-gradient(135deg, rgba(6, 10, 71, 1) 0%, rgba(25, 48, 109, 1) 100%);
      }

      /* CSS untuk styling tab aktif */
      .tab.active {
        background-color: #ffd700;
      }
      /* CSS untuk modal */
      .modal {
        display: none;
        position: fixed;
        z-index: 50;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
      }
      .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        border-radius: 10px;
      }
      .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
      }
      .close:hover,
      .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
      }

      .tab-button {
        transition: color 0.3s, border-bottom 0.3s;
      }
      .tab-button.active {
        color: #fbbf24; /* yellow-500 */
        border-bottom: 2px solid #fbbf24; /* yellow-500 */
      }
      .modal {
        display: none;
        position: fixed;
        z-index: 10;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
      }
      .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        text-align: center;
      }
      #notificationSidebar {
        z-index: 10;
      }

    </style>
  </head>
  <body>
    <!-- SIDEBAR INII -->
    <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
      <div class="h-full px-3 py-4 overflow-y-auto color-linear">
        <a href="#" class="flex items-center ps-2.5 mb-4 text-gray-50">
          <img src="assets/logo-polines.png" class="h-6 me-3 sm:h-7" alt="Polines Logo" />
          <span class="text-2xl self-center font-bold whitespace-nowrap">WICARA</span>
        </a>
        <hr />
        <ul class="space-y-2 font-medium">
          <li>
            <p class="ms-5 text-gray-50 font-semibold my-4">Menu</p>
          </li>
          <li>
            <a href="./Dashboard.php" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
              <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path
                  fill-rule="evenodd"
                  d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z"
                  clip-rule="evenodd"
                />
              </svg>
              <span class="ms-3 group-hover:text-yellow-400">Dashboard</span>
            </a>
          </li>
          <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-50 transition duration-75 rounded-lg group hover:bg-blue-900" aria-controls="dropdown-pengaduan" data-collapse-toggle="dropdown-pengaduan">
              <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path
                  fill-rule="evenodd"
                  d="M8 7V2.221a2 2 0 0 0-.5.365L3.586 6.5a2 2 0 0 0-.365.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.126a5.087 5.087 0 0 0-4.74 1.368v.001l-6.642 6.642a3 3 0 0 0-.82 1.532l-.74 3.692a3 3 0 0 0 3.53 3.53l3.694-.738a3 3 0 0 0 1.532-.82L19 15.149V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z"
                  clip-rule="evenodd"
                />
                <path
                  fill-rule="evenodd"
                  d="M17.447 8.08a1.087 1.087 0 0 1 1.187.238l.002.001a1.088 1.088 0 0 1 0 1.539l-.377.377-1.54-1.542.373-.374.002-.001c.1-.102.22-.182.353-.237Zm-2.143 2.027-4.644 4.644-.385 1.924 1.925-.385 4.644-4.642-1.54-1.54Zm2.56-4.11a3.087 3.087 0 0 0-2.187.909l-6.645 6.645a1 1 0 0 0-.274.51l-.739 3.693a1 1 0 0 0 1.177 1.176l3.693-.738a1 1 0 0 0 .51-.274l6.65-6.646a3.088 3.088 0 0 0-2.185-5.275Z"
                  clip-rule="evenodd"
                />
              </svg>
              <a href="./lihat_pengaduan.php" class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-yellow-400">Pengaduan</a>
              <svg class="w-3 h-3 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
              </svg>
            </button>
            <ul id="dropdown-pengaduan" class="hidden py-2 space-y-2">
              <li>
                <a href="./lihat_pengaduan.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Lihat Pengaduan</a>
              </li>
              <li>
                <a href="./kategori_pengaduan.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Kategori Pengaduan</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#" class="flex items-center p-2 text-gray-50 rounded-lg bg-blue-950">
              <div class="rounded-full p-2 bg-yellow-400">
                <svg class="w-6 h-6 text-gray-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path
                    fill-rule="evenodd"
                    d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm.5 5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm0 5c.47 0 .917-.092 1.326-.26l1.967 1.967a1 1 0 0 0 1.414-1.414l-1.817-1.818A3.5 3.5 0 1 0 11.5 17Z"
                    clip-rule="evenodd"
                  />
                </svg>
              </div>
              <span class="ms-3">Kehilangan</span>
            </a>
          </li>
          <li>
            <a href="./rating.php" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
              <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"
                />
              </svg>
              <span class="ms-3 group-hover:text-yellow-400">Rating</span>
            </a>
          </li>
          <li>
            <p class="ms-5 text-gray-50 font-semibold my-4">Setting</p>
          </li>
          <li>
            <button type="button" class="flex items-center w-full p-2 text-base text-gray-50 transition duration-75 rounded-lg group hover:bg-blue-900" aria-controls="dropdown-user" data-collapse-toggle="dropdown-user">
              <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path
                  fill-rule="evenodd"
                  d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z"
                  clip-rule="evenodd"
                />
              </svg>
              <a href="./mahasiswa.php" class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-yellow-400">User</a>
              <svg class="w-3 h-3 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4" />
              </svg>
            </button>
            <ul id="dropdown-user" class="hidden py-2 space-y-2">
              <li>
                <a href="./mahasiswa.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Mahasiswa</a>
              </li>
              <li>
                <a href="./dosen.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Dosen/Tendik</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="./unit_layanan.php" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
              <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path
                  fill-rule="evenodd"
                  d="M12 2a7 7 0 0 0-7 7 3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1a1 1 0 0 0 1-1V9a5 5 0 1 1 10 0v7.083A2.919 2.919 0 0 1 14.083 19H14a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1a2 2 0 0 0 1.732-1h.351a4.917 4.917 0 0 0 4.83-4H19a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3 7 7 0 0 0-7-7Zm1.45 3.275a4 4 0 0 0-4.352.976 1 1 0 0 0 1.452 1.376 2.001 2.001 0 0 1 2.836-.067 1 1 0 1 0 1.386-1.442 4 4 0 0 0-1.321-.843Z"
                  clip-rule="evenodd"
                />
              </svg>
              <span class="ms-3 group-hover:text-yellow-400">Unit Layanan</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <!-- CONTENT INII -->
    <div class="py-4 px-4 sm:ml-64">
      <!-- NAVBAR INII -->
      <nav class="w-full bg-transparent lg:px-0 pb-4">
        <div class="flex flex-wrap justify-between items-center">
          <div class="flex items-center">
            <button
              data-drawer-target="logo-sidebar"
              data-drawer-toggle="logo-sidebar"
              aria-controls="logo-sidebar"
              type="button"
              class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 hover:text-yellow-400"
            >
              <span class="sr-only">Open sidebar</span>
              <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path
                  clip-rule="evenodd"
                  fill-rule="evenodd"
                  d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                ></path>
              </svg>
            </button>
            <span class="hidden font-semibold text-xl text-[#060A47] sm:inline-block">Kehilangan</span>
          </div>
          <div class="flex items-center lg:order-2">
            <!-- INII Notifications -->
            <button type="button" id="notificationButton" class="p-2 mr-2 text-gray-400 rounded-lg hover:text-yellow-400 hover:bg-gray-100">
              <span class="sr-only">View notifications</span>
              <!-- Bell icon -->
              <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20"><path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z"/></svg>
            </button>
            <div id="notificationSidebar" class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 overflow-y-auto">
                <div class="sticky top-0 bg-white z-10">
                    <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                        <button id="closeSidebarButton" class="text-gray-500 text-2xl">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <h1 class="text-center text-xl font-bold flex-1">Notifikasi</h1>
                    </div>
                    <div class="flex justify-around border-b border-gray-200 overflow-x-auto">
                        <button id="tab-semua" class="tab-button py-2 px-4 text-gray-500" onclick="filterNotifications('semua')">Semua</button>
                        <button id="tab-pengaduan" class="tab-button py-2 px-4 text-gray-500" onclick="filterNotifications('pengaduan')">Pengaduan</button>
                        <button id="tab-kehilangan" class="tab-button py-2 px-4 text-gray-500" onclick="filterNotifications('kehilangan')">Kehilangan</button>
                        <button id="tab-rating" class="tab-button py-2 px-4 text-gray-500" onclick="filterNotifications('rating')">Rating</button>
                    </div>
                </div>
                <div id="notifications" class="p-4">
                    <!-- Notifications will be dynamically inserted here -->
                </div>
            </div>
        
            <div id="confirmationModal" class="modal">
                <div class="modal-content">
                    <p>Apakah anda yakin?</p>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button id="confirmYes" class="bg-green-500 text-white py-1 px-3 rounded">Ya</button>
                        <button id="confirmNo" class="bg-red-500 text-white py-1 px-3 rounded">Batal</button>
                    </div>
                </div>
            </div>

            <!-- INII Profile -->
            <button type="button" class="flex mx-2 text-sm bg-gray-400 rounded-full md:mr-0 hover:ring-4 ring-yellow-400" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="dropdown">
              <span class="sr-only">Open user menu</span>
              <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo" />
            </button>
            <!-- Dropdown profile -->
            <div class="hidden z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow" id="dropdown">
              <div class="py-3 px-4">
                <span class="block text-sm font-semibold text-gray-900">Davin Alifianda</span>
                <span class="block text-sm text-gray-500 truncate">admin@adm.polines.ac.id</span>
              </div>
              <ul class="py-1 text-gray-500" aria-labelledby="dropdown">
                <li>
                  <a href="#profile-section" class="block py-2 px-4 text-sm hover:bg-gray-100">My profile</a>
                </li>
                <li>
                  <a href="./Back-end/proses_logout.php" class="block py-2 px-4 text-sm hover:bg-gray-100">Logout</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <!-- PROFILE READ CONTENT -->
      <div id="profile-section-body" class="hidden absolute right-0 mt-2 w-56 lg:w-full max-w-lg p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 z-10">
          <!-- Tombol Kembali -->
          <div class="flex justify-between mb-4">
              <h5 class="text-xl font-bold text-gray-900">Profil</h5>
              <button onclick="goBack()" class="flex items-center text-sm text-blue-500 hover:underline">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                  Kembali
              </button>
          </div>

          <div class="flex items-center mb-4">
              <img class="w-32 h-32 bg-gray-300 rounded-full object-cover" src="<?php echo $user_image; ?>" alt="Foto Profil">
          </div>
          
          <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                  <label for="name" class="block mb-2 text-sm font-bold text-gray-900">Nama</label>
                  <input type="text" name="name" id="name" class="text-black bg-transparent border-none focus:ring-0 p-0" value="<?php echo $user['nama']; ?>" readonly />
              </div>
              <div>
                  <label for="nomor_induk" class="block mb-2 text-sm font-bold text-gray-900">Nomor Induk</label>
                  <input type="text" name="nomor_induk" id="nomor_induk" class="text-black bg-transparent border-none focus:ring-0 p-0" value="<?php echo $user['nomor_induk']; ?>" readonly />
              </div>
              <div>
                  <label for="nomor_telepon" class="block mb-2 text-sm font-bold text-gray-900">Nomor Telepon</label>
                  <input type="text" name="nomor_telepon" id="nomor_telepon" class="text-black bg-transparent border-none focus:ring-0 p-0" value="<?php echo $user['nomor_telepon']; ?>" readonly />
              </div>
              <div>
                  <label for="email" class="block mb-2 text-sm font-bold text-gray-900">Email</label>
                  <input type="email" name="email" id="email" class="text-black bg-transparent border-none focus:ring-0 p-0" value="<?php echo $user['email']; ?>" readonly />
              </div>
              <div>
                  <label for="password" class="block mb-2 text-sm font-bold text-gray-900">Kata Sandi</label>
                  <input type="password" name="password" id="password" class="text-black bg-transparent border-none focus:ring-0 p-0" value="<?php echo $user['password']; ?>" readonly />
              </div>                     
                                      
              <!-- Tombol Edit -->
              <div class="flex justify-end">
                  <button id="edit-profile" type="button" class=" text-white bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">Edit</button>
              </div>
          </form>
      </div>

      <!-- PROFILE EDIT CONTENT -->
      <div id="profile-section-edit" class="hidden absolute right-0 mt-2 w-56 lg:w-full max-w-lg p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8 z-10">
          <div class="flex justify-between mb-4">
              <h5 class="text-xl font-bold text-gray-900">Profil</h5>
              <button onclick="goBack2()" class="flex items-center text-sm text-blue-500 hover:underline">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                  Kembali
              </button>
          </div>

          <form action="./Back-end/update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-4 flex flex-col justify-between h-full">
              <div class="flex flex-col lg:items-center mb-4">
                  <img id="profile-preview" class="w-32 h-32 rounded-full object-cover" src="<?php echo $user_image; ?>" alt="Foto Profil">
              </div>
              <div>
                  <input type="hidden" name="id_user" id="admin-id" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['id_user']; ?>"  required />
              </div>
              <div>
                  <label for="name" class="block mb-2 text-sm font-bold text-gray-900 ">Nama</label>
                  <input type="text" name="nama" id="name" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['nama']; ?>" required />
              </div>
              <div>
                  <label for="nomor_induk" class="block mb-2 text-sm font-bold text-gray-900 ">Nomor Induk</label>
                  <input type="text" name="nomor_induk" id="nomor_induk" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['nomor_induk']; ?>" required />
              </div>
              <div>
                  <label for="nomor_telepon" class="block mb-2 text-sm font-bold text-gray-900 ">No Telepon</label>
                  <input type="text" name="nomor_telepon" id="nomor_telepon" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['nomor_telepon']; ?>" required />
              </div>
              <div>
                  <label for="email" class="block mb-2 text-sm font-bold text-gray-900">Email</label>
                  <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['email']; ?>" required />
              </div>
              <div class="relative">
                  <label for="password" class="block mb-2 text-sm font-bold text-gray-900">Kata Sandi</label>
                  <input type="password" name="password" id="password-edit" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pr-10" value="<?php echo $user['password']; ?>" required />
          
                  <!-- Tombol untuk menampilkan/menyembunyikan password -->
                  <button type="button" id="togglePassword" class="absolute right-3 top-9 flex items-center">
                      <svg id="eyeIconClosed" class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <!-- Icon mata tertutup -->
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                      </svg>
                      <svg id="eyeIconOpen" class="hidden w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <!-- Icon mata terbuka -->
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21 12c0 1.2-4.03 6-9 6s-9-4.8-9-6c0-1.2 4.03-6 9-6s9 4.8 9 6Z" />
                      </svg>
                  </button>
              </div>
              <div>
                  <label class="block mb-2 text-sm font-bold text-gray-900 " for="image">Foto Profile</label>
                  <input name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none" id="image" type="file" accept="image/jpeg, image/png, image/jpg" onchange="previewImage(event)">
              </div>
              

              <!-- Tombol Simpan -->
              <div class="flex justify-end">
                  <button type="submit" name="update" class="flex text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 ">
                      <svg class="w-6 h-6 text-gray-50 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                          <path fill-rule="evenodd" d="M5 3a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V7.414A2 2 0 0 0 20.414 6L18 3.586A2 2 0 0 0 16.586 3H5Zm10 11a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 7V5h8v2a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                        </svg>
                        <span>Simpan</span>
                  </button>
              </div>
          </form>
      </div>

      <!-- CONTENT INII REAL CUY -->
      <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg">
        <div class="grid">
          <div class="container mx-auto p-4">
            <!-- Search and Tabs -->
            <div class="flex justify-between items-center mb-4">
              <div class="flex space-x-4">
                <!-- Tabs -->
                <button class="tab py-2 px-4 rounded-lg focus:outline-none" data-tab="semua">Semua</button>
                <button class="tab py-2 px-4 rounded-lg focus:outline-none" data-tab="belum-ditemukan">Belum Ditemukan</button>
                <button class="tab py-2 px-4 rounded-lg focus:outline-none" data-tab="ditemukan">Ditemukan</button>
                <button class="tab py-2 px-4 rounded-lg focus:outline-none" data-tab="hilang">Hilang</button>
              </div>
              <!-- Search -->
              <div class="relative">
                <input type="text" id="search" placeholder="Search..." class="border border-gray-300 rounded-lg p-2 pl-10 w-64 focus:ring-blue-500 focus:border-blue-500" />
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m-2.05-4.6a7 7 0 1 0-2.1 4.35A7 7 0 0 0 14.6 16.05z"></path>
                  </svg>
                </span>
              </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">ID Laporan</th>
                    <th class="px-6 py-3">Judul Laporan</th>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Jenis Barang</th>
                    <th class="px-6 py-3">Deskripsi</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Aksi</th>
                  </tr>
                </thead>
                <tbody id="table-body" class="bg-white">
                  <!-- Contoh data -->
                  <tr data-id="A09876" class="border-b">
                    <td class="px-6 py-3">1</td>
                    <td class="px-6 py-3">A09876</td>
                    <td class="px-6 py-3">Hape Hilang</td>
                    <td class="px-6 py-3">14-09-2024</td>
                    <td class="px-6 py-3">Samsung J2 Prime</td>
                    <td class="px-6 py-3">Lorem Ipsum...</td>
                    <td class="px-6 py-3">
                      <span class="status-label bg-red-200 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Hilang</span>
                    </td>
                    <td class="px-6 py-3">
                      <button class="ellipsis-btn text-gray-500 focus:outline-none">&#8230;</button>
                    </td>
                  </tr>

                  <tr data-id="A09876" class="border-b">
                    <td class="px-6 py-3">2</td>
                    <td class="px-6 py-3">A09879</td>
                    <td class="px-6 py-3">Laptop Hilang</td>
                    <td class="px-6 py-3">13-10-2024</td>
                    <td class="px-6 py-3">Lenovo Region</td>
                    <td class="px-6 py-3">warna putih ada sticker upin ipinnya</td>
                    <td class="px-6 py-3">
                      <span class="status-label bg-green-200 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Ditemukan</span>
                    </td>
                    <td class="px-6 py-3">
                      <button class="ellipsis-btn text-gray-500 focus:outline-none">&#8230;</button>
                    </td>
                  </tr>

                  <tr data-id="A09876" class="border-b">
                    <td class="px-6 py-3">3</td>
                    <td class="px-6 py-3">A098781</td>
                    <td class="px-6 py-3">Photo Card</td>
                    <td class="px-6 py-3">26-10-2024</td>
                    <td class="px-6 py-3">Photo Card Karina Aespa</td>
                    <td class="px-6 py-3">Photo Card Karina Aespa warna titanium lemited edition</td>
                    <td class="px-6 py-3">
                      <span class="status-label bg-yellow-200 text-yellow-700 text-xs font-medium px-2.5 py-0.5 rounded"> Belum Ditemukan</span>
                    </td>
                    <td class="px-6 py-3">
                      <button class="ellipsis-btn text-gray-500 focus:outline-none">&#8230;</button>
                    </td>
                  </tr>

                  <!-- Tambahkan baris lainnya -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Modal -->
          <div id="modal" class="modal">
            <div class="modal-content max-w-4xl p-6">
              <span class="close">&times;</span>
              <div class="grid grid-cols-1">
                <label class="font-medium py-4 text-lg">Form Laporan Kehilangan</label>
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div class="grid grid-cols-1 gap-4">
                  <div>
                    <label>ID Laporan</label>
                    <input type="text" value="09000647839" disabled class="block border border-gray-300 rounded-lg p-2 w-full disabled:bg-gray-200" />
                  </div>
                  <div>
                    <label>User</label>
                    <input type="text" value="Miaauw@polines.ac.id" disabled class="border border-gray-300 rounded-lg p-2 w-full disabled:bg-gray-200" />
                  </div>
                  <div>
                    <label>Judul Laporan</label>
                    <input type="text" value="Hapeku Hilang" disabled class="border border-gray-300 rounded-lg p-2 w-full disabled:bg-gray-200" />
                  </div>
                  <div>
                    <label>Jenis Barang</label>
                    <input type="text" value="Samsung J2 Prime" disabled class="border border-gray-300 rounded-lg p-2 w-full disabled:bg-gray-200" />
                  </div>
                  <div>
                    <label>Tanggal Laporan</label>
                    <input type="text" value="01-09-2024" disabled class="border border-gray-300 rounded-lg p-2 w-full disabled:bg-gray-200" />
                  </div>
                  <div>
                    <label>Status Laporan</label>
                    <select id="status-laporan" class="border border-gray-300 rounded-lg p-2 w-full">
                      <option value="hilang">Hilang</option>
                      <option value="ditemukan">Ditemukan</option>
                      <option value="proses">Proses</option>
                    </select>
                  </div>
                  <div>
                    <label>Lokasi</label>
                    <input type="text" value="Polines" disabled class="border border-gray-300 rounded-lg p-2 w-full disabled:bg-gray-200" />
                  </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                  <img src="assets/laptop.jpg" class="h-auto max-w-xs" alt="Laptop" />
                </div>
              </div>

              <!-- Description section at the bottom -->
              <div class="grid grid-cols-1 gap-4">
                <label>Deskripsi Laporan</label>
                <textarea disabled class="border border-gray-300 rounded-lg p-2 w-full disabled:bg-gray-200 h-32">
          Lorem ipsum ale ale ale...
                </textarea>
              </div>

              <div class="flex justify-end mt-4">
                <button
                  data-modal-target="popup-modal-simpan"
                  data-modal-toggle="popup-modal-simpan"
                  class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                  type="button"
                >
                  Simpan
                </button>

                <div id="popup-modal-simpan" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                  <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                      <button
                        type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="popup-modal-simpan"
                      >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close</span>
                      </button>
                      <div class="p-4 md:p-5 text-center">
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menyimpan perubahan?</h3>
                        <button data-modal-hide="popup-modal-simpan" type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg focus:outline-none">Simpan</button>
                        <button
                          data-modal-hide="popup-modal-simpan"
                          type="button"
                          class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                          Batal
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <button
                  data-modal-target="popup-modal-hapus"
                  data-modal-toggle="popup-modal-hapus"
                  class="block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red- ml-4"
                  type="button"
                >
                  Hapus
                </button>

                <div id="popup-modal-hapus" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                  <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                      <button
                        type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="popup-modal-hapus"
                      >
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close </span>
                      </button>
                      <div class="p-4 md:p-5 text-center">
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus laporan ini?</h3>
                        <button data-modal-hide="popup-modal-hapus" type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg focus:outline-none">Hapus</button>
                        <button
                          data-modal-hide="popup-modal-hapus"
                          type="button"
                          class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-red-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        >
                          Batal
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.js"></script>    
    <script>
      const tabs = document.querySelectorAll(".tab");
      const tableBody = document.getElementById("table-body");

      tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
          // Hapus kelas active dari semua tab
          tabs.forEach((t) => t.classList.remove("active"));

          // Tambahkan kelas active ke tab yang diklik
          this.classList.add("active");

          // Filter data berdasarkan tab yang diklik
          const tabType = this.getAttribute("data-tab");
          filterTable(tabType);
        });
      });

      function filterTable(type) {
        const rows = tableBody.querySelectorAll("tr");
        rows.forEach((row) => {
          const status = row.querySelector("td:nth-child(7)").innerText.trim().toLowerCase();

          if (type === "semua" || status === type.replace("-", " ")) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        });
      }

      const searchInput = document.getElementById("search");

      searchInput.addEventListener("input", function () {
        const filter = searchInput.value.toLowerCase();
        const rows = tableBody.querySelectorAll("tr");

        rows.forEach((row) => {
          const text = row.innerText.toLowerCase();
          if (text.includes(filter)) {
            row.style.display = "";
          } else {
            row.style.display = "none";
          }
        });
      });

      // Modal handling
      const modal = document.getElementById("modal");
      const closeModal = document.querySelector(".modal .close");
      const ellipsisButtons = document.querySelectorAll(".ellipsis-btn");

      ellipsisButtons.forEach((button) => {
        button.addEventListener("click", () => {
          modal.style.display = "block";
        });
      });

      closeModal.addEventListener("click", () => {
        modal.style.display = "none";
      });

      window.addEventListener("click", (event) => {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      });

      // Event listener pada tombol "Simpan" di modal
      document.getElementById("save-btn").addEventListener("click", function () {
        // Ambil nilai status dari select dropdown di modal
        const statusLaporan = document.getElementById("status-laporan").value;

        // Ambil ID laporan dari modal (misalnya dari input hidden atau input field lain)
        const idLaporan = document.querySelector("input[name='id-laporan']").value;

        // Cari baris tabel yang sesuai dengan ID laporan
        const row = document.querySelector(`tr[data-id="${idLaporan}"]`);

        // Jika baris ditemukan
        if (row) {
          // Ambil elemen span untuk status di dalam kolom status
          const statusLabel = row.querySelector(".status-label");

          // Reset teks dan class status sebelumnya
          statusLabel.textContent = ""; // Hapus teks lama
          statusLabel.className = "status-label text-xs font-medium px-2.5 py-0.5 rounded"; // Reset class

          // Perbarui status berdasarkan pilihan dari dropdown di modal
          if (statusLaporan === "hilang") {
            statusLabel.textContent = "Hilang"; // Isi teks dengan "Hilang"
            statusLabel.classList.add("bg-red-200", "text-red-800"); // Tambahkan kelas warna untuk status hilang
          } else if (statusLaporan === "ditemukan") {
            statusLabel.textContent = "Ditemukan"; // Isi teks dengan "Ditemukan"
            statusLabel.classList.add("bg-green-200", "text-green-800"); // Tambahkan kelas warna untuk status ditemukan
          } else if (statusLaporan === "proses") {
            statusLabel.textContent = "Proses"; // Isi teks dengan "Proses"
            statusLabel.classList.add("bg-yellow-200", "text-yellow-800"); // Tambahkan kelas warna untuk status proses
          }
        }

        // Tutup modal setelah penyimpanan (optional)
        document.getElementById("modal").style.display = "none";
      });

    </script>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
    <!-- INII SCRIPT NOTIF -->
    <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.js"></script>
    <script>
      const notifications = [
          { type: 'pengaduan', title: 'Kamar mandi Kotor', time: '2h ago', avatar: 'https://placehold.co/40x40?text=1' },
          { type: 'pengaduan', title: 'Admin PBM Judes', time: '2h ago', avatar: 'https://placehold.co/40x40?text=2' },
          { type: 'pengaduan', title: 'Dosen suka bolos', time: '2h ago', avatar: 'https://placehold.co/40x40?text=3' },
          { type: 'rating', title: 'Poliklinik', time: '2h ago', rating: 4, avatar: 'https://placehold.co/40x40?text=4' },
          { type: 'kehilangan', title: 'Pacar ku Hilang', time: '2h ago', avatar: 'https://placehold.co/40x40?text=5' },
          { type: 'pengaduan', title: 'Dosen suka bolos', time: '2h ago', avatar: 'https://placehold.co/40x40?text=6' },
          { type: 'rating', title: 'Poliklinik', time: '2h ago', rating: 4, avatar: 'https://placehold.co/40x40?text=7' },
          { type: 'kehilangan', title: 'Pacar ku Hilang', time: '2h ago', avatar: 'https://placehold.co/40x40?text=8' },
          { type: 'rating', title: 'Poliklinik', time: '2h ago', rating: 4, avatar: 'https://placehold.co/40x40?text=9' },
          { type: 'pengaduan', title: 'Dosen suka bolos', time: '2h ago', avatar: 'https://placehold.co/40x40?text=10' },
          { type: 'pengaduan', title: 'Admin PBM Judes', time: '2h ago', avatar: 'https://placehold.co/40x40?text=11' }
      ];

      document.getElementById('notificationButton').addEventListener('click', () => {
          document.getElementById('notificationSidebar').classList.toggle('translate-x-full');
      });

      document.getElementById('closeSidebarButton').addEventListener('click', () => {
          document.getElementById('notificationSidebar').classList.add('translate-x-full');
      });

      function filterNotifications(type) {
          const container = document.getElementById('notifications');
          container.innerHTML = '';
          const filteredNotifications = type === 'semua' ? notifications : notifications.filter(n => n.type === type);
          filteredNotifications.forEach(notification => {
              const notificationElement = document.createElement('div');
              notificationElement.classList.add('flex', 'items-center', 'mb-4');
              notificationElement.innerHTML = `
                  <img src="${notification.avatar}" alt="User avatar" class="rounded-full mr-4" width="40" height="40">
                  <div class="flex-1">
                      <h2 class="font-bold">${notification.title}</h2>
                      <p class="text-gray-500 text-sm">${notification.time} · ${notification.type.charAt(0).toUpperCase() + notification.type.slice(1)}</p>
                      ${notification.type === 'rating' ? `<div class="text-yellow-500">${'<i class="fas fa-star"></i>'.repeat(notification.rating)}${'<i class="far fa-star"></i>'.repeat(5 - notification.rating)}</div>` : ''}
                      ${notification.type === 'kehilangan' ? `
                          <div class="flex space-x-2 mt-2">
                              <button class="bg-red-500 text-white py-1 px-3 rounded" onclick="showConfirmationDialog('Tolak')">Tolak</button>
                              <button class="bg-green-500 text-white py-1 px-3 rounded" onclick="showConfirmationDialog('Konfirmasi')">Konfirmasi</button>
                          </div>
                      ` : ''}
                  </div>
              `;
              container.appendChild(notificationElement);
          });

          // Update tab button styles
          document.querySelectorAll('.tab-button').forEach(button => {
              button.classList.remove('active');
              button.classList.add('text-gray-500');
          });
          document.getElementById(`tab-${type}`).classList.add('active');
      }

      function showConfirmationDialog(action) {
          const modal = document.getElementById('confirmationModal');
          modal.style.display = 'block';

          document.getElementById('confirmYes').onclick = () => {
              alert(`${action} berhasil!`);
              modal.style.display = 'none';
          };

          document.getElementById('confirmNo').onclick = () => {
              modal.style.display = 'none';
          };
      }

      // Initialize with all notifications
      filterNotifications('semua');

      // Close the modal when clicking outside of it
      window.onclick = function(event) {
          const modal = document.getElementById('confirmationModal');
          if (event.target === modal) {
              modal.style.display = 'none';
          }
      };
      
      // Fungsi untuk menampilkan halaman read profile
      document.querySelector('[href="#profile-section"]').addEventListener('click', function () {
          document.getElementById('profile-section-body').classList.remove('hidden');
          document.getElementById('dropdown').classList.add('hidden');
      });

      // Fungsi tombol kembali
      function goBack() {
          document.getElementById('profile-section-body').classList.add('hidden');
      }

      // Fungsi untuk menampilkan halaman edit profile
      document.querySelector('[id="edit-profile"]').addEventListener('click', function () {
          document.getElementById('profile-section-edit').classList.remove('hidden');
          document.getElementById('dropdown').classList.add('hidden');
          document.getElementById('profile-section-body').classList.add('hidden');
      });

      // Fungsi tombol kembali edit profile
      function goBack2() {
          document.getElementById('profile-section-edit').classList.add('hidden');
          document.getElementById('profile-section-body').classList.remove('hidden');
      }

      const passwordInput = document.getElementById('password-edit');
      const togglePasswordButton = document.getElementById('togglePassword');
      const eyeIconOpen = document.getElementById('eyeIconOpen');
      const eyeIconClosed = document.getElementById('eyeIconClosed');

      togglePasswordButton.addEventListener('click', function () {
          const isPasswordVisible = passwordInput.type === 'password';

          // Toggle password visibility
          passwordInput.type = isPasswordVisible ? 'text' : 'password';

          // Toggle icons
          eyeIconClosed.classList.toggle('hidden', isPasswordVisible);
          eyeIconOpen.classList.toggle('hidden', !isPasswordVisible);
      });
      // Fungsi untuk menampilkan gambar preview profile
      function previewImage(event) {
          var reader = new FileReader();
          reader.onload = function() {
              var output = document.getElementById('profile-preview');
              output.src = reader.result;
          }
          reader.readAsDataURL(event.target.files[0]);
      }
    </script>
    <script src="../path/to/flowbite/dist/flowbite.min.js"></script>
  </body>
</html>
