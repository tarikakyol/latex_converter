<?php
require '../vendor/autoload.php';

ini_set('expose_php', 'off');
header_remove("X-Powered-By");

$app = new \Slim\Slim();

$app->get('/', function () use ($app) {
    echo '';
});

$app->get('/foo', function () use ($app) {
    $app->contentType('application/json');
    $app->response()->body(json_encode([
        'foo' => 'bar'
    ]));
});

$app->options('/foo', function () use ($app) {
});

$app->post('/foo', function () use ($app) {
    $app->contentType('application/json');
    //$app->response()->header('Access-Control-Allow-Origin : * ');

    $tf = tempnam('/tmp', 'FOO');
    file_put_contents($tf.'.tex', $app->request()->params('foo'));
    $output = array();
    $return_var = 0;
    $foo = file_get_contents($tf.'.tex');
    $foo = preg_replace('/' . preg_quote('\begin{framed}') . '.*' . preg_quote('\end{framed}') . '/ms', '', $foo);
    file_put_contents($tf.'.tex', $foo);
    chdir('/tmp');
    exec('/usr/bin/htlatex '.$tf.'.tex /var/www/iop/ht5mjlatex.cfg', $output, $return_var);
    @exec('/usr/bin/pdflatex '.$tf.'.tex');
    @copy($tf . '.pdf', '/var/www/iop/public/out.pdf');
    $html = file_get_contents($tf.'.html');
    $app->response()->body(json_encode([
        'foo' => $_FILES,
        'tf' => $tf,
        'output' => $output,
	'html' => $html,
        'return_var' => $return_var
    ]));
    return;
    $app->response()->body(json_encode([
	'fooo' => $app->request()->params('foo')
    ]));
    $tf = tempnam('/tmp', 'FOO').'X.tex';
    move_uploaded_file($_FILES['foo']['tmp_name'], $tf);
    $app->response()->body(json_encode(['fooo' => 'baar']));
    return;
    $output = array();
    $return_var = 0;
    $foo = file_get_contents($tf);
    $foo = preg_replace('/' . preg_quote('\begin{framed}') . '.*' . preg_quote('\end{framed}') . '/ms', '', $foo);
    file_put_contents($tf, $foo);
    chdir('/tmp');
    exec('/usr/bin/htlatex '.$tf.' /var/www/iop/ht5mjlatex.cfg', $output, $return_var);
    $app->response()->body(json_encode([
        'foo' => $_FILES,
        'tf' => $tf,
        'output' => $output,
        'return_var' => $return_var
    ]));
});

$app->run();
