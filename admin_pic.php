<?php
    require_once('t_function_pic.php');
    // buat update profile

    if (!isset($_SESSION['id_instansi'])) {
        header("Location: ../Wicara_User_Web/index.php"); // Jika belum login, redirect ke halaman login
    }

    $id_user = $_SESSION['id_instansi'];
    $user_data = mysqli_query($db->koneksi, "SELECT * FROM instansi WHERE id_instansi = '$id_user'");
    $user = mysqli_fetch_assoc($user_data);
    $user_image = $user['gambar_instansi'] ? "../Wicara_User_Web/assets/images/instansi/".$user['gambar_instansi'] : 'assets/laptop.jpg';

    // Get the selected status from the query parameter, default to 'semua'
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : 'semua';

    // Get the current page number, default to 1 if not set
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $itemsPerPage = 10;
    $offset = ($currentPage - 1) * $itemsPerPage;

    $dataPengaduan = $db->tampil_data_pengaduan_filtered($id_user);

    // Filter the data based on the selected status
    $filteredKejadian = array_filter($dataPengaduan, function($x) use ($statusFilter) {
        if ($statusFilter === 'semua') {
            return in_array(strtolower($x['nama_status_pengaduan']), ['diproses', 'selesai']);
        }
        // Filter by the specific status
        return strtolower($x['nama_status_pengaduan']) === strtolower($statusFilter);
    });


    // Calculate the total number of items and pages based on filtered data
    $totalKejadian = count($filteredKejadian);
    $totalPages = ceil($totalKejadian / $itemsPerPage);

    // Fetch the items for the current page
    $KejadianToShow = array_slice($filteredKejadian, $offset, $itemsPerPage);

    // Start numbering from the current offset
    $no = $offset + 1;

    $allUsersQuery = "SELECT 
            a.*, 
            b.*, 
            c.*, 
            d.*, 
            e.*, 
            f.*, 
            g.* 
        FROM kejadian a
        LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
        LEFT JOIN jenis_pengaduan b ON b.id_jenis_pengaduan = a.id_jenis_pengaduan
        INNER JOIN user c ON c.id_user = a.id_user
        LEFT JOIN instansi d ON d.id_instansi = a.id_instansi
        LEFT JOIN status_kehilangan e ON e.id_status_kehilangan = a.status_kehilangan
        LEFT JOIN status_pengaduan f ON f.id_status_pengaduan = a.status_pengaduan
        WHERE a.id_jenis_kejadian = 2 AND a.id_instansi = '$id_user'
        ORDER BY a.tanggal DESC";
    $allUsersResult = mysqli_query($db->koneksi, $allUsersQuery);
    $allUsers = mysqli_fetch_all($allUsersResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pengaduan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css"  rel="stylesheet"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="t_style_notif.css">
        <script src="t_skrip_notif_pic.js" defer></script>
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
                        <a href="#" class="flex items-center p-2 text-gray-50 rounded-lg bg-blue-950 group">
                            <div class="rounded-full p-2 bg-yellow-400">
                                <svg class="w-6 h-6 text-gray-50" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M8 7V2.221a2 2 0 0 0-.5.365L3.586 6.5a2 2 0 0 0-.365.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.126a5.087 5.087 0 0 0-4.74 1.368v.001l-6.642 6.642a3 3 0 0 0-.82 1.532l-.74 3.692a3 3 0 0 0 3.53 3.53l3.694-.738a3 3 0 0 0 1.532-.82L19 15.149V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/>
                                    <path fill-rule="evenodd" d="M17.447 8.08a1.087 1.087 0 0 1 1.187.238l.002.001a1.088 1.088 0 0 1 0 1.539l-.377.377-1.54-1.542.373-.374.002-.001c.1-.102.22-.182.353-.237Zm-2.143 2.027-4.644 4.644-.385 1.924 1.925-.385 4.644-4.642-1.54-1.54Zm2.56-4.11a3.087 3.087 0 0 0-2.187.909l-6.645 6.645a1 1 0 0 0-.274.51l-.739 3.693a1 1 0 0 0 1.177 1.176l3.693-.738a1 1 0 0 0 .51-.274l6.65-6.646a3.088 3.088 0 0 0-2.185-5.275Z" clip-rule="evenodd"/>
                                </svg>  
                            </div>
                            <span class="ms-3 text-gray-50">Pengaduan</span>
                        </a>
                    </li>
                    <li>
                        <a href="rating_pic.php?id=<?= $id_user;?>" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
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
                        <a href="staff_instansi.php" class="flex items-center p-2 text-gray-50 rounded-lg hover:bg-blue-900 group">
                            <svg class="w-6 h-6 text-gray-50 transition duration-75 group-hover:text-yellow-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                            </svg>   
                            <span class="ms-3 group-hover:text-yellow-400">Staff</span>
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
                        <span class="hidden font-semibold text-xl text-[#060A47] sm:inline-block">Pengaduan</span>
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
                                <span class="block text-sm font-semibold text-gray-900 "><?php echo $user['nama_instansi'];?></span>
                                <span class="block text-sm text-gray-500 truncate "><?php echo $user['email_pic'];?></span>
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
                        <label for="name" class="block mb-2 text-sm font-bold text-gray-900">Nama Instansi</label>
                        <input type="text" name="name" id="name" class="text-black bg-transparent border-none focus:ring-0 p-0" value="<?php echo $user['nama_instansi']; ?>" readonly />
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-bold text-gray-900">Email</label>
                        <input type="email" name="email" id="email" class="text-black bg-transparent border-none focus:ring-0 p-0" value="<?php echo $user['email_pic']; ?>" readonly />
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

                <form action="./Back-end/update_instansi.php" method="POST" enctype="multipart/form-data" class="space-y-4 flex flex-col justify-between h-full">
                    <div class="flex flex-col lg:items-center mb-4">
                        <img id="profile-preview" class="w-32 h-32 rounded-full object-cover" src="<?php echo $user_image; ?>" alt="Foto Profil">
                    </div>
                    <div>
                        <input type="hidden" name="id_instansi" id="admin-id" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['id_instansi']; ?>"  required />
                    </div>
                    <div>
                        <label for="name" class="block mb-2 text-sm font-bold text-gray-900 ">Nama Instansi</label>
                        <input type="text" name="nama_instansi" id="name" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['nama_instansi']; ?>" required />
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-bold text-gray-900">Email</label>
                        <input type="email" name="email_pic" id="email" class="bg-gray-50 border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" value="<?php echo $user['email_pic']; ?>" required />
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
                        <input name="image_instansi" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none" id="image" type="file" accept="image/jpeg, image/png, image/jpg" onchange="previewImage(event)">
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
                                <a href="?status=diproses" class="status-tab inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" data-status="diproses">Diproses</a>
                            </li>
                            <li class="me-2">
                                <a href="?status=selesai" class="status-tab inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" data-status="selesai">Selesai</a>
                            </li>
                            <form id="search-form" class="flex-grow mx-auto">
                                <div class="relative top-2">
                                    <div class="absolute top-2.5 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                        </svg>
                                    </div>
                                    <input type="search" id="default-search" class="block w-full px-4 py-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Search Anything" required onkeyup="searchTable()"/>
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
                                ID Aduan
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Judul Aduan
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Tanggal
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Jenis Pengaduan
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Deskripsi
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-2 font-light">
                                <span class="sr-only">Edit</span>
                            </th>
                        </tr>
                    </thead>
                    <!-- INII ISI KOLOM -->
                    <tbody class="text-center">
                        <?php 
                        if (empty($KejadianToShow)):
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
                            foreach ($KejadianToShow as $x): 
                                // Increment the overall row index
                                $overallRowIndex++;
                                
                                // Check if the row should be displayed
                                $isVisible = true; // This should be dynamically set based on the search in JavaScript
                                
                                if ($isVisible) {
                                    // Increment the visible row index if the row is visible
                                    $visibleRowIndex++;
                                }
                        ?>
                            <tr class="bg-white border-b" style="<?php echo $isVisible ? '' : 'display: none;'; ?>" data-status="<?php echo strtolower($x['nama_status_pengaduan']); ?>">
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
                                    <?php echo $x['nama_jenis_pengaduan']; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo substr($x['deskripsi'], 0, 30) . '...'; ?>
                                </td>
                                <td class="">
                                    <?php
                                        if($x['nama_status_pengaduan'] == "Diajukan"){
                                            echo '<span class="bg-gray-100 text-gray-500 text-xs font-medium px-3 py-1 rounded">Diajukan</span>';
                                        }elseif($x['nama_status_pengaduan'] == "Diproses"){
                                            echo '<span class="bg-yellow-100 text-yellow-400 text-xs font-medium px-3 py-1 rounded">Diproses</span>';
                                        }elseif($x['nama_status_pengaduan'] == "Selesai"){
                                            echo '<span class="bg-green-200 text-green-600 text-xs font-medium px-3 py-1 rounded">Selesai</span>';
                                        }elseif($x['nama_status_pengaduan'] == "Ditolak"){
                                            echo '<span class="bg-red-200 text-red-600 text-xs font-medium px-3 py-1 rounded">Ditolak</span>';
                                        }elseif($x['nama_status_pengaduan'] == "Dibatalkan"){
                                            echo '<span class="bg-pink-100 text-pink-500 text-xs font-medium px-3 py-1 rounded">Dibatalkan</span>';
                                        }
                                    ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button id="updateProductButton" class="inline text-blue-600 hover:underline font-medium text-sm" type="button" 
                                        onclick="openEditPopup('<?php echo $x['id_kejadian']; ?>', '<?php echo $x['nama']; ?>', '<?php echo $x['judul']; ?>', '<?php echo $x['nama_jenis_pengaduan']; ?>', '<?php echo $x['tanggal']; ?>', '<?php echo $x['nama_status_pengaduan']; ?>', '<?php echo $x['lokasi']; ?>', '<?php echo $x['lampiran']; ?>', '<?php echo $x['deskripsi']; ?>', '<?php echo $x['nama_instansi']; ?>')">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        <?php 
                            endforeach; 
                            // Handle remaining rows
                            $remainingRows = $itemsPerPage - count($KejadianToShow);
                            for ($i = 0; $i < $remainingRows; $i++): 
                                // Increment overall row index for empty rows
                                $overallRowIndex++;
                        ?>
                        <?php 
                            endfor;
                        endif; 
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
        <div id="updateProductModal" class="hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:min-h-screen">
            <div class="relative p-4 mx-4 w-full max-w-2xl h-auto md:min-h-screen">
                <!-- Modal content -->
                <div class="relative p-4 bg-white rounded-lg overflow-y-auto max-h-screen shadow sm:p-5">
                    <!-- Modal header -->
                    <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Detail Pengaduan
                        </h3>
                        <button onclick="closePopup()" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" >
                            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form action="admin_pic.php" method="POST">
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">
                            <div>
                                <label for="id_kejadian" class="block mb-2 text-sm font-medium text-gray-900">ID Aduan</label>
                                <input type="text" name="id_kejadian" id="id_kejadian" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="user" class="block mb-2 text-sm font-medium text-gray-900">User</label>
                                <input type="text" name="user" id="user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="judul" class="block mb-2 text-sm font-medium text-gray-900">Judul Aduan</label>
                                <input type="text" name="judul" id="judul" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
                            </div>
                            <div>
                                <label for="kategori" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                                <input type="text" name="kategori" id="kategori" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
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
                                <label for="instansi" class="block mb-2 text-sm font-medium text-gray-900">Instansi</label>
                                <input type="text" name="instansi" id="instansi" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" placeholder="-" readonly>
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
                                Selesai
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
                const allUsers = <?php echo json_encode($allUsers); ?>;

                function openEditPopup(id_kejadian, nama, judul, nama_jenis_pengaduan, tanggal, nama_status_pengaduan, lokasi, lampiran, deskripsi, nama_instansi) {
                    // Set each input field with the corresponding value
                    document.querySelector('#updateProductModal input[name="id_kejadian"]').value = id_kejadian;
                    document.querySelector('#updateProductModal input[name="user"]').value = nama;
                    document.querySelector('#updateProductModal input[name="judul"]').value = judul;
                    document.querySelector('#updateProductModal input[name="kategori"]').value = nama_jenis_pengaduan;
                    document.querySelector('#updateProductModal input[name="tanggal"]').value = tanggal;
                    document.querySelector('#updateProductModal input[name="status"]').value = nama_status_pengaduan;
                    document.querySelector('#updateProductModal input[name="lokasi"]').value = lokasi;
                    document.querySelector('#updateProductModal input[name="instansi"]').value = nama_instansi;
                    const deskripsiField = document.querySelector('#updateProductModal textarea[name="deskripsi"]');
                    if (deskripsiField) deskripsiField.value = deskripsi;

                    const lampiranField = document.querySelector('#updateProductModal img[id="lampiran"]');
                    if (lampiranField) {
                        lampiranField.src = lampiran ? `../Wicara_User_Web/backend/aduan/${lampiran}` : "./assets/default-image.png";
                    }

                    // Show or hide buttons based on the status
                    const acceptButton = document.getElementById('acceptButton');
                    const deleteButton = document.getElementById('deleteButton');

                    if (nama_status_pengaduan === 'Diproses') {
                        // Show "Terima" and "Tolak" buttons, hide "Delete"
                        acceptButton.classList.remove('hidden');
                        deleteButton.classList.add('hidden');
                    } else {
                        // Show "Delete" button, hide "Terima" and "Tolak"
                        acceptButton.classList.add('hidden');
                        deleteButton.classList.remove('hidden');
                    }

                    // Display the popup
                    document.getElementById('updateProductModal').classList.remove('hidden');
                    document.getElementById('updateProductModal').classList.add('flex');

                    
                    // Mengambil id kejadian dari modal
                    const getIdKejadian = () => document.querySelector('#updateProductModal input[name="id_kejadian"]').value;

                    // Fungsi AJAX untuk mengirim data
                    function sendAction(action) {
                        const idKejadian = getIdKejadian();
                        fetch('Back-end/update_status_pengaduan.php', {
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
                                setTimeout(() => location.reload(), 500);
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }

                    // Event listeners untuk tombol aksi
                    acceptButton.addEventListener('click', () => sendAction('selesai'));
                    deleteButton.addEventListener('click', () => {
                        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                            sendAction('delete');
                        }
                    });
                }

                function closePopup() {
                    document.getElementById('updateProductModal').classList.add('hidden');
                    document.getElementById('updateProductModal').classList.remove('flex');
                }


                // Fungsi untuk mencari data di tabel
                function searchTable() {
                    const searchInput = document.getElementById('default-search').value.toLowerCase();
                    const tableBody = document.querySelector('table tbody');

                    // Jika input pencarian kosong, tampilkan data asli dari halaman saat ini
                    if (searchInput.trim() === '') {
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
                        const userString = `${user.id_kejadian} ${user.judul} ${user.tanggal} ${user.nama_jenis_pengaduan} ${user.deskripsi} ${user.nama_status_pengaduan}`.toLowerCase();
                        if (userString.includes(searchInput)) {
                            const row = createTableRow(user, visibleRowIndex);
                            tableBody.insertAdjacentHTML('beforeend', row);
                            visibleRowIndex++;
                        }
                    });
                }

                // Simpan data asli dari halaman saat ini
                const currentPageData = <?php echo json_encode($KejadianToShow); ?>;

                // Mencegah form submit dengan Enter
                document.getElementById('search-form').addEventListener('submit', function(event) {
                    event.preventDefault();
                });

                // Fungsi untuk membuat baris tabel
                function createTableRow(user, index) {
                    // Fungsi untuk menghindari error saat mengakses properti yang null atau undefined
                    function safeToLowerCase(value) {
                        return value ? value.toLowerCase() : '';
                    }

                    // Default value for status
                    const statusClass = {
                        "Diajukan": "bg-gray-100 text-gray-500",
                        "Diproses": "bg-yellow-100 text-yellow-400",
                        "Selesai": "bg-green-200 text-green-600",
                        "Ditolak": "bg-red-200 text-red-600",
                        "Dibatalkan": "bg-pink-100 text-pink-500"
                    };

                    const statusColorClass = statusClass[user.nama_status_pengaduan] || "bg-gray-100 text-gray-500";

                    return `
                        <tr class="bg-white border-b">
                            <th scope="row" class="px-3 py-4">${index + 1}</th>
                            <td class="px-6 py-4">${user.id_kejadian || ''}</td>
                            <td class="px-6 py-4">${user.judul || ''}</td>
                            <td class="px-6 py-4">${user.tanggal || ''}</td>
                            <td class="px-6 py-4 text-center">${user.nama_jenis_pengaduan || ''}</td>
                            <td class="px-6 py-4">${user.deskripsi ? user.deskripsi.substring(0, 30) + '...' : ''}</td>
                            <td class="">
                                <span class="${statusColorClass} text-xs font-medium px-3 py-1 rounded">${user.nama_status_pengaduan || ''}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button 
                                    id="updateProductButton" 
                                    class="inline text-blue-600 hover:underline font-medium text-sm" 
                                    type="button"
                                    onclick="openEditPopup('${user.id_kejadian || ''}', '${user.nama || ''}', '${user.judul || ''}', '${user.nama_jenis_pengaduan || ''}', '${user.tanggal || ''}', '${user.nama_status_pengaduan || ''}', '${user.lokasi || ''}', '${user.lampiran || ''}', '${user.deskripsi || ''}', '${user.nama_instansi || ''}')">
                                    Detail
                                </button>
                            </td>
                        </tr>
                    `;
                }

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

                        // Initialize a flag to track if there is visible data
                        let hasVisibleData = false;

                        // Show/hide rows based on the selected status
                        rows.forEach(row => {
                            const rowStatus = row.getAttribute('data-status');
                            if (status === 'semua' || rowStatus === status) {
                                row.style.display = '';
                                hasVisibleData = true;
                            } else {
                                row.style.display = 'none';
                            }
                        });

                        // Handle the case when no data is visible
                        const emptyMessageRow = document.querySelector('#empty-message-row');

                        if (!hasVisibleData) {
                            if (!emptyMessageRow) {
                                // Create a new row for the empty message if it doesn't exist
                                const tbody = document.querySelector('tbody');
                                const tr = document.createElement('tr');
                                tr.id = 'empty-message-row';
                                tr.innerHTML = `
                                    <td colspan="8" class="py-4">
                                        <img src="assets/Belum_ada_data.png" alt="Belum ada data" class="mx-auto">
                                    </td>
                                `;
                                tbody.appendChild(tr);
                            } else {
                                // Show the existing empty message row
                                emptyMessageRow.style.display = '';
                            }
                        } else {
                            // Hide the empty message row if data is available
                            if (emptyMessageRow) {
                                emptyMessageRow.style.display = 'none';
                            }
                        }

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