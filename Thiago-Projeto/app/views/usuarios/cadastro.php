<H2> PREENCHA O FORMULÁRIO PARA SE CADASTRAR NO SITE </H2>
<br>
<form method="POST" action="<?= BASE_URL ?>usuario/cadastrar">
<input type="hidden" name="cadastrando">
Nome:
<input type="text" name="nome" size="50" maxlength="50" autofocus required>
<br><br>
Telefone:
<input type="text" name="telefone" size="15" maxlength="15" required>
<br><br>
CPF:
<input type="text" name="cpf" size="14" maxlength="14" required>
<br><br>
<label>Sexo:</label>
<input type="radio" id="sexo_m" name="sexo" value="Masculino" required>
<label for="sexo_m">Masculino</label>
<input type="radio" id="sexo_f" name="sexo" value="Feminino">
<label for="sexo_f">Feminino</label>
<br><br>
Data de nascimento:
<input type="date" name="data_n" required>
<br><br>
Email:
<input type="email" name="email" size="50" maxlength="50" required>
<br><br>
Senha:
<input type="password" name="senha" size="15" maxlength="15" required>
<br><br>
Confirmar senha:
<input type="password" name="senhac" size="15" maxlength="15" required>
<br><br>
CEP:
<input type="text" name="cep" size="10" maxlength="10" required>
<br><br>
Endereço:
<input type="text" name="endereco" size="50" maxlength="50" required>
<br><br>
Número:
<input type="text" name="numero" size="5" maxlength="5" required>
<br><br>
Bairro:
<input type="text" name="bairro" size="50" maxlength="50" required>
<br><br>
Cidade:
<input type="text" name="cidade" size="50" maxlength="50" required>
<br><br>
Estado:
<input type="text" name="estado" size="2" maxlength="2" required>
<br><br><br>
<input type="submit" value="Cadastrar">
<br><br>
</form>
<!-- Abaixo, a rotina para trabalhar os erros que virão do usuarioController.php -->
<?php if(isset($_SESSION['erro'])): ?>
<div id="msg-erro" class="msg">
<?php echo $_SESSION['erro']; ?>
</div>
<?php unset($_SESSION['erro']); ?>
<?php endif; ?>
<script>
setTimeout(function() {
const msg = document.querySelector(".msg");
if (msg) {
msg.style.opacity = "0";
setTimeout(() => msg.remove(), 500);
}
}, 2000);
</script>