<?php
require_once 'Conexao.php';

class Usuario {

private $pdo;

public function __construct()
{
 $this->pdo = Conexao::conectar();
 $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

public function cadastrar($dados)
{
 $nom  = trim($dados['nome'] ?? '');
 $sex  = $dados['sexo'] ?? '';
 $tel  = trim($dados['telefone'] ?? '');
 $cp   = trim($dados['cpf'] ?? '');
 $dn   = $dados['data_n'] ?? '';
 $ema  = trim($dados['email'] ?? '');
 $senOriginal = $dados['senha'] ?? '';

 $end  = trim($dados['endereco'] ?? '');
 $num  = trim($dados['numero'] ?? '');
 $bair = trim($dados['bairro'] ?? '');
 $cid  = trim($dados['cidade'] ?? '');
 $est  = trim($dados['estado'] ?? '');
 $ce   = trim($dados['cep'] ?? '');

 $campos = [
  $nom,$sex,$tel,$cp,$dn,$ema,$senOriginal,$end,$num,$bair,$cid,$est,$ce
 ];

 foreach ($campos as $valor)
 {
  if(empty($valor))
  {
   return false;
  }
 }

 $sen = password_hash($senOriginal, PASSWORD_DEFAULT);

 $cmd = $this->pdo->prepare(
  "SELECT id FROM tb_usuario WHERE email = :e OR cpf = :c"
 );

 $cmd->bindValue(":e",$ema);
 $cmd->bindValue(":c",$cp);
 $cmd->execute();

 if($cmd->rowCount() > 0)
 {
  return "duplicado";
 }

 try
 {
  $cmd = $this->pdo->prepare("
   INSERT INTO tb_usuario
   (nome,sexo,telefone,cpf,data_nascimento,email,senha,
    endereco,numero,bairro,cidade,estado,cep,foto_usuario)
   VALUES
   (:n,:s,:t,:c,:d,:e,:h,:en,:nu,:ba,:ci,:es,:cep,:ft)
  ");

  $cmd->execute([
   ':n'=>$nom,
   ':s'=>$sex,
   ':t'=>$tel,
   ':c'=>$cp,
   ':d'=>$dn,
   ':e'=>$ema,
   ':h'=>$sen,
   ':en'=>$end,
   ':nu'=>$num,
   ':ba'=>$bair,
   ':ci'=>$cid,
   ':es'=>$est,
   ':cep'=>$ce,
   ':ft'=>"foto.jpg"
  ]);

  return true;

 }
 catch (Exception $e)
 {
  return false;
 }

}
public function logarUsuario($em, $sen)
{
$cmd = $this->pdo->prepare("SELECT * FROM tb_usuario WHERE email = :e");
$cmd->bindValue(":e", $em);
$cmd->execute();
if ($cmd->rowCount() == 0)
{
return "email_inexistente";
}
$dados = $cmd->fetch(PDO::FETCH_ASSOC);
if (password_verify($sen, $dados['senha']))
{
return $dados;
}
else
{
return "senha_nao_confere";
}
}
public function pesquisarUsuario($id)
{
$cmd = $this->pdo->prepare("
SELECT
id,
nome,
sexo,
telefone,
cpf,
data_nascimento,
email,
endereco,
numero,
bairro,
cidade,
estado,
cep
FROM tb_usuario
WHERE id = :i
");
$cmd->bindValue(":i", $id, PDO::PARAM_INT);
$cmd->execute();
return $cmd->fetch(PDO::FETCH_ASSOC);
}
public function atualizarUsuario($dados)
{
$id = (int)$dados['id'] ?? ''; // (int) -> forçar tipo inteiro
$nome = trim($dados['nome'] ?? '');
$sexo = $dados['sexo'] ?? '';
$telefone = trim($dados['telefone'] ?? '');
$cpf = trim($dados['cpf'] ?? '');
$data_n = $dados['data_n'] ?? '';
$email = trim($dados['email'] ?? '');
$senha = $dados['senha'] ?? '';
$endereco = trim($dados['endereco'] ?? '');
$numero = trim($dados['numero'] ?? '');
$bairro = trim($dados['bairro'] ?? '');
$cidade = trim($dados['cidade'] ?? '');
$estado = trim($dados['estado'] ?? '');
$cep = trim($dados['cep'] ?? '');
// Validação de campos vazios
$campos = [
$nome, $sexo, $telefone, $cpf, $data_n,
$email, $endereco, $numero, $bairro,
 $cidade, $estado, $cep
];
foreach ($campos as $valor)
{
if (empty($valor))
{
return "erro_de_conexao";
}
}
// Se digitou nova senha, criptografar
if (!empty($senha))
{
$sen = password_hash($senha, PASSWORD_DEFAULT);
}
else
{
// Busca senha atual no banco
$cmdSenha = $this->pdo->prepare("SELECT senha FROM tb_usuario
WHERE id = :i");
$cmdSenha->bindValue(":i", $id, PDO::PARAM_INT);
$cmdSenha->execute();
$sen = $cmdSenha->fetchColumn();
// id não existe no banco. geralmente, vai existir, pois o usuário estará logado. colocado apenas para o caso de haver um erro no formulário:
if (!$sen)
{
return false;
}
}
// Atualiza usuário
$cmd = $this->pdo->prepare("
UPDATE tb_usuario SET
nome = :no,
sexo = :sex,
telefone = :te,
cpf = :cp,
data_nascimento = :da,
email = :em,
senha = :sen,
endereco = :en,
numero = :nu,
bairro = :ba,
cidade = :ci,
estado = :es,
cep = :ce
WHERE id = :i
");
$cmd->bindValue(":no", $nome);
$cmd->bindValue(":sex", $sexo);
$cmd->bindValue(":te", $telefone);
$cmd->bindValue(":cp", $cpf);
$cmd->bindValue(":da", $data_n);
$cmd->bindValue(":em", $email);
$cmd->bindValue(":sen", $sen);
$cmd->bindValue(":en", $endereco);
$cmd->bindValue(":nu", $numero);
$cmd->bindValue(":ba", $bairro);
$cmd->bindValue(":ci", $cidade);
$cmd->bindValue(":es", $estado);
$cmd->bindValue(":ce", $cep);
$cmd->bindValue(":i", $id, PDO::PARAM_INT);
$cmd->execute();
return $cmd->rowCount() > 0; // retornará true se afetar uma linha no banco de dados e false se não.
}
public function excluirUsuario($idd)
{
$cmd = $this->pdo->prepare("DELETE FROM tb_usuario WHERE id = :i");
$cmd->bindValue(":i",$idd);
$cmd->execute();
return true;
}
public function alterarFoto($id_usu, $foto)
{
$cmd = $this->pdo->prepare("UPDATE tb_usuario SET foto_usuario = :foto WHERE id = :id");
$cmd->bindValue(":foto", $foto);
$cmd->bindValue(":id", $id_usu);
$cmd->execute();
// Retorna true se realmente atualizou
return $cmd->rowCount() > 0;
}
public function excluirFoto($id_usu)
{
$cmd = $this->pdo->prepare("UPDATE tb_usuario SET foto_usuario = 'foto.jpg' WHERE id
= :i");
$cmd->bindValue(":i", $id_usu, PDO::PARAM_INT);
$cmd->execute();
return $cmd->rowCount() > 0;

}
}