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

    // Get the selected status from the query parameter, default to 'semua'
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'semua';

    // Get the current page number, default to 1 if not set
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $itemsPerPage = 10;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Filter the data based on the selected status
    $filteredKehilangan = array_filter($db->tampil_data_kehilangan(), function($x) use ($statusFilter) {
        if ($statusFilter === 'semua') {
            return true; // Show all if 'semua' is selected
        }
        return strtolower($x['nama_status_kehilangan']) === $statusFilter;
    });

    // Calculate the total number of items and pages based on filtered data
    $totalKehilangan = count($filteredKehilangan);
    $totalPages = ceil($totalKehilangan / $itemsPerPage);

    // Fetch the items for the current page
    $kehilanganToShow = array_slice($filteredKehilangan, $offset, $itemsPerPage);

    // Start numbering from the current offset
    $no = $offset + 1;

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kehilangan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif !important;
                background-image: url('assets/Pattern-cover.png');
                background-size: cover;
                background-color: #F6F6F6;
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
                            <svg class="w-6 h-6 text-gray-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8 7V2.221a2 2 0 0 0-.5.365L3.586 6.5a2 2 0 0 0-.365.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.126a5.087 5.087 0 0 0-4.74 1.368v.001l-6.642 6.642a3 3 0 0 0-.82 1.532l-.74 3.692a3 3 0 0 0 3.53 3.53l3.694-.738a3 3 0 0 0 1.532-.82L19 15.149V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M17.447 8.08a1.087 1.087 0 0 1 1.187.238l.002.001a1.088 1.088 0 0 1 0 1.539l-.377.377-1.54-1.542.373-.374.002-.001c.1-.102.22-.182.353-.237Zm-2.143 2.027-4.644 4.644-.385 1.924 1.925-.385 4.644-4.642-1.54-1.54Zm2.56-4.11a3.087 3.087 0 0 0-2.187.909l-6.645 6.645a1 1 0 0 0-.274.51l-.739 3.693a1 1 0 0 0 1.177 1.176l3.693-.738a1 1 0 0 0 .51-.274l6.65-6.646a3.088 3.088 0 0 0-2.185-5.275Z" clip-rule="evenodd"/>
                            </svg>  
                            <a href="./lihat_pengaduan.php" class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Pengaduan</a>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <ul id="dropdown-pengaduan" class=" py-2 space-y-2 hidden">
                            <li>
                                <a href="./lihat_pengaduan.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Lihat Pengaduan</a>
                            </li>
                            <li>
                                <a href="./kategori_pengaduan.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Kategori Pengaduan</a>
                            </li>
                        </ul>  
                    </li>
                    <li>
                        <a href="#" class="flex items-center p-2 text-gray-50 rounded-lg bg-blue-950 group">
                          <div class="rounded-full p-2 bg-yellow-400">
                            <svg class="w-6 h-6 text-gray-50 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M9 7V2.221a2 2 0 0 0-.5.365L4.586 6.5a2 2 0 0 0-.365.5H9Zm2 0V2h7a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Zm.5 5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3Zm0 5c.47 0 .917-.092 1.326-.26l1.967 1.967a1 1 0 0 0 1.414-1.414l-1.817-1.818A3.5 3.5 0 1 0 11.5 17Z" clip-rule="evenodd"/>
                            </svg>                                
                          </div>
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
                               <a href="./dosen.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Dosen/Tendik</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="./unit_layanan.php" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
                            <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2a7 7 0 0 0-7 7 3 3 0 0 0-3 3v2a3 3 0 0 0 3 3h1a1 1 0 0 0 1-1V9a5 5 0 1 1 10 0v7.083A2.919 2.919 0 0 1 14.083 19H14a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h1a2 2 0 0 0 1.732-1h.351a4.917 4.917 0 0 0 4.83-4H19a3 3 0 0 0 3-3v-2a3 3 0 0 0-3-3 7 7 0 0 0-7-7Zm1.45 3.275a4 4 0 0 0-4.352.976 1 1 0 0 0 1.452 1.376 2.001 2.001 0 0 1 2.836-.067 1 1 0 1 0 1.386-1.442 4 4 0 0 0-1.321-.843Z" clip-rule="evenodd"/>
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
                        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 hover:text-yellow-400">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
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

            <!-- CONTENT INII REAL CUY -->
            <div class="relative overflow-x-auto mb-3 bg-white px-3 drop-shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <!-- INII TABS STATUS -->
                    <div class="text-sm font-medium text-center bg-white text-gray-500 border-b border-gray-200">
                        <ul class="flex flex-wrap -mb-px">
                            <li class="me-2">
                                <a href="?status=semua" class="status-tab inline-block p-4 text-yellow-400 border-b-2 border-yellow-400 rounded-t-lg active" data-status="semua">Semua</a>
                            </li>
                            <li class="me-2">
                                <a href="?status=diajukan" class="status-tab inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" data-status="diajukan">Diajukan</a>
                            </li>
                            <li class="me-2">
                                <a href="?status=belum ditemukan" class="status-tab inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" data-status="belum ditemukan">Belum Ditemukan</a>
                            </li>
                            <li class="me-2">
                                <a href="?status=ditemukan" class="status-tab inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" data-status="ditemukan">Ditemukan</a>
                            </li>
                            <li class="me-2">
                                <a href="?status=hilang" class="status-tab inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" data-status="hilang">Hilang</a>
                            </li>
                            <form class="flex-grow mx-auto">
                                <div class="relative top-2">
                                    <div class="absolute top-2.5 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input type="search" id="default-search" class="block w-full px-4 py-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Anything" onkeyup="searchTable()" required />
                                </div>
                            </form>
                        </ul>
                    </div>

                    <!-- INII JUDUL KOLOM -->
                    <thead class="text-[#858585] text-xs bg-gray-50 text-center">
                        <tr>
                            <th scope="col" class="px-2.5 py-2 font-light">
                                No
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                ID Laporan
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Judul Laporan
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Tanggal
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Jenis Barang
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Deskripsi
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Tanggal Kadaluwarsa
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <!-- INII ISI KOLOM -->
                    <tbody class="text-center">
                        <?php 
                        // Initialize the overall row index
                        static $overallRowIndex = 0;
                        $visibleRowIndex = 0; // Counter for visible rows

                        // Loop through the users to show
                        foreach ($kehilanganToShow as $x): 
                            // Increment the overall row index
                            $overallRowIndex++;
                            
                            // Check if the row should be displayed
                            $isVisible = true; // This should be dynamically set based on the search in JavaScript
                            
                            if ($isVisible) {
                                // Increment the visible row index if the row is visible
                                $visibleRowIndex++;
                            }
                        ?>
                            <tr class="bg-white border-b" style="<?php echo $isVisible ? '' : 'display: none;'; ?>" data-status="<?php echo strtolower($x['nama_status_kehilangan']); ?>">
                                <th scope="row" class="px-3 py-4">
                                    <?php echo $no++; ?>
                                </th>
                                <td class="px-6 py-4">
                                    <?php echo $x['id_kejadian']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $x['judul']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $x['tanggal']; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php echo $x['jenis_barang']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo substr($x['deskripsi'], 0, 30) . '...'; ?>
                                </td>
                                <td class="">
                                    <?php
                                        if($x['nama_status_kehilangan'] == "Diajukan"){
                                            echo '<span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1 rounded">Diajukan</span>';
                                        }elseif($x['nama_status_kehilangan'] == "Belum Ditemukan"){
                                            echo '<span class="bg-yellow-100 text-yellow-400 text-xs font-medium px-3 py-1 rounded">Belum Ditemukan</span>';
                                        }elseif($x['nama_status_kehilangan'] == "Ditemukan"){
                                            echo '<span class="bg-green-200 text-green-600 text-xs font-medium px-3 py-1 rounded">Ditemukan</span>';
                                        }elseif($x['nama_status_kehilangan'] == "Hilang"){
                                            echo '<span class="bg-red-200 text-red-600 text-xs font-medium px-3 py-1 rounded">Hilang</span>';
                                        }
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php echo $x['tanggal_kadaluwarsa']; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button id="updateProductButton" data-modal-target="updateProductModal" data-modal-toggle="updateProductModal" class="inline text-blue-600 hover:underline font-medium text-sm" type="button" 
                                        data-id="<?php echo $x['id_kejadian']; ?>"
                                        data-user="<?php echo $x['nama']; ?>"
                                        data-judul="<?php echo $x['judul']; ?>"
                                        data-jenis="<?php echo $x['jenis_barang']; ?>"
                                        data-tanggal="<?php echo $x['tanggal']; ?>"
                                        data-status="<?php echo $x['nama_status_kehilangan']; ?>"
                                        data-lokasi="<?php echo $x['lokasi']; ?>"
                                        data-tanggal-kadaluwarsa="<?php echo $x['tanggal_kadaluwarsa']; ?>"
                                        data-lampiran="<?php echo $x['lampiran']; ?>"
                                        data-deskripsi="<?php echo $x['deskripsi']; ?>">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        <?php 
                            endforeach; 
                            // Handle remaining rows
                            $remainingRows = $itemsPerPage - count($kehilanganToShow);
                            for ($i = 0; $i < $remainingRows; $i++): 
                                // Increment overall row index for empty rows
                                $overallRowIndex++;
                        ?>
                        <?php 
                        endfor 
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- PAGENATION -->
            <nav aria-label="Page navigation example" class="flex justify-end">
                <ul class="inline-flex -space-x-px text-sm">
                    <li>
                        <a href="?status=<?php echo $statusFilter; ?>&page=<?php echo max(1, $currentPage - 1); ?>" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="?status=<?php echo $statusFilter; ?>&page=<?php echo $i; ?>" class="flex items-center justify-center px-3 h-8 leading-tight <?php echo $i === $currentPage ? 'text-blue-600 border border-gray-300 bg-blue-50' : 'text-gray-500 bg-white border-gray-300'; ?> hover:bg-gray-100 hover:text-gray-700"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li>
                        <a href="?status=<?php echo $statusFilter; ?>&page=<?php echo min($totalPages, $currentPage + 1); ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">Next</a>
                    </li>
                </ul>
            </nav>
        </div>


        <!-- Main modal -->
        <div id="updateProductModal" tabindex="-1" aria-hidden="true" class="hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:min-h-screen">
            <div class="relative p-4 mx-4 w-full max-w-2xl h-auto md:min-h-screen">
                <!-- Modal content -->
                <div class="relative p-4 bg-white rounded-lg overflow-y-auto max-h-screen shadow sm:p-5">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Detail Laporan
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-toggle="updateProductModal">
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form action="simpan_edit_kejadian_pengaduan.php" method="POST">
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">
                            <div>
                                <label for="id_kejadian" class="block mb-2 text-sm font-medium text-gray-900">ID Laporan</label>
                                <input type="text" name="id_kejadian" id="id_kejadian" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="user" class="block mb-2 text-sm font-medium text-gray-900">User</label>
                                <input type="text" name="user" id="user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="judul" class="block mb-2 text-sm font-medium text-gray-900">Judul Laporan</label>
                                <input type="text" name="judul" id="judul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="jenis_barang" class="block mb-2 text-sm font-medium text-gray-900">Jenis Barang</label>
                                <input type="text" name="jenis_barang" id="jenis_barang" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900">Tanggal</label>
                                <input type="text" name="tanggal" id="tanggal" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status</label>
                                <input type="text" name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="lokasi" class="block mb-2 text-sm font-medium text-gray-900">Lokasi</label>
                                <input type="text" name="lokasi" id="lokasi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="tanggal_kadaluwarsa" class="block mb-2 text-sm font-medium text-gray-900">tanggal kadaluwarsa</label>
                                <input type="text" name="tanggal_kadaluwarsa" id="tanggal_kadaluwarsa" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                                <textarea type="text" name="deskripsi" id="deskripsi" rows=5 class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly></textarea>
                            </div>
                            <div class="sm:col-span-2">
                                <label for="lampiran" class="block mb-2 text-sm font-medium text-gray-900">Lampiran</label>
                                <img id="lampiran" class="w-full h-fit rounded-lg object-cover" alt="Lampiran">
                            </div>
                        </div>
                        <!-- Buttons area inside the modal -->
                        <div class="flex items-center justify-end space-x-4" id="actionButtons">
                            <!-- Accept Button -->
                            <button type="submit" id="acceptButton" class="text-white bg-blue-600 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center hidden">
                                Terima
                            </button>

                            <!-- Delete Button -->
                            <button type="button" id="deleteButton" class="text-red-600 items-center hover:text-white border border-red-600 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center hidden">
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
 
  
        <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <!-- INII SCRIPT -->
        <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.js"></script>
        <script>
            // Search Table
            function searchTable() {
                const searchInput = document.getElementById('default-search').value.toLowerCase();
                const tableRows = document.querySelectorAll('table tbody tr');
                let visibleRowIndex = 0; // Initialize a visible row index

                tableRows.forEach(row => {
                    const cells = row.getElementsByTagName('td');
                    let found = false;

                    for (let i = 0; i < cells.length; i++) {
                        const cellText = cells[i].innerText.toLowerCase();
                        if (cellText.includes(searchInput)) {
                            found = true;
                            break;
                        }
                    }

                    // Show or hide the row based on the search results
                    if (found) {
                        row.style.display = ''; // Show the row
                        visibleRowIndex++;
                    } else {
                        row.style.display = 'none'; // Hide the row
                    }
                });
            }

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
                    notificationElement.classList.add('tab-button', 'py-2', 'px-4', 'text-gray-500', 'w-full', 'flex', 'items-start');
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

                    // Add click event to navigate based on notification type
                    notificationElement.addEventListener('click', () => {
                        if (notification.type === 'pengaduan') {
                            window.location.href = 'lihat_pengaduan.php';
                        } else if (notification.type === 'rating') {
                            window.location.href = 'rating.php';
                        } else if (notification.type === 'kehilangan') {
                            window.location.href = 'kehilangan.php'; // Replace with the correct page
                        }
                    });

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

            // Fungsi untuk menampilkan modal update product
            document.addEventListener("DOMContentLoaded", function(event) {
                document.getElementById('updateProductButton').click();
            });

            // Fungsi untuk menampilkan modal update product
            document.addEventListener('DOMContentLoaded', function() {
                const updateButtons = document.querySelectorAll('#updateProductButton');
                
                updateButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const id = button.getAttribute('data-id');
                        const user = button.getAttribute('data-user');
                        const judul = button.getAttribute('data-judul');
                        const jenis_barang = button.getAttribute('data-jenis');
                        const tanggal = button.getAttribute('data-tanggal');
                        const status = button.getAttribute('data-status').toLowerCase();
                        const lokasi = button.getAttribute('data-lokasi');
                        const tanggal_kadaluwarsa = button.getAttribute('data-tanggal-kadaluwarsa');
                        const lampiran = button.getAttribute('data-lampiran');
                        const deskripsi = button.getAttribute('data-deskripsi');

                        // Populate modal fields
                        document.querySelector('#updateProductModal input[name="id_kejadian"]').value = id;
                        document.querySelector('#updateProductModal input[name="user"]').value = user;
                        document.querySelector('#updateProductModal input[name="judul"]').value = judul;
                        document.querySelector('#updateProductModal input[name="jenis_barang"]').value = jenis_barang;
                        document.querySelector('#updateProductModal input[name="tanggal"]').value = tanggal;
                        document.querySelector('#updateProductModal input[name="status"]').value = status;
                        document.querySelector('#updateProductModal input[name="lokasi"]').value = lokasi;
                        document.querySelector('#updateProductModal input[name="tanggal_kadaluwarsa"]').value = tanggal_kadaluwarsa;

                        const deskripsiField = document.querySelector('#updateProductModal textarea[name="deskripsi"]');
                        if (deskripsiField) deskripsiField.value = deskripsi;

                        const lampiranField = document.querySelector('#updateProductModal img[id="lampiran"]');
                        if (lampiranField) {
                            lampiranField.src = lampiran ? `./Back-end/foto-kehilangan/${lampiran}` : "./assets/default-image.png";
                        }

                        // Show or hide buttons based on the status
                        const acceptButton = document.getElementById('acceptButton');
                        const deleteButton = document.getElementById('deleteButton');

                        if (status === 'diajukan') {
                            // Show "Terima" buttons, hide "Delete"
                            acceptButton.classList.remove('hidden');
                            deleteButton.classList.add('hidden');
                        } else {
                            // Show "Delete" button, hide "Terima"
                            acceptButton.classList.add('hidden');
                            deleteButton.classList.remove('hidden');
                        }
                    });
                });

                const acceptButton = document.getElementById('acceptButton');
                const deleteButton = document.getElementById('deleteButton');
                
                // Mengambil id kejadian dari modal
                const getIdKejadian = () => document.querySelector('#updateProductModal input[name="id_kejadian"]').value;

                // Fungsi AJAX untuk mengirim data
                function sendAction(action) {
                    const idKejadian = getIdKejadian();
                    fetch('Back-end/update_status_kehilangan.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `id_kejadian=${idKejadian}&action=${action}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            location.reload(); // Refresh halaman setelah aksi berhasil
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }

                // Event listeners untuk tombol aksi
                acceptButton.addEventListener('click', () => sendAction('terima'));
                deleteButton.addEventListener('click', () => {
                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        sendAction('delete');
                    }
                });
            });


            // Fungsi untuk menampilkan tab status
            document.addEventListener('DOMContentLoaded', function () {
                // Get all the status tabs
                const tabs = document.querySelectorAll('.status-tab');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Get the status from the tab
                        const status = tab.getAttribute('data-status');

                        // Get all table rows
                        const rows = document.querySelectorAll('tbody tr');

                        // Show/hide rows based on the selected status
                        rows.forEach(row => {
                            const rowStatus = row.getAttribute('data-status');
                            if (status === 'semua' || rowStatus === status) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        // Update the active tab styling
                        tabs.forEach(t => {
                            t.classList.remove('text-yellow-400', 'border-yellow-400', 'active');
                            t.classList.add('hover:text-gray-600', 'hover:border-gray-300', 'border-transparent');
                        });

                        // Add active styling to the clicked tab
                        tab.classList.add('text-yellow-400', 'border-yellow-400', 'active');
                        tab.classList.remove('hover:text-gray-600', 'hover:border-gray-300', 'border-transparent');
                    });
                });
            });
        </script>
        <script src="../path/to/flowbite/dist/flowbite.min.js"></script>


    </body>
</html>