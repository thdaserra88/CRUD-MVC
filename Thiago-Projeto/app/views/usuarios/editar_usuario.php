<?php
 $id = htmlspecialchars($dados['id']);
 $nome = htmlspecialchars($dados["nome"]);
 $sexo = htmlspecialchars($dados["sexo"]);
 $tel = htmlspecialchars($dados["telefone"]);
 $cpf = htmlspecialchars($dados["cpf"]);
 $data_n = htmlspecialchars($dados["data_nascimento"]);
 $emai = htmlspecialchars($dados["email"]);
 $end = htmlspecialchars($dados["endereco"]);
 $num = htmlspecialchars($dados["numero"]);
 $bair = htmlspecialchars($dados["bairro"]);
 $cid = htmlspecialchars($dados["cidade"]);
 $est = htmlspecialchars($dados["estado"]);
 $ce = htmlspecialchars($dados["cep"]);
?>
<h2> EDIÇÃO DOS DADOS DE USUÁRIO </h2>
<br>
<form method="post" action="<?= BASE_URL ?>usuario/editar_usuario">
 <input type="hidden" name="id" value="<?php echo $id; ?>">
 Nome: <input type="text" name="nome" size="50" maxlength="50" value="<?= $nome ?>"
autofocus><br><br>
 Telefone: <input type="text" name="telefone" size="15" maxlength="15" value="<?= $tel
?>"><br><br>
 CPF: <input type="text" name="cpf" size="14" maxlength="14" value="<?= $cpf ?>"><br><br>

 Sexo: <label><?php echo $sexo; ?></label>
 <input type="radio" name="sexo" value="Masculino" <?php if($sexo == 'Masculino') echo
'checked'; ?>> M
 <input type="radio" name="sexo" value="Feminino" <?php if($sexo == 'Feminino') echo
'checked'; ?>> F
<!-- checked coloca o valor correspondente, padrão. Assim, o post sempre leva uma opção. -->
 <br><br>
 Data de nascimento: <input type="date" name="data_n" value="<?= $data_n ?>"><br><br>
 Email: <input type="text" name="email" size="50" maxlength="50" value="<?= $emai
?>"><br><br>
 Senha: <input type="password" name="senha" size="15" maxlength="15" value=""
autocomplete="new-password"><br><br>
<!-- autocomplete="new-password" é para que fique sem nada, já que muitas vezes o navegador coloca o que se está na memória, mesmo colocando o value=""; -->
CEP: <input type="text" name="cep" size="10" maxlength="10" value="<?= $ce
?>"><br><br>
 Endereço: <input type="text" name="endereco" size="50" maxlength="60" value="<?= $end
?>"><br><br>
Número: <INPUT type="text" name="numero" size="5" maxlength="5" value="<?= $num
?>"> <br><br>
 Bairro: <input type="text" name="bairro" size="50" maxlength="50" value="<?= $bair
?>"><br><br>
 Cidade: <input type="text" name="cidade" size="50" maxlength="50" value="<?= $cid
?>"><br><br>
 Estado: <input type="text" name="estado" size="2" maxlength="2" value="<?= $est
?>"><br><br><br>
 <input type="submit" value="Alterar">
</form>
<br>
<form method="post" action="<?= BASE_URL ?>usuario/excluir">
 <input type="hidden" name="c_exc" value="<?PHP ECHO $id ?>">
 <input type="submit" value="Excluir">
</form>
<br>
<table border="0" width="900" align="">
<tr>
<td>
<form action="<?= BASE_URL ?>usuario/alterarFoto" method="post"
enctype="multipart/form-data">
<label for="foto">Selecionar foto para alteração:</label>
<input type="file" id="foto" name="foto"> <br /> <br />
<input type="submit" value="Enviar" name="submit" /><br><br>
</form>
</td>
<br/> <br/>
</tr>
<?php
$foto = $_SESSION['foto_atual'];
?>
<tr>
<td align="" height="89"><img src="<?= BASE_URL . 'assets/images/usuario/' .
$foto . "?t=" . time(); ?>" height="100" width="100">
<!-- Aqui, para forçar o navegador a buscar sempre a imagem do servidor e não a do possível cache.
Isso é importante para caso o usuário altere a foto mais de uma vez na mesma hora. O ?t= é para
adicionarmos o parâmetro da hora na url (query string). Foi usado t como nome da variável, mas
pode ser qualquer nome. -->
</tr>
<tr>
<td>
<a href="<?= BASE_URL ?>usuario/excluirFoto">Clique aqui para excluir a sua foto.</a>
<br><br>
</td>
</tr>
</table>