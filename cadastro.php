<?php
error_reporting(-1);
$obj_mysqli = new mysqli("localhost", "andre", "@eF7400313", "meus_locais");
 
if ($obj_mysqli->connect_errno)
{
	echo "Ocorreu um erro na conexão com o banco de dados.";
	exit;
}
 
mysqli_set_charset($obj_mysqli, 'utf8');
//Validando a existência dos dados
if(isset($_POST["nome"]) && isset($_POST["email"]) && isset($_POST["cidade"]) && isset($_POST["uf"]))
{
	if(empty($_POST["nome"]))
		$erro = "Campo nome obrigatório";
	else
	if(empty($_POST["email"]))
		$erro = "Campo e-mail obrigatório";
	else
	{
				//Vamos realizar o cadastro ou alteração dos dados enviados.
        $nome   = $_POST["Nome"];
        $cep  = $_POST["Cep"];
        $logradouro = $_POST["Logradouro"];
        $complemento     = $_POST["Complemento"];
        $numero   = $_POST["Numero"];
        $bairro  = $_POST["Bairro"];
        $uf  = $_POST["Estado"];
        $cidade = $_POST["Cidade"];
        $data     = $_POST["Data"];
        
        $stmt = $obj_mysqli->prepare("INSERT INTO `locais` (`nome`,`cep`,`logradouro`,`complemento`,`numero`,`bairro`, `uf`, `cidade`,`data`) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('sssssssss', $nome, $cep, $logradouro, $complemento, $numero, $bairro, $uf, $cidade, $data);
        
        if(!$stmt->execute())
        {
          $erro = $stmt->error;
        }
        else
        {
          $sucesso = "Dados cadastrados com sucesso!";
        }
	}
}
?>

<html lang="pt-br">
  <head>
    <meta charset="utf-8"/>
    <title>CRUD com PHP, de forma simples e fácil</title>
  </head>
  <body>
  <?php
if(isset($erro))
	echo '<div style="color:#F00">'.$erro.'</div><br/><br/>';
else
if(isset($sucesso))
	echo '<div style="color:#00f">'.$sucesso.'</div><br/><br/>';
 
?>
	<form action="<?=$_SERVER["PHP_SELF"]?>" method="POST">
	  Nome do lugar:<br/> 
	  <input type="text" name="Nome" placeholder="Nome do lugar"><br/><br/>
	  Cep:<br/> 
	  <input type="text" name="Cep" placeholder="Cep"><br/><br/>
	  Logradouro:<br/> 
	  <input type="text" name="Logradouro" placeholder="Logradouro"><br/><br/>
	  Complemento:<br/> 
    <input type="text" name="Complemento" placeholder="Complemento"><br/><br/>
    Numero:<br/> 
    <input type="text" name="Numero" placeholder="Numero"><br/><br/>
    Bairro:<br/> 
    <input type="text" name="Bairro" placeholder="Bairro"><br/><br/>
    UF:<br/> 
    <input type="text" name="Estado" placeholder="Estado"><br/><br/>
    Cidade:<br/> 
    <input type="text" name="Cidade" placeholder="Cidade"><br/><br/>
    Data:<br/> 
	  <input type="text" name="Data" placeholder="Data"><br/><br/>
	  <br/>
	  <input type="hidden" value="-1" name="id" >
	  <button type="submit">Cadastrar</button>
	</form>
  </body>
</html>