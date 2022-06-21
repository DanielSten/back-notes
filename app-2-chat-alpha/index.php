<?php

define('BASE_PATH', dirname(__FILE__));

$pdo = new PDO('sqlite:' . BASE_PATH . '/mydb.sqlite');


$uri = $_SERVER['REQUEST_URI'];
$uri = explode('?', $uri);
$uri = array_shift($uri);

$method = $_SERVER['REQUEST_METHOD'];

$json = file_get_contents('php://input');
$json = empty($json) ? null : json_decode($json);

$routes = [
	'GET' => [
		'/alpha-chat/messages' => function() use ($pdo){
			$timestamp = time();
			if(array_key_exists('last', $_GET)){
				$query = $pdo->prepare("SELECT * FROM (SELECT * FROM messages ORDER BY `timestamp` DESC LIMIT :limit) ORDER BY `timestamp`");
				$query->execute([
					'limit' => $_GET['last']
				]);
			} elseif(array_key_exists('t', $_GET)){
				$query = $pdo->prepare("SELECT * FROM messages WHERE `timestamp` >= :t ORDER BY `timestamp`");
				$query->execute([
					't' => $_GET['t']
				]);
			} else {
				$query = $pdo->prepare("SELECT * FROM messages ORDER BY `timestamp`");
				$query->execute();
			}
			return [
				'_status' => true,
				'_timestamp' => $timestamp,
				'messages' => $query->fetchAll(PDO::FETCH_OBJ),
			];
		},
	],
	'POST' => [
		'/alpha-chat/send' => function() use ($pdo, $json){
			if(!isset($json->name) || !$json->name || !isset($json->text) || !$json->text){
				return [
					'_status' => false,
				];
			}
			$query = $pdo->prepare('INSERT INTO messages (`name`, `text`, `timestamp`) VALUES (:name, :text, :t)');
			$r = $query->execute([
				'name' => $json->name,
				'text' => $json->text,
				't' => time(),
			]);
			if(!$r){
				return [
					'_status' => false,
				];
			}
			$id = $pdo->lastInsertId();
			$query = $pdo->prepare("SELECT * FROM messages WHERE `id` = :id");
			$query->execute([
				'id' => $id
			]);
			$message = $query->fetch(PDO::FETCH_OBJ);
			if(!$message){
				return [
					'_status' => false,
				];
			}
			return [
				'_status' => true,
				'message' => $message,
			];	
		}
	],
];

if($method == 'OPTIONS'){
	die;
}

if(array_key_exists($method, $routes)){
	foreach ($routes[$method] as $route => $action) {
		$matches = [];
		if(preg_match('|^' . $route . '$|', $uri, $matches)){
			break;
		}
	}
}

if(!isset($action) || !is_callable($action)) {
	http_response_code(404);
	die('Not found');
}

array_shift($matches);
$result = $action(...$matches);

header('Content-Type: application/json');
echo json_encode($result, 128);