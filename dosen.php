<?php
    require_once('t_function.php');
    // buat update profile
    session_start();

    if (!isset($_SESSION['id_user'])) {
        header("Location: ../../Wicara_User_Web/index.php"); // Jika belum login, redirect ke halaman login
    }

    $id_user = $_SESSION['id_user'];
    $user_data = mysqli_query($db->koneksi, "SELECT * FROM user WHERE id_user = '$id_user'");
    $user = mysqli_fetch_assoc($user_data);
    $user_image = $user['profile'] ? 'Wicara_Admin_Web/'.$user['profile'] : 'assets/user.png';

    // Get the current page number, default to 1 if not set
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $itemsPerPage = 5;
    $offset = ($currentPage - 1) * $itemsPerPage;

    // Get the total number of users with role 3
    $totalUsers = count(array_filter($db->tampil_user(), function($x) {
        return $x['role'] == 2;
    }));

    // Calculate the total number of pages
    $totalPages = ceil($totalUsers / $itemsPerPage);

    // Fetch the users for the current page
    $users = array_filter($db->tampil_user(), function($x) {
        return $x['role'] == 2;
    });
    $usersToShow = array_slice($users, $offset, $itemsPerPage);

    // Display the users in the table
    $no = $offset + 1; // Start numbering from the current offset

    // Query untuk mengambil waktu update terakhir dan jumlah dosen dengan role 2
    $result = $mysqli->query("SELECT MAX(updated_at) AS last_update, COUNT(*) AS total_dosen FROM user WHERE role = 2");
    $row = $result->fetch_assoc();
    $lastUpdate = $row['last_update'];
    $totalDosen = $row['total_dosen'];

    $allUsersQuery = "SELECT * FROM user WHERE role = 2";
    $allUsersResult = mysqli_query($db->koneksi, $allUsersQuery);
    $allUsers = mysqli_fetch_all($allUsersResult, MYSQLI_ASSOC);

?>

<script>
    const allUsers = <?php echo json_encode($allUsers); ?>;
