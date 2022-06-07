<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/app-1/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/app-1/notes', function (Request $request, Response $response, $args) {
    $notes = DI::$db->getRepository('Note');
	$all_notes = $notes->findAll();
	$response->getBody()->write(json_encode([
		'_status' => true,
		'notes' => $all_notes,
	], JSON_PRETTY_PRINT));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/app-1/notes/create', function (Request $request, Response $response, $args) {
	$data = json_decode((string)$request->getBody());

	$result = [
		'_status' => false,
	];

	if(isset($data->title) && isset($data->text)){

		$note = new Note();
		$note->title = $data->title;
		$note->text = $data->text;
		$note->created_at = date('Y-m-d H:i:s');
		$note->updated_at = date('Y-m-d H:i:s');

		DI::$db->persist($note);
		DI::$db->flush();

		$result = [
			'_status' => true,
			'note' => $note,
		];

	}

    $response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT));
    return $response
	    ->withHeader('Content-Type', 'application/json')
	    ;
});

$app->post('/app-1/note/{id}/update', function (Request $request, Response $response, $args) {
	$id   = (int)$args['id'];
	$data = json_decode((string)$request->getBody());

	$result = [
		'_status' => false,
	];

	$note = DI::$db->find('Note', $id);

	if($note){

		$note->title = $data->title ?? $note->title;
		$note->text = $data->text ?? $note->text;
		$note->updated_at = date('Y-m-d H:i:s');

		DI::$db->persist($note);
		DI::$db->flush();

		$result = [
			'_status' => true,
			'note' => $note,
		];

	}

    $response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT));
    return $response
	    ->withHeader('Content-Type', 'application/json')
	    ;
});

$app->post('/app-1/note/{id}/delete', function (Request $request, Response $response, $args) {
	$id   = (int)$args['id'];

	$result = [
		'_status' => false,
	];

	$note = DI::$db->find('Note', $id);

	if($note){

		DI::$db->remove($note);
		DI::$db->flush();

		$result = [
			'_status' => true,
		];

	}

    $response->getBody()->write(json_encode($result, JSON_PRETTY_PRINT));
    return $response
	    ->withHeader('Content-Type', 'application/json')
	    ;
});