<?php

chdir(dirname(__DIR__));

// Inclui o autoload do Composer
require 'vendor/autoload.php';

// Caminho para o arquivo .env na raiz do projeto
$dotenvPath = __DIR__ . '/../.env';

// Carrega as variáveis de ambiente
$dotenv = Dotenv\Dotenv::createImmutable(dirname($dotenvPath));
$dotenv->load();

$servername = $_ENV['HOST'];
$username = $_ENV["USER"];
$password = $_ENV["PASS"];
$dbname = $_ENV["DB_NAME"];

try {
    $dsn = "mysql:host=$servername;dbname=$dbname";
    $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    );
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Conexão falhou: " . $e->getMessage());
}

require_once 'models/UserModel.php';
require_once 'services/UserService.php';
require_once 'controllers/UserController.php';

require_once 'models/AuthModel.php';
require_once 'services/AuthService.php';
require_once 'controllers/AuthController.php';

require_once 'models/ProjectModel.php';
require_once 'services/ProjectService.php';
require_once 'controllers/ProjectController.php';

require_once 'models/ArticleModel.php';
require_once 'services/ArticleService.php';
require_once 'controllers/ArticleController.php';

require_once 'models/BlacklistModel.php';
require_once 'services/BlacklistService.php';
require_once 'controllers/BlacklistController.php';
?>