</script>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="t_style_notif.css">
        <script src="t_skrip_notif.js" defer></script>
        <style>
            body {
                font-family: 'Poppins', sans-serif !important;
                background-image: url('assets/Pattern_1.png');
                background-size: cover;
                background-color: #f9f9f9;
            }

            .color-linear {
                background: rgb(6,10,71);
                background: linear-gradient(135deg, rgba(6,10,71,1) 0%, rgba(25,48,109,1) 100%);
            }

            .max-h-60 {
                max-height: 240px; /* Set height according to your needs */
            }

            .even {
                background-color: #f9f9f9; /* Warna latar untuk baris genap */
            }

            .odd {
                background-color: #ffffff; /* Warna latar untuk baris ganjil */
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
                            <span class="ms-3 group-hover:text-yellow-400">Unit Layanan</span>
                        </a>
                    </li>
                    <li>
                        <p class="ms-5 text-gray-50 font-semibold my-4">Setting</p>
                    </li>
                    <li>
                        <button type="button" class="flex items-center w-full p-2 text-base text-gray-50 rounded-lg group bg-blue-950" aria-controls="dropdown-user" data-collapse-toggle="dropdown-user">
                            <div class="rounded-full p-2 bg-yellow-400">
                                <svg class="w-6 h-6 text-gray-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                                </svg>    
                            </div>
                            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">User</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                               <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <!-- Mahasiswa - Dosen -->
                        <ul id="dropdown-user" class="py-2 space-y-2">
                            <li>
                                <a href="mahasiswa.php" class="flex items-center w-full p-2 text-gray-50 transition duration-75 rounded-lg pl-11 group hover:text-yellow-400 text-sm">Mahasiswa</a>
                            </li>
                            <li>
                               <a href="./dosen.php" class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group text-yellow-400 text-sm">Dosen</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        
        <!-- CONTENT INII -->
        <div class="py-4 px-4 sm:ml-64">
            <!-- NAVBAR INII -->
            <nav class="w-full lg:px-0 pb-4">
                <div class="flex flex-wrap justify-between items-center">
                <div class="flex items-center">
                        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 hover:text-yellow-400">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                            </svg>
                        </button>
                        <span class="hidden font-semibold text-xl text-[#060A47] sm:inline-block">User &gt; Dosen</span>
                    </div>
                    <div class="flex items-center lg:order-2">
                        <!-- INII Notifications -->
                        <div class="relative">
                            <button type="button" id="notificationButton" class="relative p-2 mr-2 text-gray-400 rounded-lg hover:text-yellow-400 hover:bg-gray-100">
                                <span class="sr-only">View notifications</span>
                                
                                <!-- Red dot for notification count -->
                                <div class="absolute top-3 right-2 translate-x-1/2 -translate-y-1/2 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full dark:border-gray-900 hidden">
                                    7
                                </div>
                                
                                <!-- Bell icon -->
                                <svg class="w-7 h-7" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 14 20">
                                    <path d="M12.133 10.632v-1.8A5.406 5.406 0 0 0 7.979 3.57.946.946 0 0 0 8 3.464V1.1a1 1 0 0 0-2 0v2.364a.946.946 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C1.867 13.018 0 13.614 0 14.807 0 15.4 0 16 .538 16h12.924C14 16 14 15.4 14 14.807c0-1.193-1.867-1.789-1.867-4.175ZM3.823 17a3.453 3.453 0 0 0 6.354 0H3.823Z"/>
                                </svg>
                            </button>
                        </div>
                        <div id="notificationSidebar" class="fixed top-0 right-0 w-80 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 overflow-y-auto">
                            <div class="sticky top-0 bg-white z-10">
                                <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                                    <button id="closeSidebarButton" class="text-gray-500 text-2xl">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                    <h1 class="text-center text-xl font-bold flex-1">Notifikasi</h1>
                                </div>
                                <div class="flex justify-around border-b border-gray-200 overflow-x-auto">
                                    <button id="tab-semua" class="tab-button py-2 px-4 text-gray-500" onclick="populateNotifications('semua')">Semua</button>
                                    <button id="tab-pengaduan" class="tab-button py-2 px-4 text-gray-500" onclick="populateNotifications('pengaduan')">Pengaduan</button>
                                    <button id="tab-kehilangan" class="tab-button py-2 px-4 text-gray-500" onclick="populateNotifications('kehilangan')">Kehilangan</button>
                                    <button id="tab-rating" class="tab-button py-2 px-4 text-gray-500" onclick="populateNotifications('rating')">Rating</button>
                                </div>
                            </div>
                            <!-- Elemen tersembunyi untuk data notifikasi -->
                            <div id="notifications-data" style="display: none;">
                                <?php echo $notificationsJSON; ?>
                            </div>
                            <div id="notifications" class="p-4 flex flex-col space-y-2">
                                <!-- Notifications will be dynamically inserted here -->
                            </div>
                            <div id="showAllButtonContainer" class="text-center p-4 hidden">
                                <button id="showAllButton" class="text-blue-500 underline">Tampilkan Semua</button>
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

            <!-- ATAS TABEL -->
            <p class="p-2 text-gray-500 text-sm">
                Update terakhir: <?php
                // Check if $lastUpdate is not empty and valid before using strtotime
                if (!empty($lastUpdate)) {
                    echo date("j F Y (H:i T)", strtotime($lastUpdate));
                } else {
                    echo "Belum ada update"; // Fallback message if no update is available
                }
                ?>
            </p>

            <!-- BAGIAN TABEL -->
            <div class="bg-white p-4 border-2 border-gray-200 shadow-md rounded-lg">
                <!-- ROW ATAS -->
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <p class="font-bold p-0 text-lg">Data Dosen</p>
                        <p class="text-xs text-gray-500">Detail Dosen</p>
                    </div>
                    <div class="ml-auto">
                        <button onclick="togglePopup()" id="openModalBtn" class="mb-3 text-sm text-gray-600 mr-4 hover:text-blue-600 hover:underline"><span class="text-blue-600 font-semibold">+ </span>Tambah</button>
                    </div>
                    <!-- SEARCH -->
                    <form id="search-form" class="flex-grow max-w-sm">
                        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="search" id="default-search" class="block w-full p-2 pl-9 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="search anything..." required onkeyup="searchTable()" />
                        </div>
                    </form>
                    <div id="popup" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center hidden">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                        <h3 class="flex items-center justify-between text-lg font-semibold text-gray-700 mb-4">
                            Tambah Dosen
                            <button onclick="togglePopup()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </h3>
                        <form action="Back-end/simpan_tambah_dosen.php" method="POST">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
                                <input type="text" name="nama" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">NIP</label>
                                <input type="text" name="nomor_induk" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nomer Telepon</label>
                                <input type="text" name="nomor_telepon" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                                <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

                </div>
                <!-- TABEL -->
                <table class="w-full">
                <thead class="text-[#858585] text-xs bg-gray-50">
                        <tr>
                            <th scope="col" class="text-center px-2.5 py-2 font-light">
                                No
                            </th>
                            <th scope="col" class="text-center px-2 py-2 font-light">
                                Profile
                            </th>
                            <th scope="col" class="px-4 text-left font-light">
                                Informasi Dosen
                            </th>
                            <th scope="col" class="px-4 text-right font-light">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        if (empty($usersToShow)): // Jika tidak ada data yang ditampilkan
                        ?>
                            <tr>
                                <td colspan="8" class="py-4">
                                    <img src="assets/Belum_ada_data.png" alt="Belum ada data" class="mx-auto">
                                </td>
                            </tr>
                    <?php 
                    else:
                        // Initialize the overall row index
                        static $overallRowIndex = 0;
                        $visibleRowIndex = 0; // Counter for visible rows

                        // Loop through the users to show
                        foreach ($usersToShow as $x): 
                            $imagePath = $x['profile'];

                            // Increment the overall row index
                            $overallRowIndex++;
                            
                            // Check if the row should be displayed
                            $isVisible = true; // This should be dynamically set based on the search in JavaScript
                            
                            if ($isVisible) {
                                // Increment the visible row index if the row is visible
                                $visibleRowIndex++;
                            }
                    ?>
                    <tr class="<?php echo $rowClass; ?> border-b" style="<?php echo $isVisible ? '' : 'display: none;'; ?>">
                        <th class="text-center w-10">
                            <?php echo $no++;?>
                        </th>
                        <td class="w-10 h-10 px-0.5 py-0.5 text-center align-middle">
                            <img alt="Profile Image" class="w-10 rounded-full mx-auto" src="<?php echo isset($imagePath) && !empty($imagePath) ? $imagePath : "./assets/user.png"; ?>">
                        </td>
                        <td class="px-4 py-2" style="height: 3rem;">
                            <span class="text-base font-semibold text-blue-950">
                                <?php echo $x['nama']; ?>
                            </span>
                            <br>
                            <span class="text-sm text-gray-800 font-medium">
                                <?php echo 'NIP: ', $x['nomor_induk']; ?>
                            </span>
                            <br>
                            <span class="text-sm text-gray-600">
                                <?php echo $x['nomor_telepon']; ?>
                            </span>
                        </td>
                        <td class="py-2 px-6 text-right">
                            <button 
                                class="text-blue-500 hover:underline"
                                onclick="openEditPopup('<?php echo $x['id_user']; ?>', '<?php echo addslashes($x['nama']); ?>', '<?php echo $x['nomor_induk']; ?>', '<?php echo $x['nomor_telepon']; ?>', '<?php echo addslashes($x['email']); ?>', '<?php echo addslashes($x['password']); ?>')">
                                Detail
                            </button>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 
                        // Handle remaining rows
                        $remainingRows = $itemsPerPage - count($usersToShow);
                        for ($i = 0; $i < $remainingRows; $i++): 
                            // Increment overall row index for empty rows
                            $overallRowIndex++;
                            $rowClass = $overallRowIndex % 2 == 0 ? 'bg-white' : 'bg-gray-100'; // Maintain color pattern
                    ?>
                    <?php 
                        endfor;
                    endif; 
                    ?>
                    </tbody>
                </table>
                <!-- Popup Form untuk Edit User -->
                <div id="editPopup" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white p-6 rounded-lg w-96">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold">Edit User</h2>
                            <button onclick="closePopup()" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex items-center">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <form id="editForm" action="./Back-end/edit_dosen.php" method="POST">
                            <input type="hidden" name="id_user" id="editUserId">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" name="nama" id="editNama" class="w-full border px-3 py-2 rounded" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nomor Induk Pegawai</label>
                                <input type="text" name="nomor_induk" id="editNomorInduk" class="w-full border px-3 py-2 rounded" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                                <input type="text" name="nomor_telepon" id="editNomorTelepon" class="w-full border px-3 py-2 rounded" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="editEmail" name="email" class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" id="editPassword" class="w-full border px-3 py-2 rounded bg-gray-100" readonly>
                            </div>

                            <!-- Checkbox Reset Password -->
                            <div class="mb-4 flex items-center">
                                <input type="checkbox" name="reset_password" id="resetPasswordCheckbox" class="mr-2">
                                <label for="resetPasswordCheckbox" class="text-sm font-medium text-gray-700">Reset Password to Default</label>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" name="delete" value="1" class="bg-red-500 text-white px-4 py-2 rounded mr-2 hover:bg-red-600">Delete</button>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                            </div>
                        </form>
                    </div>
                </div>            
            </div>
            <!-- Tampilan navigasi Pagination -->
            <div class="flex justify-between">
                <p class="p-2 text-gray-500 text-sm">
                    Σ Jumlah: <?php echo $totalDosen; ?> Dosen
                </p>
                <nav aria-label="Page navigation example" class="flex justify-end mt-3">
                    <ul class="inline-flex -space-x-px text-sm">
                        <li>
                            <a href="?page=<?php echo max(1, $currentPage - 1); ?>" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li>
                                <a href="?page=<?php echo $i; ?>" class="flex items-center justify-center px-3 h-8 leading-tight <?php echo $i === $currentPage ? 'text-blue-600 border border-gray-300 bg-blue-50' : 'text-gray-500 bg-white border-gray-300'; ?> hover:bg-gray-100 hover:text-gray-700"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li>
                            <a href="?page=<?php echo min($totalPages, $currentPage + 1); ?>" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            </div>
        </div> 
        <!-- DIV CONTENT -->
        <script>
            
            // Fungsi untuk menampilkan popup dan mengisi data yang benar
            function openEditPopup(userId, nama, nomorInduk, nomorTelepon, email, password, id_instansi) {
                // Set each input field with the corresponding value
                document.getElementById('editUserId').value = userId;
                document.getElementById('editNama').value = nama;
                document.getElementById('editNomorInduk').value = nomorInduk;
                document.getElementById('editNomorTelepon').value = nomorTelepon;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPassword').value = password;

                // Display the popup
                document.getElementById('editPopup').classList.remove('hidden');
            }

            function closePopup() {
                document.getElementById('editPopup').classList.add('hidden');
            }

            // Function to toggle popup and disable/enable search
            function togglePopup() {
                var popup = document.getElementById("popup");
                var searchInput = document.getElementById("default-search");
                
                popup.classList.toggle("hidden");
                
                // Toggle disabled attribute on search input
                if (popup.classList.contains("hidden")) {
                    searchInput.removeAttribute("disabled");
                } else {
                    searchInput.setAttribute("disabled", "true");
                }
            }

            function searchTable() {
                const searchInput = document.getElementById('default-search').value.toLowerCase();
                const tableBody = document.querySelector('table tbody');

                // Jika input pencarian kosong, tampilkan data asli dari halaman saat ini
                if (searchInput.trim() === '') {
                    // Ambil data asli yang sesuai dengan halaman saat ini
                    tableBody.innerHTML = '';
                    currentPageData.forEach((user, index) => {
                        const row = createTableRow(user, index);
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                    return;
                }

                // Kosongkan tabel sebelum menampilkan data baru
                tableBody.innerHTML = '';

                // Lakukan pencarian di semua data
                let visibleRowIndex = 0;
                allUsers.forEach((user, index) => {
                    const userString = `${user.nama} ${user.nomor_induk} ${user.nomor_telepon}`.toLowerCase();
                    if (userString.includes(searchInput)) {
                        const row = createTableRow(user, visibleRowIndex);
                        tableBody.insertAdjacentHTML('beforeend', row);
                        visibleRowIndex++;
                    }
                });
            }

            // Menyimpan data asli dari halaman saat ini
            const currentPageData = <?php echo json_encode($usersToShow); ?>;

            // Mencegah di enter pada search
            document.getElementById('search-form').addEventListener('submit', function(event) {
                event.preventDefault(); // Mencegah submit form saat Enter ditekan
            });

            // Fungsi untuk membuat baris tabel
            function createTableRow(user, index) {
                return `
                    <tr class="border-b">
                        <th class="text-center w-10">${index + 1}</th>
                        <td class="w-10 h-10 px-0.5 py-0.5 text-center align-middle">
                            <img alt="Profile Image" class="w-10 rounded-full mx-auto" src="../Wicara_User_Web/backend/profile/${user.profile || 'user.png'}">
                        </td>
                        <td class="px-4 py-2" style="height: 3rem;">
                            <span class="text-base font-semibold text-blue-950">${user.nama}</span><br>
                            <span class="text-sm text-gray-800 font-medium">NIM: ${user.nomor_induk}</span><br>
                            <span class="text-sm text-gray-600">${user.nomor_telepon}</span>
                        </td>
                        <td class="py-2 px-6 text-right">
                            <button 
                                class="text-blue-500 hover:underline"
                                onclick="openEditPopup('${user.id_user}', '${user.nama}', '${user.nomor_induk}', '${user.nomor_telepon}', '${user.email}', '${user.password}')">
                                Detail
                            </button>
                        </td>
                    </tr>
                `;
            }

     
            function applyRowAlternatingColors() {
                 let table = document.getElementById('student-table');
                 let rows = table.getElementsByTagName('tr');
                 let visibleRowIndex = 0;
     
                 for (let i = 0; i < rows.length; i++) {
                     let row = rows[i];
                     // Bersihkan kelas lama
                     row.classList.remove('even', 'odd');
                     
                     // Terapkan kelas 'odd' atau 'even' berdasarkan indeks baris yang terlihat
                     if (visibleRowIndex % 2 === 0) {
                         row.classList.add('even');
                     } else {
                         row.classList.add('odd');
                     }
                     visibleRowIndex++;  // Tingkatkan indeks hanya untuk baris yang terlihat
                 }
             }
     
             // Panggil fungsi ini ketika halaman dimuat
             window.onload = function() {
                 applyRowAlternatingColors();
             }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
        <!-- INII SCRIPT NOTIF -->
        <script src="https://unpkg.com/flowbite@1.4.7/dist/flowbite.min.js"></script>
        
        <script>
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