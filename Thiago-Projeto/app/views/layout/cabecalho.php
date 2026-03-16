<!DOCTYPE html>
<html>
<head>
<title><?= $titulo ?? "MEU_CRUD_MVC" ?></title>
<meta charset="utf-8">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<?PHP
if(isset($_SESSION['foto_atual']))
{
$foto = $_SESSION['foto_atual'];
}
else
{
$foto = "foto.jpg";
}
?>
<nav>
<a href="<?= BASE_URL ?>home/index">Home</a> |
<a href="<?= BASE_URL ?>site/somosNos">Quem Somos</a> |
<?php if(!isset($_SESSION['logado']) || $_SESSION['logado'] != 'SIM'): ?>
<a href="<?= BASE_URL ?>usuario/cadastro">Cadastro</a> |
<a href="<?= BASE_URL ?>usuario/login">Login</a>
<?php else: ?>
<a href="<?= BASE_URL ?>usuario/editar">Meus Dados</a> |
<a href="<?= BASE_URL ?>usuario/logout">Logout</a>
<?php endif; ?>
<img src="<?= BASE_URL . 'assets/images/usuario/' . $foto . '?t=' . time(); ?>" height="50"
width="50">
</nav>
<hr>