<?php
$mysqli = new mysqli("localhost", "root", "", "wicara");

// Periksa koneksi
if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}
?>


<?php
    class database{
        var $host = "localhost:3306";
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

        function tampil_data_ulasan_by_id($idInstansi = null)
        {
            $query = "SELECT a.*, b.*, c.*, d.*, e.*, f.*, g.* 
                    FROM kejadian a
                    LEFT JOIN jenis_kejadian g ON g.id_jenis_kejadian = a.id_jenis_kejadian
                    LEFT JOIN jenis_pengaduan b ON b.id_jenis_pengaduan = a.id_jenis_pengaduan
                    INNER JOIN user c ON c.id_user = a.id_user
                    LEFT JOIN instansi d ON d.id_instansi = a.id_instansi
                    LEFT JOIN status_kehilangan e ON e.id_status_kehilangan = a.status_kehilangan
                    LEFT JOIN status_pengaduan f ON f.id_status_pengaduan = a.status_pengaduan
                    WHERE a.id_jenis_kejadian = 3";

            // Tambahkan filter id_instansi jika ada
            if ($idInstansi !== null) {
                $query .= " AND d.id_instansi = '$idInstansi'";
            }

            $data = mysqli_query($this->koneksi, $query);
            $hasil = [];
            while ($row = mysqli_fetch_array($data)) {
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

        function tampil_user_instansi()
        {
            $data = mysqli_query($this->koneksi, "SELECT a.*, b.*, c.* FROM user a 
                                                INNER JOIN role b ON b.id_role = a.role
                                                LEFT JOIN instansi c ON c.id_instansi = a.id_instansi"); // Ganti INNER JOIN dengan LEFT JOIN
            $hasil = [];
            while ($row = mysqli_fetch_array($data)) {
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

        function tampil_instansi_by_id($id_instansi)
        {
            $data = mysqli_query($this->koneksi, "SELECT * FROM instansi WHERE id_instansi = '$id_instansi'");
            $row = mysqli_fetch_array($data);
            return $row;
        }

        
//FORM KEHILANGAN
        function tambah_kejadian_kehilangan($id_jenis_kejadian,$id_user, $judul, $deskripsi, $tanggal, $lokasi, $lampiran, $jenis_barang, $status_kehilangan, $tanggal_kadaluwarsa, $status_notif)
        {
            $query = "INSERT INTO kejadian (id_jenis_kejadian, id_user, judul, deskripsi, tanggal, lokasi, lampiran, jenis_barang, status_kehilangan, tanggal_kadaluwarsa, status_notif) 
                VALUES ('$id_jenis_kejadian', '$id_user', '$judul', '$deskripsi', '$tanggal', '$lokasi', '$lampiran', '$jenis_barang', '$status_kehilangan', '$tanggal_kadaluwarsa', 'belum terbaca')";
            mysqli_query($this->koneksi, $query);
        }

        public function update_status_kehilangan($id_kejadian, $status) {
            $query = "UPDATE kejadian SET nama_status_kehilangan = '$status' WHERE id_kejadian = '$id_kejadian'";
            return mysqli_query($this->koneksi, $query);
        }

        public function hapus_kehilangan($id_kejadian) {
            $query = "DELETE FROM kejadian WHERE id_kejadian = '$id_kejadian'";
            return mysqli_query($this->koneksi, $query);
        }
//FORM PENGADUAN
        function tambah_kejadian_pengaduan($id_jenis_kejadian, $id_user, $judul, $deskripsi, $tanggal, $lokasi, $lampiran, $id_jenis_pengaduan, $status_pengaduan, $id_instansi) {
            $query = "INSERT INTO kejadian (id_jenis_kejadian, id_user, judul, deskripsi, tanggal, lokasi, lampiran, id_jenis_pengaduan, status_pengaduan, id_instansi, status_notif) 
                    VALUES ('$id_jenis_kejadian', '$id_user', '$judul', '$deskripsi', '$tanggal', '$lokasi', '$lampiran', '$id_jenis_pengaduan', '$status_pengaduan', '$id_instansi', 'belum terbaca')";
            mysqli_query($this->koneksi, $query);
        }

        public function update_status_pengaduan($id_kejadian, $status) {
            $query = "UPDATE kejadian SET nama_status_pengaduan = '$status' WHERE id_kejadian = '$id_kejadian'";
            return mysqli_query($this->koneksi, $query);
        }

        public function hapus_pengaduan($id_kejadian) {
            $query = "DELETE FROM kejadian WHERE id_kejadian = '$id_kejadian'";
            return mysqli_query($this->koneksi, $query);
        }
        

//FORM ULASAN
        function tambah_kejadian_ulasan($id_jenis_kejadian,$id_user, $id_instansi, $isi_komentar, $tanggal, $skala_bintang)
        { 
            mysqli_query($this->koneksi,"INSERT INTO kejadian (id_jenis_kejadian, id_user, id_instansi,isi_komentar, tanggal, skala_bintang, status_notif) 
            VALUES ('$id_jenis_kejadian','$id_user', '$id_instansi', '$isi_komentar', '$tanggal', '$skala_bintang', 'belum terbaca')");
        }

//FORM USER
        function tambah_user($nama, $nomor_induk, $nomor_telepon, $email, $password, $role, $image, $id_instansi)
        { 
            mysqli_query($this->koneksi,"INSERT INTO user (nama, nomor_induk, nomor_telepon, email, password, role, image, id_instansi) 
            VALUES ('$nama', '$nomor_induk', '$nomor_telepon', '$email', '$password', '$role', '$image', '$id_instansi')");
        }
        
        public function edit_user($id_user, $nama, $nomor_induk, $nomor_telepon) {
            $query = "UPDATE user SET nama = '$nama', nomor_induk = '$nomor_induk', nomor_telepon = '$nomor_telepon' WHERE id_user = " . intval($id_user);
            return mysqli_query($this->koneksi, $query);
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
        function edit_kejadian_kehilangan($id_kejadian, $id_jenis_kejadian, $id_user, $judul, $jenis_barang, $deskripsi, $tanggal, $lokasi, $lampiran,  $status_kehilangan, $tanggal_kadaluwarsa)
        {
            mysqli_query($this->koneksi,"UPDATE kejadian set id_jenis_kejadian = '$id_jenis_kejadian', id_user = '$id_user', judul = '$judul', jenis_barang = '$jenis_barang', 
                                                        deskripsi = '$deskripsi', tanggal = '$tanggal', lokasi = '$lokasi', lampiran = '$lampiran', status_kehilangan = '$status_kehilangan',
                                                        tanggal_kadaluwarsa = '$tanggal_kadaluwarsa' Where id_kejadian = $id_kejadian");
        }
        function edit_kejadian_pengaduan($id_kejadian,$id_jenis_kejadian,$id_user,$judul, $deskripsi, $tanggal, $lokasi, $lampiran ,$status_pengaduan, $id_jenis_pengaduan,$id_instansi)
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
        
        // CRUD untuk jenis_pengaduan
        function kode_pengaduan($id_jenis_pengaduan) {
            // Query untuk mengambil data dari tabel jenis_pengaduan
            $data = mysqli_query($this->koneksi, "SELECT id_jenis_pengaduan, nama_jenis_pengaduan 
                                                    FROM jenis_pengaduan 
                                                    WHERE id_jenis_pengaduan = '$id_jenis_pengaduan'");
            while ($row = mysqli_fetch_assoc($data)) {
                $hasil[] = $row;
            }
            return isset($hasil) ? $hasil : [];
        }

        //Fungsi Tambah jenis_pengaduan
        function tambah_jenis_pengaduan($nama_jenis_pengaduan) {
            $stmt = $this->koneksi->prepare("INSERT INTO jenis_pengaduan (nama_jenis_pengaduan) VALUES (?)");
            $stmt->bind_param("s", $nama_jenis_pengaduan);
            return $stmt->execute();
        }

        // Fungsi Edit jenis_pengaduan
        function edit_jenis_pengaduan($id_jenis_pengaduan, $nama_jenis_pengaduan) {
            $stmt = $this->koneksi->prepare("UPDATE jenis_pengaduan SET nama_jenis_pengaduan = ? WHERE id_jenis_pengaduan = ?");
            $stmt->bind_param("si", $nama_jenis_pengaduan, $id_jenis_pengaduan);
            return $stmt->execute();
        }

        // Fungsi Hapus jenis_pengaduan
        function hapus_jenis_pengaduan($id_jenis_pengaduan) {
            // Check if the category is in use in the pengaduan table
            $stmt = $this->koneksi->prepare("SELECT COUNT(*) FROM jenis_pengaduan WHERE id_jenis_pengaduan = ?");
            $stmt->bind_param("i", $id_jenis_pengaduan);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            
            if ($count > 0) {

                return false;
            }
        
            $stmt = $this->koneksi->prepare("DELETE FROM jenis_pengaduan WHERE id_jenis_pengaduan = ?");
            $stmt->bind_param("i", $id_jenis_pengaduan);
            return $stmt->execute();
        }
        

    // Instansi
        function edit_instansi_with_image($id_instansi, $nama_instansi, $email_pic, $password, $image_instansi) {
            if ($image_instansi) {
                // Jika ada gambar baru yang diunggah, perbarui juga kolom 'image'
                $stmt = $this->koneksi->prepare("UPDATE instansi SET nama_instansi=?, email_pic=?, password=?, image_instansi=? WHERE id_instansi=?");
                $stmt->bind_param("ssssi", $nama_instansi, $email_pic, $password, $image_instansi, $id_instansi);
            } else {
                // Jika tidak ada gambar baru, update data kecuali kolom 'image'
                $stmt = $this->koneksi->prepare("UPDATE instansi SET nama_instansi=?, email_pic=?, password=? WHERE id_instansi=?");
                $stmt->bind_param("sssi", $nama_instansi, $email_pic, $password, $id_instansi);
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
        public function get_instansi_image($id_instansi) {
            $query = "SELECT image_instansi FROM instansi WHERE id_instansi = ?";
            $stmt = $this->koneksi->prepare($query);
            $stmt->bind_param("i", $id_instansi);
            $stmt->execute();
            $result = $stmt->get_result();
            $instansi = $result->fetch_assoc();
    
            return $instansi ? $instansi['image_instansi'] : null;
        }
        
    }
