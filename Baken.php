<?php
class UsuariosManager {
    private $conn;

    public function __construct($servername, $username, $password, $database) {
        $this->conn = new mysqli($servername, $username, $password, $database);

        if ($this->conn->connect_error) {
            die("Conexión fallida: " . $this->conn->connect_error);
        }
    }

    public function agregarUsuario($id_usuario, $nombre, $email, $telefono) {
        $sql = "INSERT INTO usuarios (id_usuario, nombre, email, telefono) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss", $id_usuario, $nombre, $email, $telefono);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function eliminarUsuario($id_usuario) {
        $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function listarUsuarios() {
        $sql = "SELECT id_usuario, nombre, email, telefono FROM usuarios";
        $result = $this->conn->query($sql);

        $usuarios = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }

        return $usuarios;
    }

    public function actualizarUsuario($id_usuario, $nombre, $email, $telefono) {
        $sql = "UPDATE usuarios SET nombre=?, email=?, telefono=? WHERE id_usuario=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $email, $telefono, $id_usuario);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function cerrarConexion() {
        $this->conn->close();
    }
}

// Uso de la clase UsuariosManager
$servername = '127.0.0.1';
$username = 'root';
$password = '0215487';
$database = "prueba";

$manager = new UsuariosManager($servername, $username, $password, $database);

// Manejo de solicitudes desde el frontend
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data["accion"]) && $data["accion"] === "agregar") {
        $id_usuario = $data["id_usuario"];
        $nombre = $data["nombre"];
        $email = $data["email"];
        $telefono = $data["telefono"];

        if ($manager->agregarUsuario($id_usuario, $nombre, $email, $telefono)) {
            echo "Usuario agregado correctamente.";
        } else {
            echo "Error al agregar usuario.";
        }
    } else {
        echo "Acción no válida para la solicitud POST.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Listar usuarios y devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($manager->listarUsuarios());
}

$manager->cerrarConexion(); // Cerrar la conexión
?>