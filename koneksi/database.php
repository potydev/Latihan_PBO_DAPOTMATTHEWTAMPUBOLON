<?php
class Database {
    private $host = "localhost";
    private $db_name = "DB_LATIHAN_PBO_TRPL1A_DAPOTMATTHEWTAMPUBOLON";
    private $username = "root";
    private $password = ""; // Default password XAMPP/local adalah kosong, sesuaikan jika ada password
    public $conn;

    // Mendapatkan koneksi database
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Mengatur mode error PDO ke exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Mengatur default fetch mode ke associative array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            echo "Kesalahan Koneksi: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>
