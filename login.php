<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <style>
      body {
          font-family: 'Poppins', sans-serif !important;
      }
    </style>
  </head>
  <body>
    <section class="w-dvw h-dvh">
      <div class="grid grid-cols-1 md:grid-cols-2 size-full bg-[rgb(249,249,249)]">
        <!-- Info -->
        <div class="bg-[#070D59] rounded-b-[50px] md:rounded-r-[50px] md:rounded-b-none relative overflow-clip">
          <img src="assets/Pattern_1.png" alt="" class="absolute size-full">
          <div class="pt-12 pl-12 size-full">
            <div class="bg-white w-44 h-12 rounded-full flex items-center justify-center">
              <img src="assets/Polines.png" alt="" class="h-7 w-auto">
            </div>
            <div class="text-white mt-12">
              <p class="text-4xl font-bold hidden md:block">WICARA</p>
              <p class="italic mt-2 hidden md:block">Wadah Informasi Catatan Aspirasi & Rating Akademik.</p>  
            </div>
            <img src="assets/Orang.png" alt="" class="absolute mt-0 md:mt-7 -pt-1 max-h-[150%] md:max-h-full object-scale-down w-auto top-28 md:top-48">
          </div> 
        </div>
        <!-- Form Login -->
        <div class="bg-[#F9F9F9] md:pt-12 pl-12 relative overflow-clip">
          <div class="mt-24">
            <p class="text-blue-950 text-4xl font-bold">LOGIN</p>
            <p class="text-black italic mt-2">Selamat Datang di Platform Aspirasi dan Rating Akademik!</p>  
          </div>       
            <form action="proses_login.php" method="post" class="w-[80%] mt-7">
              <div class="mb-5">
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-[#F7B633] focus:border-[#F7B633] block w-full h-12 p-2.5" placeholder="email@gmail.com" required />
              </div>
              <div class="mb-5 relative">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 ">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-full focus:ring-[#F7B633] focus:border-[#F7B633] block w-full h-12 p-2.5" placeholder="••••••••" />

                <!-- Tombol untuk menampilkan/menyembunyikan password -->
                <button type="button" id="togglePassword" class="absolute right-3 top-10 flex items-center">
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
              <div class="flex items-start mb-5">
                <div class="flex items-center h-5">
                  <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-[#070D59] text-[#070D59]" required />
                </div>
                <label for="remember" class="ms-2 text-sm font-medium text-gray-900">Remember me</label>
              </div>
              <button type="submit" class="text-white bg-[#F7B633] hover:bg-[#070D59] focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm w-full h-12 px-5 py-2.5 text-center">Login</button>
            </form>
        </div>
      </div>
    </section>

    <script>
      const passwordInput = document.getElementById('password');
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
    </script>
  </body>
</html>