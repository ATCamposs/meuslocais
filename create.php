<?php
// Include config file
require __DIR__ . '/vendor/autoload.php';
require_once "config.php";
use Jarouche\ViaCEP\HelperViaCep;
$result = HelperViaCep::getBuscaViaCEP('Json', '01311300');
// Define variables and initialize with empty values
$nome = $cep = $logradouro = $complemento = $numero = $bairro = $uf = $cidade = $data = "";
$nome_err = $cep_err = $logradouro_err = $complemento_err = $numero_err = $bairro_err = $uf_err = $cidade_err = $data_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["nome"]);
    if(empty($input_name)){
        $name_err = "Por favor adicione um nome.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]/")))){
        $name_err = "Por favor entre com um nome válido.";
    } else{
        $nome = $input_name;
    }

    //validate cep
    $input_cep = trim($_POST["cep"]);
    if(empty($input_cep)){
        $cep_err = "Adicione o cep.";
    } elseif(!filter_var($input_cep, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/\d{8}/")))){
        $cep_err = "O Cep deve conter somente numeros.";
    } else{
        $cep = $input_cep;
    }
    
    // Validate address
    $input_logradouro = trim($_POST["logradouro"]);
    if(empty($input_logradouro)){
        $logradouro_err = "Adicione o logradouro.";     
    } else{
        $logradouro = $input_logradouro;
    }

    // Validate complement
    $complemento = trim($_POST["complemento"]);
 
    //validate cep
    $input_numero = trim($_POST["numero"]);
    if(empty($input_numero)){
        $numero_err = "Adicione o numero.";
    } elseif(!filter_var($input_numero, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9]{1,6}$/")))){
        $numero_err = "O numero deve conter somente numeros.";
    } else{
        $numero = $input_numero;
    }

    // Validate bairro
    $input_bairro = trim($_POST["bairro"]);
    if(empty($input_bairro)){
        $bairro_err = "Adicione o bairro.";     
    } else{
        $bairro = $input_bairro;
    }

    //validate UF
    $input_uf = trim($_POST["uf"]);
    if(empty($input_uf)){
        $uf_err = "Adicione o Estado.";
    } elseif(!filter_var($input_uf, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[A-Za-z]{2}$/")))){
        $uf_err = "O uf deve conter somente 2 letras (SP, MG, RJ).";
    } else{
        $uf = $input_uf;
    }  

    // Validate cidade
    $input_cidade = trim($_POST["cidade"]);
    if(empty($input_cidade)){
        $cidade_err = "Adicione a cidade.";     
    } else{
        $cidade = $input_cidade;
    }
    
    //validate Data
    $input_data = trim($_POST["data"]);
    if(empty($input_data)){
        $data_err = "Adicione a data.";
    } elseif(!filter_var($input_data, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/")))){
        $data_err = "A data deve estar no formato YYYY-MM-DD";
    } else{
        $data = $input_data;
    }  
    
    // Check input errors before inserting in database
    if(empty($nome_err) && empty($cep_err) && empty($logradouro_err) && empty($complemento_err) && empty($numero_err) && empty($bairro_err) && empty($uf_err) && empty($cidade_err) && empty($data_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO locais (nome, cep, logradouro, complemento, numero, bairro, uf, cidade, data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssss", $param_nome, $param_cep, $param_logradouro, $param_complemento, $param_numero, $param_bairro, $param_uf, $param_cidade, $param_data);
            
            // Set parameters
            $param_nome = $nome;
            $param_cep = $cep;
            $param_logradouro = $logradouro;
            $param_complemento = $complemento;
            $param_numero = $numero;
            $param_bairro = $bairro;
            $param_uf = $uf;
            $param_cidade = $cidade;
            $param_data = $data;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Crie um novo local</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <!-- Adicionando JQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
            crossorigin="anonymous"></script>

    <!-- Adicionando Javascript -->
    <script type="text/javascript">
        $(document).ready(function() {
            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#logradouro").val("");
                $("#bairro").val("");
                $("#cidade").val("");
                $("#uf").val("");
            }
            
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {
                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                if (cep === "") {
                    alert("Favor informar o CEP para consulta.");
                }
                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {
                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#logradouro").val("...");
                        $("#complemento").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#uf").val("...");

                        //Consulta o webservice viacep.com.br/
//                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                        $.getJSON("teste.php?cep="+ cep, function(dados) {
                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#logradouro").val(dados.logradouro);
                                $("#complemento").val(dados.complemento);
                                $("#bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#uf").val(dados.uf);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });

    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Criar local</h2>
                    </div>
                    <p>Por favor preencha todos os campos ou o furmulário não será validado.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                            <label>Nome do lugar</label>
                            <input type="text" name="nome" id="nome" class="form-control" value="<?php echo $nome; ?>">
                            <span class="help-block"><?php echo $nome_err;?></span>
                        </div>                       
                        <div class="form-group <?php echo (!empty($cep_err)) ? 'has-error' : ''; ?>">
                            <label>CEP</label>
                            <input type="text" name="cep" id="cep" class="form-control" value="<?php echo $cep; ?>">
                            <span class="help-block"><?php echo $cep_err;?></span>
                        </div>                    
                        <div class="form-group <?php echo (!empty($logradouro_err)) ? 'has-error' : ''; ?>">
                            <label>Logradouro</label>
                            <input type="text" name="logradouro" id="logradouro" class="form-control" value="<?php echo $logradouro; ?>">
                            <span class="help-block"><?php echo $logradouro_err;?></span>
                        </div>                        
                        <div class="form-group <?php echo (!empty($complemento_err)) ? 'has-error' : ''; ?>">
                            <label>Complemento</label>
                            <input type="text" name="complemento" id="complemento" class="form-control" value="<?php echo $complemento; ?>">
                            <span class="help-block"><?php echo $complemento_err;?></span>
                        </div>                        
                        <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                            <label>Numero</label>
                            <input type="text" name="numero" id="numero" class="form-control" value="<?php echo $numero; ?>">
                            <span class="help-block"><?php echo $numero_err;?></span>
                        </div>                       
                        <div class="form-group <?php echo (!empty($bairro_err)) ? 'has-error' : ''; ?>">
                            <label>Bairro</label>
                            <input type="text" name="bairro" id="bairro" class="form-control" value="<?php echo $bairro; ?>">
                            <span class="help-block"><?php echo $bairro_err;?></span>
                        </div>                       
                        <div class="form-group <?php echo (!empty($uf_err)) ? 'has-error' : ''; ?>">
                            <label>Estado (Formato reduzido (RJ/SP/ES/MG etc)</label>
                            <input type="text" name="uf" id="uf" class="form-control" value="<?php echo $uf; ?>">
                            <span class="help-block"><?php echo $uf_err;?></span>
                        </div>                       
                        <div class="form-group <?php echo (!empty($cidade_err)) ? 'has-error' : ''; ?>">
                            <label>Cidade</label>
                            <input type="text" name="cidade" id="cidade" class="form-control" value="<?php echo $cidade; ?>">
                            <span class="help-block"><?php echo $cidade_err;?></span>
                        </div>                       
                        <div class="form-group <?php echo (!empty($data_err)) ? 'has-error' : ''; ?>">
                            <label>Data Formato YYYY-MM-DD (ano-mes-dia)</label>
                            <input type="text" name="data" id="data" class="form-control" value="<?php echo $data; ?>">
                            <span class="help-block"><?php echo $data_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>

