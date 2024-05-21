<?php
require_once 'config.php';
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$key = $_ENV['SECRET'];



$userController = new UserController(new UserService(new UserModel($conn)));
$authController = new AuthController(new AuthService(new AuthModel($conn)));
$projectController = new ProjectController(new ProjectService(new ProjectModel($conn)));
$articleController = new ArticleController(new ArticleService(new ArticleModel($conn)));
$blacklistController = new BlacklistController(new BlacklistService(new BlacklistModel($conn)));

function authenticate($key, $blacklist) {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["message" => "Token não fornecido."]);
        exit();
    }

    list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');

    if (!$jwt) {
        http_response_code(401);
        echo json_encode(["message" => "Formato do token inválido."]);
        exit();
    }

    try {
        $decoded = JWT::decode($jwt, new Key($key, 'HS256')); 
        $status = $blacklist->verifytoken($jwt);

        if($status == false){
            http_response_code(401);
            echo json_encode(["message" => "not authorized."]);
            exit();
        }
        return (array) $decoded;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Access denied.", "error" => $e->getMessage()]);
        exit();
    }
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateStrongPassword($password) {
    $passwordPattern = '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    return preg_match($passwordPattern, $password);
}

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $issuedAt = time();
    $expireIn = isset($_ENV['EXPIRE_IN']) ? (int)$_ENV['EXPIRE_IN'] : 3600;

    try {
        if (strpos($requestUri, '/user') !== false) {

            $errors = [];

            if (!validateEmail($data['email'])) {
                $errors[] = "invalid email.";
            }

            if (!validateStrongPassword($data['password'])) {
                $errors[] = "Password must be at least 8 characters long, including uppercase letters, lowercase letters, numbers and special characters.";
            }

            if (!empty($errors)) {
                http_response_code(400);
                echo json_encode(['errors' => $errors]);
                exit();
            }
            $userId = $userController->addUser($data);
            $payload = [
                'email' => $userId['email'],
                'id' => $userId['id'],
                'iat' => $issuedAt,
                'exp' => $issuedAt + $expireIn
            ];
            $jwt = JWT::encode($payload, $key, 'HS256');
            $data['token'] = $jwt;
            $data['userId'] = $userId['id'];
            $data['status'] = 1;
            $blacklistController->addtoken($data);
            header('Authorization: ' . $jwt);
            http_response_code(201);
            echo json_encode(['userId' => $userId]);
        } elseif (strpos($requestUri, '/login') !== false) {
            $loginResult = $authController->login($data, new BlacklistModel($conn));
            if ($loginResult === 'invalid email or password') {
                http_response_code(400);
                echo json_encode(['error' => $loginResult]);
                exit();
            }
            $payload = [
                'email' => $loginResult['email'],
                'id' => $loginResult['id'],
                'iat' => $issuedAt,
                'exp' => $issuedAt + $expireIn
            ];
            $jwt = JWT::encode($payload, $key, 'HS256');
            $data['token'] = $jwt;
            $data['userId'] = $loginResult['id'];
            $data['status'] = 1;
            $blacklistController->addtoken($data);
            header('Authorization: ' . $jwt);
            echo json_encode($loginResult);
        } elseif(strpos($requestUri, '/project') !== false) {
            $decoded = authenticate($key, $blacklistController); 
            $data['userId'] =  $decoded['id'];
            $createResult = $projectController->addProject($data);
            
            http_response_code(201);
            echo json_encode($data);
        } elseif (strpos($requestUri, '/article') !== false) {
            $decoded = authenticate($key, $blacklistController); 
            $data['userId'] =  $decoded['id'];
            $createResult = $articleController->addArticle($data, new ProjectModel($conn));
            if($createResult == false) {
                http_response_code(404);
                $createResult = "resource not found";
            } else {
                http_response_code(201);
            }
            
            echo json_encode($createResult);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($requestMethod === 'GET') {
    try {
        if (strpos($requestUri, '/user') !== false) {
            authenticate($key, $blacklistController); 
            http_response_code(200);
            echo json_encode("well come");
        } elseif (strpos($requestUri, '/article') !== false) {
            authenticate($key, $blacklistController); 
            $urlParts = explode('/', trim($requestUri, '/'));
            $id = (int)$urlParts[3]; 
            $data =  [
                "id" => $id,
              ];
            $result = $articleController->listArticleById($data);
            if($result == false) {
                http_response_code(404);
                $result = "resource not found";
            } else {
                http_response_code(200);
            }
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif($requestMethod === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    try {
        if (strpos($requestUri, '/user') !== false) {
            $decoded = authenticate($key, $blacklistController);
            $headers = getallheaders();
            list($jwt) = sscanf($headers['Authorization'], 'Bearer %s');
            $data['token'] = $jwt;
            $data['userId'] =  $decoded['id'];
            $userId = $userController->updateUser($decoded['id'], $data, new BlacklistModel($conn));
            if($userId == false) {
                http_response_code(404);
                $userId = "resource not found";
            }else {
                http_response_code(200);
            }
            
            echo json_encode('log in again');
        } elseif (strpos($requestUri, '/project') !== false) {
            $decoded = authenticate($key, $blacklistController); 
            $data['userId'] =  $decoded['id'];
            $urlParts = explode('/', trim($requestUri, '/'));
            $projectId = (int)$urlParts[3]; 
            $createResult = $projectController->updateProject(1, $data);
            
            http_response_code(201);
            echo json_encode($createResult);
        } elseif (strpos($requestUri, '/article') !== false) {
            $decoded = authenticate($key, $blacklistController); 
            $data['userId'] =  $decoded['id'];
            $urlParts = explode('/', trim($requestUri, '/'));
            $articleId = (int)$urlParts[3]; 
            $data['articleId'] =  $articleId;
            $updateResult = $articleController->updateArticle($data, new ProjectModel($conn));
            if($updateResult == false) {
                http_response_code(404);
                $updateResult = "resource not found";
            }else {
                http_response_code(204);
            }
            echo json_encode($updateResult);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

} elseif ($requestMethod === "DELETE") {

    $data = json_decode(file_get_contents("php://input"), true);
    $issuedAt = time();
    $expireIn = isset($_ENV['EXPIRE_IN']) ? (int)$_ENV['EXPIRE_IN'] : 3600;

    try {
        if (strpos($requestUri, '/article') !== false) {
            $decoded = authenticate($key, $blacklistController);  
            $urlParts = explode('/', trim($requestUri, '/'));
            $id = (int)$urlParts[3]; 
            $data['userId'] =  $decoded['id'];
            $data['id'] = $id;
            $deleteResult = $articleController->deleteArticle($data, new ProjectModel($conn));
            if($deleteResult == false) {
                http_response_code(404);
                $deleteResult = "resource not found";
            }else {
                http_response_code(200);
            }
            
            echo json_encode($deleteResult);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}
?>
