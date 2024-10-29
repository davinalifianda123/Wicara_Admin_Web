<?php
    class database{
        var $host = "localhost:3307";
        var $username = "root";
        var $password = "";
        var $database = "wicara";
        var $koneksi;
        function __construct(){
            $this->koneksi = mysqli_connect($this->host, $this->username, $this->password, $this->database);
            if (mysqli_connect_errno()){
                echo "Koneksi database gagal : " . mysqli_connect_error();
            }
        }
        
        function tampil_data()
        {
            $data = mysqli_query($this->koneksi, "select a.*,b.*,c.*,d.*,e.*,f.*,g.* from kejadian a
                                                                LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
                                                                LEFT JOIN jenis_pengaduan b ON b.id_jenis_pengaduan = a.id_jenis_pengaduan
                                                                INNER JOIN user c ON c.id_user = a.id_user
                                                                LEFT JOIN instansi d ON d.id_instansi = a.id_instansi
                                                                LEFT JOIN status_kehilangan e ON e.id_status_kehilangan = a.status_kehilangan
                                                                LEFT JOIN status_pengaduan f ON f.id_status_pengaduan = a.status_pengaduan");
            while ($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
            
        }

        function tampil_data_kehilangan()
        {
            $data = mysqli_query($this->koneksi, "select a.*,b.*,c.*,d.*,e.*,f.*,g.* from kejadian a
                                                                LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
                                                                LEFT JOIN jenis_pengaduan b ON b.id_jenis_pengaduan = a.id_jenis_pengaduan
                                                                INNER JOIN user c ON c.id_user = a.id_user
                                                                LEFT JOIN instansi d ON d.id_instansi = a.id_instansi
                                                                LEFT JOIN status_kehilangan e ON e.id_status_kehilangan = a.status_kehilangan
                                                                LEFT JOIN status_pengaduan f ON f.id_status_pengaduan = a.status_pengaduan
                                                                WHERE a.id_jenis_kejadian = 1");
            while ($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
            
        }

        function tampil_data_pengaduan()
        {
            $data = mysqli_query($this->koneksi, "select a.*,b.*,c.*,d.*,e.*,f.*,g.* from kejadian a
                                                                LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
                                                                LEFT JOIN jenis_pengaduan b ON b.id_jenis_pengaduan = a.id_jenis_pengaduan
                                                                INNER JOIN user c ON c.id_user = a.id_user
                                                                LEFT JOIN instansi d ON d.id_instansi = a.id_instansi
                                                                LEFT JOIN status_kehilangan e ON e.id_status_kehilangan = a.status_kehilangan
                                                                LEFT JOIN status_pengaduan f ON f.id_status_pengaduan = a.status_pengaduan
                                                                WHERE a.id_jenis_kejadian = 2");
            while ($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
            
        }

        function tampil_data_ulasan()
        {
            $data = mysqli_query($this->koneksi, "select a.*,b.*,c.*,d.*,e.*,f.*,g.* from kejadian a
                                                                LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
                                                                LEFT JOIN jenis_pengaduan b ON b.id_jenis_pengaduan = a.id_jenis_pengaduan
                                                                INNER JOIN user c ON c.id_user = a.id_user
                                                                LEFT JOIN instansi d ON d.id_instansi = a.id_instansi
                                                                LEFT JOIN status_kehilangan e ON e.id_status_kehilangan = a.status_kehilangan
                                                                LEFT JOIN status_pengaduan f ON f.id_status_pengaduan = a.status_pengaduan
                                                                WHERE a.id_jenis_kejadian = 3");
            while ($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
            
        }

        function tampil_jenis_pengaduan()
        {
            $data = mysqli_query($this->koneksi, "select * from jenis_pengaduan");
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }

        function tampil_instansi()
        {
            $data = mysqli_query($this->koneksi, "select * from instansi");
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }

        function tampil_jenis_kejadian()
        {
            $data = mysqli_query($this->koneksi, "select * from jenis_kejadian");
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }

        function tampil_audit_log()
        {
            $data = mysqli_query($this->koneksi, "select * from audit_log");
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }

        function tampil_role()
        {
            $data = mysqli_query($this->koneksi, "select * from role");
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }

        function tampil_status_kehilangan()
        {
            $data = mysqli_query($this->koneksi, "select * from status_kehilangan");
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }

        function tampil_user()
        {
            $data = mysqli_query($this->koneksi, "select a.*,b.* from user a INNER JOIN role b ON b.id_role = a.role");
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }

        function tampil_status_pengaduan()
        {
            $data = mysqli_query($this->koneksi, "select * from status_pengaduan");
            while($row = mysqli_fetch_array($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }
        
//FORM KEHILANGAN
        function tambah_kejadian_kehilangan($id_jenis_kejadian,$id_user, $jenis_barang, $deskripsi, $tanggal, $lokasi,  $status_kehilangan, $tanggal_kadaluwarsa)
        {
            mysqli_query($this->koneksi,"INSERT INTO kejadian (id_jenis_kejadian, id_user, jenis_barang, deskripsi, tanggal, lokasi,status_kehilangan, tanggal_kadaluwarsa) 
            VALUES ('$id_jenis_kejadian','$id_user', '$jenis_barang', '$deskripsi', '$tanggal', '$lokasi', '$status_kehilangan', '$tanggal_kadaluwarsa')");
        }
//FORM PENGADUAN
        function tambah_kejadian_pengaduan($id_jenis_kejadian, $id_user, $judul, $deskripsi, $tanggal, $lokasi, $lampiran, $id_jenis_pengaduan, $status_pengaduan, $id_instansi) {
            $query = "INSERT INTO kejadian (id_jenis_kejadian, id_user, judul, deskripsi, tanggal, lokasi, lampiran, id_jenis_pengaduan, status_pengaduan, id_instansi) 
                    VALUES ('$id_jenis_kejadian', '$id_user', '$judul', '$deskripsi', '$tanggal', '$lokasi', '$lampiran', '$id_jenis_pengaduan', '$status_pengaduan', '$id_instansi')";
            mysqli_query($this->koneksi, $query);
        }

//FORM ULASAN
        function tambah_kejadian_ulasan($id_jenis_kejadian,$id_user, $id_instansi, $isi_komentar, $tanggal, $skala_bintang)
        { 
            mysqli_query($this->koneksi,"INSERT INTO kejadian (id_jenis_kejadian, id_user, id_instansi,isi_komentar, tanggal, skala_bintang) 
            VALUES ('$id_jenis_kejadian','$id_user', '$id_instansi', '$isi_komentar', '$tanggal', '$skala_bintang')");
        }
//FORM USER
        function tambah_user($nama, $nomor_induk, $nomor_telepon, $email, $password, $role, $image)
        { 
            mysqli_query($this->koneksi,"INSERT INTO user (nama, nomor_induk, nomor_telepon, email, password, role, image) 
            VALUES ('$nama', '$nomor_induk', '$nomor_telepon', '$email', '$password', '$role', '$image')");
        }
        function edit_user_with_image($id_user, $nama, $nomor_induk, $nomor_telepon, $email, $password, $role, $image_path) {
            if ($image_path) {
                // Jika ada gambar baru yang diunggah, perbarui juga kolom 'image'
                $stmt = $this->koneksi->prepare("UPDATE user SET nama=?, nomor_induk=?, nomor_telepon=?, email=?, password=?, role=?, image=? WHERE id_user=?");
                $stmt->bind_param("sssssssi", $nama, $nomor_induk, $nomor_telepon, $email, $password, $role, $image_path, $id_user);
            } else {
                // Jika tidak ada gambar baru, update data kecuali kolom 'image'
                $stmt = $this->koneksi->prepare("UPDATE user SET nama=?, nomor_induk=?, nomor_telepon=?, email=?, password=?, role=? WHERE id_user=?");
                $stmt->bind_param("ssssssi", $nama, $nomor_induk, $nomor_telepon, $email, $password, $role, $id_user);
            }
        
            // Eksekusi query
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        
            // Tutup statement
            $stmt->close();
        }
        public function get_user_image($id_user) {
            $query = "SELECT image FROM user WHERE id_user = ?";
            $stmt = $this->koneksi->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
    
            return $user ? $user['image'] : null;
        }
        
        

//EDIT KEHILANGAN, PENGADUAN DAN ULASAN
        function kode_kejadian($id_kejadian){
            $data = mysqli_query($this->koneksi,"select a.*,b.*,c.*,d.*,e.*,f.*,g.* from kejadian a
                                                                LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
                                                                LEFT JOIN jenis_pengaduan b ON b.id_jenis_pengaduan = a.id_jenis_pengaduan
                                                                INNER JOIN user c ON c.id_user = a.id_user
                                                                LEFT JOIN instansi d ON d.id_instansi = a.id_instansi
                                                                LEFT JOIN status_kehilangan e ON e.id_status_kehilangan = a.status_kehilangan
                                                                LEFT JOIN status_pengaduan f ON f.id_status_pengaduan = a.status_pengaduan
                                                                WHERE a.id_kejadian = '$id_kejadian'");
            while($row = mysqli_fetch_assoc($data)){
                $hasil[] = $row;
            }
            return $hasil;
        }
//EDIT KEHILANGAN
        function edit_kejadian_kehilangan($id_kejadian,$id_jenis_kejadian,$id_user, $jenis_barang, $deskripsi, $tanggal, $lokasi,  $status_kehilangan, $tanggal_kadaluwarsa)
        {
            mysqli_query($this->koneksi,"UPDATE kejadian set id_jenis_kejadian = '$id_jenis_kejadian', id_user = '$id_user', jenis_barang = '$jenis_barang', 
                                                        deskripsi = '$deskripsi', tanggal = '$tanggal', lokasi = '$lokasi',status_kehilangan = '$status_kehilangan',
                                                        tanggal_kadaluwarsa = '$tanggal_kadaluwarsa' Where id_kejadian = $id_kejadian");
        }
        function edit_kejadian_pengaduan($id_kejadian,$id_jenis_kejadian,$id_user,$judul,$deskripsi, $tanggal, $lokasi, $lampiran ,$status_pengaduan, $id_jenis_pengaduan,$id_instansi)
        {
            mysqli_query($this->koneksi,"UPDATE kejadian set id_jenis_kejadian = '$id_jenis_kejadian', id_user = '$id_user', judul = '$judul',
                                                        deskripsi = '$deskripsi', tanggal = '$tanggal', lokasi = '$lokasi',lampiran = '$lampiran', status_pengaduan = '$status_pengaduan', id_jenis_pengaduan= '$id_jenis_pengaduan',
                                                        id_instansi = '$id_instansi' Where id_kejadian = $id_kejadian");
        }
        function edit_kejadian_ulasan($id_kejadian,$id_jenis_kejadian,$id_user, $id_instansi,$isi_komentar, $tanggal, $skala_bintang)
        {
            mysqli_query($this->koneksi,"UPDATE kejadian set id_jenis_kejadian = '$id_jenis_kejadian', id_user = '$id_user', id_instansi = '$id_instansi',
                                                        isi_komentar = '$isi_komentar', tanggal = '$tanggal', skala_bintang = '$skala_bintang' Where id_kejadian = $id_kejadian");
        }

        function hapus_data($id_kejadian)
        {
            mysqli_query($this->koneksi,"DELETE from kejadian where id_kejadian = '$id_kejadian'");
        }  
        
    }

?>