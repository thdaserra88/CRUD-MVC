<?php
require_once "Controller.php"; // adiconando o script controller.php
class UsuarioController extends Controller // herdando o script controller.php
{
 public function cadastro() // para enviar para a rota
 {
    $this->render("usuarios/cadastro", ["titulo" => "CADASTRO"]);
// aqui, chamamos a rotina para cadastrar. Passamos os dados do caminho (usuarios/cadastro) para a função render() do script controller.php para que chame a página correspondente.
 }
 public function cadastrar() // para receber os dados do formulário
{
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
// Validar confirmação de senha:
if ($_POST['senha'] !== $_POST['senhac'])
{
$_SESSION['erro'] = "As senhas não coincidem.";
header("Location: " . BASE_URL . "usuario/cadastro");
exit;
}
/*// VALIDAR CPF
if(!$this->validarCPF($_POST['cpf']))
{
$_SESSION['erro'] = "CPF inválido.";
header("Location: " . BASE_URL . "usuario/cadastro");
exit;
$_POST['cpf'] = $this->limparNumero($_POST['cpf']);
$_POST['telefone'] = $this->limparNumero($_POST['telefone']);
$_POST['cep'] = $this->limparNumero($_POST['cep']);
} */
require_once "../app/models/Usuario.php";
$usuario = new Usuario();
$resultado = $usuario->cadastrar($_POST);
if ($resultado === true)
{
$_SESSION['sucesso'] = "Usuário cadastrado com sucesso!";
header("Location: " . BASE_URL . "usuario/login");
}
elseif ($resultado === "duplicado")
{
$_SESSION['erro'] = "E-mail ou CPF já cadastrado.";
header("Location: " . BASE_URL . "usuario/cadastro");
}
else
{
$_SESSION['erro'] = "Erro ao cadastrar.";
header("Location: " . BASE_URL . "usuario/cadastro");
}
exit;
}
}
/*private function validarCPF($cpf)
{
// Remove tudo que não for número
$cpf = preg_replace('/[^0-9]/', '', $cpf);
if(strlen($cpf) != 11) return false;
// Bloqueia CPFs repetidos (11111111111 etc)
if(preg_match('/(\d)\1{10}/', $cpf)) return false;
// Primeiro dígito
for ($t = 9; $t < 11; $t++) {
for ($d = 0, $c = 0; $c < $t; $c++) {
$d += $cpf[$c] * (($t + 1) - $c);
}
$d = ((10 * $d) % 11) % 10;
if ($cpf[$c] != $d) {
return false;
}
}
return true;
}
private function limparNumero($valor)
{
return preg_replace('/\D/', '', $valor);
} */
    public function login()
    {
   $this->render("usuarios/login", ["titulo" => "LOGIN"]);
    }
   public function login_usuario()
   {
   if($_SERVER['REQUEST_METHOD'] === 'POST')
   {
   require_once "../app/models/Usuario.php";
   $usuario = new Usuario();
   $resultado = $usuario->logarUsuario(
   $_POST['email'],
   $_POST['senha']
   );
   // Se for array → login correto
   if(is_array($resultado))
   {
   $_SESSION['logado'] = "SIM";
   $_SESSION['id_usuario'] = $resultado['id'];
   $_SESSION['foto_atual'] = $resultado['foto_usuario'];
    $_SESSION['email_usu'] = $resultado['email'];
   header("Location: " . BASE_URL . "home/index");
   }
   elseif($resultado === "email_inexistente")
   {
   $_SESSION['erro'] = "E-mail não encontrado.";
   header("Location: " . BASE_URL . "usuario/login");
   }
   elseif($resultado === "senha_nao_confere")
   {
   $_SESSION['erro'] = "Senha incorreta.";
   header("Location: " . BASE_URL . "usuario/login");
   }
   exit;
   }
   }
   public function editar()
{
if(!isset($_SESSION['logado']) || $_SESSION['logado'] != 'SIM')
{
header("Location: " . BASE_URL . "usuario/login");
exit;
}
require_once "../app/models/Usuario.php";
$usuario = new Usuario();
$dados = $usuario->pesquisarUsuario($_SESSION['id_usuario']);
$dados['titulo'] = "EDITAR CADASTRO"; // para acrescentar no array $dados o título que o cabeçalho chamará.
$this->render("usuarios/editar_usuario", $dados);
}
public function editar_usuario()
{
if (!isset($_SESSION['logado']) || $_SESSION['logado'] != 'SIM')
{
header("Location: " . BASE_URL . "usuario/login");
exit;
}

require_once "../app/models/Usuario.php";

$usuario = new Usuario();

$resultado = $usuario->atualizarUsuario($_POST);

if ($resultado === true)
{
$_SESSION['sucesso'] = "Alteração feita com sucesso!";
header("Location: " . BASE_URL . "usuario/editar");
}
else
{
$_SESSION['erro'] = "Erro ao atualizar usuário.";
header("Location: " . BASE_URL . "usuario/editar");
}
exit;
}
public function excluir()
{
if(isset($_SESSION['id_usuario']))
{
require_once __DIR__ . "/../models/Usuario.php";
$usuario = new Usuario();
$id = $_SESSION['id_usuario'];
if($usuario->excluirUsuario($id))
{
$_SESSION = [];
session_destroy();
header("Location: " . BASE_URL . "home/index");
exit;
}
else
{
header("Location: " . BASE_URL . "usuario/editar");
exit;
}
}
}
public function logout()
{
// Limpa todas as variáveis da sessão
$_SESSION = [];
// Destroi a sessão
session_destroy();
// Redireciona para home
header("Location: " . BASE_URL . "home/index");
exit;
}
public function alterarFoto()
{
// Excluir foto anterior (se não for a padrão)
if (isset($_SESSION['foto_atual']) && $_SESSION['foto_atual'] != 'foto.jpg') {
$foto_antiga = $_SESSION['foto_atual'];
$caminho_antigo = __DIR__ . "/../../public/assets/images/usuario/" . $foto_antiga;
if (file_exists($caminho_antigo)) {
unlink($caminho_antigo);
}
}
// Verifica se o arquivo foi enviado corretamente
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
$_SESSION["erro_envio_foto"] = "Erro no envio da foto.";
header("Location: " . BASE_URL . "usuario/editar");
exit();
}
$id_usu = $_SESSION['id_usuario'];
$email_usu = $_SESSION['email_usu'];
$foto = $_FILES['foto']['name'];
// Extrair extensão
$extensao = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
if (!in_array($extensao, ['jpg','jpeg','png','gif'])) {
$_SESSION["foto_invalida"] = "Formato inválido.";
header("Location: " . BASE_URL . "usuario/editar");
exit();
}
// Nome único do arquivo (email sem espaços + extensão)
$arq2 = str_replace(' ', '', $email_usu) . '.' . $extensao;
// Caminho físico no servidor
$pasta_destino = __DIR__ . "/../../public/assets/images/usuario/";
$to = $pasta_destino . $arq2;
$from = $_FILES["foto"]["tmp_name"];
// Move o arquivo
if (!move_uploaded_file($from, $to)) {
$_SESSION["erro_envio_foto"] = "Erro ao salvar a foto.";
header("Location: " . BASE_URL . "usuario/editar");
exit();
}
// Atualiza no banco
require_once "../app/models/Usuario.php";
$usuario = new Usuario();
$usuario->alterarFoto($id_usu, $arq2);
// Atualiza sessão
$_SESSION['foto_atual'] = $arq2;

header("Location: " . BASE_URL . "usuario/editar");
exit();
}
public function excluirFoto()
{
require_once "../app/models/Usuario.php";
$usuario = new Usuario();
$id_usu = $_SESSION['id_usuario'];
$foto = $_SESSION['foto_atual'];
if (!$usuario->excluirFoto($id_usu)) {
header("Location: " . BASE_URL . "usuario/editar");
exit();
}
// Excluir fisicamente se não for a padrão
if ($foto != 'foto.jpg') {
$caminho = __DIR__ . "/../../public/assets/images/usuario/" . $foto;
if (file_exists($caminho)) {
unlink($caminho);
}
$_SESSION['foto_atual'] = 'foto.jpg';
}
header("Location: " . BASE_URL . "usuario/editar");
exit();
}
}