<?php

use app\controller\User;
use app\controller\Cliente;
use app\controller\Login;
use app\controller\Empresa;
use app\controller\Fornecedor;
use app\middleware\Auth;
use app\controller\Home;
use app\controller\PaymentTerms;
use Slim\Routing\RouteCollectorProxy;

$app->get('/', Home::class . ':home'); #->add(Auth::route());
$app->get('/home', Home::class . ':home'); #->add(Auth::route());
#$app->get('/', ControllerHome::class . ':home')->add(Middlewares::route());

$app->get('/login', Login::class . ':login');

$app->group('/home', function (RouteCollectorProxy $group) {
    #$group->post('/tema', Home::class . ':tema');
});
$app->group('/login', function (RouteCollectorProxy $group) {
    $group->post('/precadastro', Login::class . ':precadastro');
    $group->post('/autenticar', Login::class . ':autenticar');
});


$app->group('/usuario', function (RouteCollectorProxy $group) {
    $group->get('/lista', User::class . ':lista'); #->add(Auth::route());
    $group->get('/cadastro', User::class . ':cadastro'); #->add(Auth::route());
    $group->get('/alterar/{id}', User::class . ':alterar'); #->add(Auth::route());
    $group->get('/print', User::class . ':print');
    $group->post('/update', User::class . ':update');
    $group->post('/listuser', User::class . ':listuser');
    $group->post('/insert', User::class . ':insert');
    $group->post('/delete', User::class . ':delete');
});

$app->group('/cliente', function (RouteCollectorProxy $group) {
    $group->get('/lista', Cliente::class . ':lista'); #->add(Auth::route());
    $group->get('/cadastro', Cliente::class . ':cadastro'); #->add(Auth::route());
    $group->get('/alterar/{id}', Cliente::class . ':alterar'); #->add(Auth::route());
    $group->get('/print', Cliente::class . ':print');
    $group->post('/update', Cliente::class . ':update');
    $group->post('/listcliente', Cliente::class . ':listcliente');
    $group->post('/insert', Cliente::class . ':insert');
    $group->post('/delete', Cliente::class . ':delete');
});

$app->group('/empresa', function (RouteCollectorProxy $group) {
    $group->get('/lista', Empresa::class . ':lista'); #->add(Auth::route());
    $group->get('/cadastro', Empresa::class . ':cadastro'); #->add(Auth::route());
    $group->get('/alterar/{id}', Empresa::class . ':alterar'); #->add(Auth::route());
    $group->get('/print', Empresa::class . ':print');
    $group->post('/update', Empresa::class . ':update');
    $group->post('/listempresa', Empresa::class . ':listempresa');
    $group->post('/insert', Empresa::class . ':insert');
    $group->post('/delete', Empresa::class . ':delete');
});

$app->group('/fornecedor', function (RouteCollectorProxy $group) {
    $group->get('/lista', Fornecedor::class . ':lista'); #->add(Auth::route());
    $group->get('/cadastro', Fornecedor::class . ':cadastro'); #->add(Auth::route());
    $group->get('/alterar/{id}', Fornecedor::class . ':alterar'); #->add(Auth::route());
    $group->get('/print', Fornecedor::class . ':print');
    $group->post('/update', Fornecedor::class . ':update');
    $group->post('/listfornecedor', Fornecedor::class . ':listfornecedor');
    $group->post('/insert', Fornecedor::class . ':insert');
    $group->post('/delete', Fornecedor::class . ':delete');
});
$app->group('/pagamento', function (RouteCollectorProxy $group) {
    $group->get('/lista', PaymentTerms::class . ':lista'); #->add(Auth::route());
    $group->get('/cadastro', PaymentTerms::class . ':cadastro'); #->add(Auth::route());

});