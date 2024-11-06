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

    $instansi_data = mysqli_query($db->koneksi, "SELECT * FROM instansi");
    $instansi = mysqli_fetch_assoc($instansi_data);
    $instansi_image = $instansi['image_instansi'] ? './Back-end'.$instansi['image_instansi'] : './assets/folder-cuate.png';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Unit Layanan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif !important;
                background-image: url("assets/Pattern-cover.png");
                background-size: cover;
            }

            .color-linear {
                background: rgb(6,10,71);
                background: linear-gradient(135deg, rgba(6,10,71,1) 0%, rgba(25,48,109,1) 100%);
            }

            .tab-button {
                transition: background-color 0.3s, color 0.3s;
                display: flex;
                width: 100%;
                align-items: center;
                text-align: left;
            }
            
            .tab-button:hover {
                background-color: #f3f4f6; /* light gray */
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
                <hr>
                <ul class="space-y-2 font-medium">
                    <li>
                        <p class="ms-5 text-gray-50 font-semibold my-4">Menu</p>
                    </li>
                    <li>
                        <a href="./Dashboard.php" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
                            <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z" clip-rule="evenodd"/>
                            </svg>
                            <span class="ms-3 group-hover:text-yellow-400">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <button type="button" class="flex items-center w-full p-2 text-base text-gray-50 transition duration-75 rounded-lg group hover:bg-blue-900" aria-controls="dropdown-pengaduan" data-collapse-toggle="dropdown-pengaduan">
                            <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 7V2.221a2 2 0 0 0-.5.365L3.586 6.5a2 2 0 0 0-.365.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.126a5.087 5.087 0 0 0-4.74 1.368v.001l-6.642 6.642a3 3 0 0 0-.82 1.532l-.74 3.692a3 3 0 0 0 3.53 3.53l3.694-.738a3 3 0 0 0 1.532-.82L19 15.149V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M17.447 8.08a1.087 1.087 0 0 1 1.187.238l.002.001a1.088 1.088 0 0 1 0 1.539l-.377.377-1.54-1.542.373-.374.002-.001c.1-.102.22-.182.353-.237Zm-2.143 2.027-4.644 4.644-.385 1.924 1.925-.385 4.644-4.642-1.54-1.54Zm2.56-4.11a3.087 3.087 0 0 0-2.187.909l-6.645 6.645a1 1 0 0 0-.274.51l-.739 3.693a1 1 0 0 0 1.177 1.176l3.693-.738a1 1 0 0 0 .51-.274l6.65-6.646a3.088 3.088 0 0 0-2.185-5.275Z" clip-rule="evenodd"/>
                            </svg>  
                            <a href="./lihat_pengaduan.php" class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-yellow-400">Pengaduan</a>
                            <svg class="w-3 h-3 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
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
                        <a href="./kehilangan.php" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
                            <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm.5 5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm0 5c.47 0 .917-.092 1.326-.26l1.967 1.967a1 1 0 0 0 1.414-1.414l-1.817-1.818A3.5 3.5 0 1 0 11.5 17Z" clip-rule="evenodd"/>
                            </svg>                                
                            <span class="ms-3 group-hover:text-yellow-400">Kehilangan</span>
                        </a>
                    </li>
                    <li>
                        <a href="./rating.php" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
                            <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
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
                                <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                            </svg>    
                            <a href="./mahasiswa.php" class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap group-hover:text-yellow-400">User</a>
                            <svg class="w-3 h-3 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                               <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul id="dropdown-user" class="hidden py-2 space-y-2">
                            <li>
                               <a href="./mahasiswa.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Mahasiswa</a>
                            </li>
                            <li>
                               <a href="./dosen.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Dosen</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="unit_layanan.php" class="flex items-center p-2 text-gray-50 rounded-lg bg-blue-950 group">
                            <div class="rounded-full p-2 bg-yellow-400">
                                <svg class="w-6 h-6 text-gray-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 2a7 7 0 0 0-7 7 3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1a1 1 0 0 0 1-1V9a5 5 0 1 1 10 0v7.083A2.919 2.919 0 0 1 14.083 19H14a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1a2 2 0 0 0 1.732-1h.351a4.917 4.917 0 0 0 4.83-4H19a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3 7 7 0 0 0-7-7Zm1.45 3.275a4 4 0 0 0-4.352.976 1 1 0 0 0 1.452 1.376 2.001 2.001 0 0 1 2.836-.067 1 1 0 1 0 1.386-1.442 4 4 0 0 0-1.321-.843Z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span class="ms-3">Unit Layanan</span>
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
                        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 hover:text-yellow-400">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                            </svg>
                        </button>
                        <span class="hidden font-semibold text-xl text-[#060A47] sm:inline-block">Unit Layanan</span>
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
                            <div id="notifications" class="p-4 flex flex-col space-y-2">
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
                            <img class="w-8 h-8 rounded-full" src="<?php echo $user_image; ?>" alt="user photo">
                        </button>
                        <!-- Dropdown profile -->
                        <div class="hidden z-50 my-4 w-56 text-base list-none bg-white rounded divide-y divide-gray-100 shadow" id="dropdown">
                            <div class="py-3 px-4">
                                <span class="block text-sm font-semibold text-gray-900 "><?php echo $user['nama'];?></span>
                                <span class="block text-sm text-gray-500 truncate "><?php echo $user['email'];?></span>
                            </div>
                            <ul class="py-1 text-gray-500 " aria-labelledby="dropdown">
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
                        <input type="hidden" name="role" id="role" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['role']; ?>"  required />
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

            <!-- CONTENT -->
            <div class="flex justify-between">
                <!-- Bagian atas -->
                <p class="p-2 text-gray-500" style="font-size: 10px;">Update terakhir: 1 September 2024 (20:00 WIB)</p>
                <p class="p-2 text-gray-500" style="font-size: 10px;">Σ Jumlah: 2000 Unit Layanan</p>
            </div>

            <!-- Bagian Kartu Utama -->
            <div class="bg-white p-4 border-2 border-gray-200 rounded-lg">
                <!-- INI HEADER -->
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-bold p-0 text-lg">Data Unit Layanan</p>
                        <p class="text-xs text-gray-500">Detail Unit Layanan</p>
                    </div>
                    <div class="ml-auto">
                        <button id="openModalBtn" class="text-sm text-gray-600 mr-4 hover:text-blue-600 hover:underline"><span class="text-blue-600 font-semibold">+ </span>Tambah</button>
                    </div>
                    <form class="flex-grow max-w-sm">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="search" class="block w-full p-2 pl-9 text-sm border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="nama, nim, jurusan, atau prodi.." required onkeyup="searchTable()" />
                        </div>
                    </form>
                </div>

                <!-- Kartu Layanan -->
                <?php
                        $no = 1;
                        foreach ($db->tampil_instansi() as $x) {
                        ?>
                            <div class="mt-4 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 scrollbar-track-transparent shadow-lg rounded-lg shadow-gray-400 w-full" style="max-height: 400px;">
                                <div class="space-y-4">
                                    <!-- Kartu Poliklinik -->
                                    <div class="bg-white shadow-md overflow-hidden">
                                        <div class="relative">
                                            <img src="<?=$x['image_instansi'] == null ? "assets/data_rating.png" : "./Back-end".$x['image_instansi'];?>" alt="Poliklinik" class="w-full h-52 object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900 opacity-35"></div>
                                        </div>
                                        <div class="p-4 flex justify-between items-center">
                                            <div>
                                                <div class="font-semibold text-blue-950"><?php echo $x['nama_instansi']; ?></div>
                                                <div class="text-gray-500"><?=$x['email_pic'];?></div>
                                            </div>
                                            <button id="editModalButton" data-modal-target="editModal" data-modal-toggle="editModal" type="button" class="inline text-blue-600 hover:underline font-medium text-sm"
                                                data-id="<?=$x['id_instansi'];?>"
                                                data-nama="<?=$x['nama_instansi'];?>"
                                                data-email="<?=$x['email_pic'];?>"
                                                data-image="<?=$x['image_instansi'];?>"
                                                data-qrcode = "<?=$x['qr_code_url'];?>"
                                                data-jeda-rating = "<?=$x['jeda_waktu_rating'];?>">
                                                Edit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
            </div>
            

            <!-- Modal Form -->
            <div id="modal" class="fixed inset-0 items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                <div class="bg-white rounded-lg p-4 w-full max-w-4xl h-auto relative">
                    <!-- Tombol Tutup Modal -->
                    <div class="flex justify-between items-center mb-4 sm:mb-5">
                        <div>
                            <h2 class="text-2xl font-semibold">Form Unit Layanan</h2>
                            <p class="text-gray-500">Tambah Unit Layanan</p>
                        </div>
                        <button id="closeModalBtn" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>

                    
                    <!-- Input Fields -->
                    <form action="Back-end/tambah_instansi.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                        <!-- Gambar Form -->
                        <div class="flex justify-center items-center mb-4">
                            <img id="unit-layanan-preview" src="<?=$instansi_image;?>" alt="Gambar" class="w-auto h-40 rounded-md">
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="w-1/3">Nama Unit Layanan</label>
                            <input type="text" name="nama_instansi" class="w-full border border-gray-300 rounded-md p-2" required/>
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="w-1/3">Email PIC</label>
                            <input type="email" name="email_pic" class="w-full border border-gray-300 rounded-md p-2" required/>
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="w-1/3">Jeda Waktu Rating</label>
                            <div class="flex space-x-2 items-center w-full">
                                <input type="number" name="jeda_waktu_rating" class="border border-gray-300 rounded-md p-2" required/>
                                <span>Hari</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <label class="w-1/3">Gambar Banner</label>
                            <input type="file" name="image_instansi" accept="image/jpeg, image/png, image/jpg" class="w-full border border-gray-300 rounded-md" onchange="previewImageUnitLayanan(event)"/>
                        </div>
                        <!-- Tombol Tambah -->
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600">+ Tambah</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal: Edit Form -->
            <div id="editModal" tabindex="-1" aria-hidden="true" class="hidden fixed z-50 items-center justify-center w-full md:inset-0 h-modal md:min-h-screen">
                <div class="bg-white rounded-lg p-4 w-full max-w-4xl h-auto relative md:mih-h-screen">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-2xl font-semibold">Form Unit Layanan</h2>
                            <p class="text-gray-500">Detail Unit Layanan</p>
                        </div>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="editModal"> 
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>                         
                        </button>
                    </div>

                    <!-- Image Section -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center justify-center border rounded-lg p-4">
                            <img name="image-unit" alt="Unit Image" class="w-full h-auto object-contain">
                        </div>
                        <div class="flex items-center justify-center border rounded-lg p-4">
                            <img name="qr-code" alt="QR Code" class="w-32 h-auto object-contain">
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <form id="editForm" action="./Back-end/update_instansi.php" method="POST">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <input type="hidden" name="id-layanan" id="idLayanan" class="w-full p-3 border border-gray-300 rounded" />
                            <div>
                                <label for="namaLayanan" class="block text-gray-700 text-sm">Nama Unit Layanan</label>
                                <input type="text" name="nama-layanan" id="namaLayanan" class="w-full p-3 border border-gray-300 rounded" />
                            </div>
                            <div>
                                <label for="emailPIC" class="block text-gray-700 text-sm">Email PIC</label>
                                <input type="email" name="email-pic" id="emailPIC" class="w-full p-3 border border-gray-300 rounded" />
                            </div>
                            <div class="flex items-center space-x-2">
                                <label for="ratingJeda" class="block text-gray-700 text-sm flex-grow">Jeda Waktu Rating</label>
                                <input type="text" name="rating-jeda" id="ratingJeda" class="w-full p-3 border border-gray-300 rounded" />
                                <span class="text-gray-700">Hari</span>
                            </div>
                            <!-- Action Buttons -->
                            <div class="flex justify-end items-center col-span-2 mt-8 space-x-4">
                                <button type="submit" class="flex items-center bg-blue-700 text-white px-6 py-3 rounded hover:bg-blue-600" onclick="submitEditForm()">
                                    <span>Simpan</span>
                                </button>
                                <button type="submit" class="flex items-center bg-red-700 text-white px-6 py-3 rounded hover:bg-red-600" onclick="confirmHapus()">
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
  
        <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
        <script>
            const openModalBtn = document.getElementById('openModalBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const modal = document.getElementById('modal');

            openModalBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            closeModalBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            function searchTable() {
                let input = document.querySelector('input[type="search"]').value.toLowerCase();
                let rows = document.querySelectorAll('.space-y-4 > div');

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    if (text.includes(input)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            function previewImageUnitLayanan(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('unit-layanan-preview');
                    output.src = reader.result;
                }
                reader.readAsDataURL(event.target.files[0]);
            }
            
            // Fungsi untuk menampilkan modal update product
            document.addEventListener("DOMContentLoaded", function(event) {
                document.getElementById('editModalButton').click();
            });
            
            document.addEventListener('DOMContentLoaded', function() {
                const updateButtons = document.querySelectorAll('#editModalButton');
                
                updateButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = button.getAttribute('data-id');
                        const nama = button.getAttribute('data-nama');
                        const emailPIC = button.getAttribute('data-email');
                        const image = button.getAttribute('data-image');
                        const qrcode = button.getAttribute('data-qrcode');
                        const jedaRating = button.getAttribute('data-jeda-rating');

                        const imageElement = document.querySelector('#editModal img[name="image-unit"]');
                        // Periksa apakah ada gambar
                        if (image && image !== "assets/data_rating.png") {
                            // Jika gambar ada dan bukan gambar default
                            imageElement.src = "./Back-end" + image;
                        } else {
                            // Jika gambar tidak ada atau gambar default
                            imageElement.src = "assets/data_rating.png";
                        }
                        // Populate modal fields
                        document.querySelector('#editModal input[name="id-layanan"]').value = id;
                        document.querySelector('#editModal input[name="nama-layanan"]').value = nama;
                        document.querySelector('#editModal input[name="email-pic"]').value = emailPIC;
                        document.querySelector('#editModal img[name="qr-code"]').src = "/Wicara_Admin_Web"+qrcode;
                        document.querySelector('#editModal input[name="rating-jeda"]').value = jedaRating;
                    });
                });
            });

            function submitEditForm() {
                const form = document.getElementById('editForm');
                const formData = new FormData(form);

                fetch('./Back-end/update_instansi.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tambahkan kode untuk memperbarui UI jika perlu
                    } else {
                        alert("Gagal menyimpan data: " + data.message);
                    }
                })
            }

            function confirmHapus() {
                const id = document.querySelector('#editForm input[name="id-layanan"]').value;

                fetch('./Back-end/delete_instansi.php?id=' + id, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Data berhasil dihapus.");
                        // Tambahkan kode untuk menghapus data dari UI jika perlu
                    } else {
                        alert("Gagal menghapus data: " + data.message);
                    }
                })
            }
        </script>
        <!-- INII SCRIPT NOTIF -->
        <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.js"></script>
        <script>
            const notifications = [
                { type: 'pengaduan', title: 'Kamar mandi Kotor', time: '2h ago', avatar: 'https://placehold.co/40x40?text=1' },
                { type: 'rating', title: 'Poliklinik', time: '2h ago', rating: 4, avatar: 'https://placehold.co/40x40?text=2' },
                { type: 'kehilangan', title: 'Pacar ku Hilang', time: '2h ago', avatar: 'https://placehold.co/40x40?text=3' },
                { type: 'pengaduan', title: 'Dosen suka bolos', time: '2h ago', avatar: 'https://placehold.co/40x40?text=4' },
                { type: 'rating', title: 'Poliklinik', time: '2h ago', rating: 4, avatar: 'https://placehold.co/40x40?text=5' },
                { type: 'kehilangan', title: 'Pacar ku Hilang', time: '2h ago', avatar: 'https://placehold.co/40x40?text=6' },
                { type: 'rating', title: 'Poliklinik', time: '2h ago', rating: 4, avatar: 'https://placehold.co/40x40?text=7' },
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
                    const notificationElement = document.createElement('button');
                    notificationElement.classList.add('tab-button', 'py-2', 'px-4', 'text-gray-500', 'w-full');
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