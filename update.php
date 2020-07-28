<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$nome = $cep = $logradouro = $complemento = $numero = $bairro = $uf = $cidade = $data = "";
$nome_err = $cep_err = $logradouro_err = $complemento_err = $numero_err = $bairro_err = $uf_err = $cidade_err = $data_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["nome"]);
    if(empty($input_name)){
        $name_err = "Por favor adicione um nome.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
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
    } elseif(!filter_var($input_numero, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[A-Za-z0-9]{1,6}$/")))){
        $numero_err = "O numero pode conter no máximo 6 digitos.";
    }else{
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
        // Prepare an update statement
        $sql = "UPDATE locais SET nome=?, cep=?, logradouro=?, complemento=?, numero=?, bairro=?, uf=?, cidade=?, data=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssi", $param_nome, $param_cep, $param_logradouro, $param_complemento, $param_numero, $param_bairro, $param_uf, $param_cidade, $param_data, $param_id);
            
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
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM locais WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $nome = $row["nome"];
                    $cep = $row["cep"];
                    $logradouro = $row["logradouro"];
                    $complemento = $row["complemento"];
                    $numero = $row["numero"];
                    $bairro = $row["bairro"];
                    $uf = $row["uf"];
                    $cidade = $row["cidade"];
                    $data = $row["data"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar local</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#uf").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#logradouro").val(dados.logradouro);
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
                        <h2>Modificar local</h2>
                    </div>
                    <p>Por favor atualize os dados sobre o local.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($nome_err)) ? 'has-error' : ''; ?>">
                            <label>Nome do lugar</label>
                            <input type="text" name="nome" class="form-control" value="<?php echo $nome; ?>">
                            <span class="help-block"><?php echo $nome_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($cep_err)) ? 'has-error' : ''; ?>">
                            <label>CEP</label>
                            <input type="text" name="cep" class="form-control" value="<?php echo $cep; ?>">
                            <span class="help-block"><?php echo $cep_err;?></span>
                        </div>
                        
                        <div class="form-group <?php echo (!empty($logradouro_err)) ? 'has-error' : ''; ?>">
                            <label>Logradouro</label>
                            <input type="text" name="logradouro" class="form-control" value="<?php echo $logradouro; ?>">
                            <span class="help-block"><?php echo $logradouro_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($complemento_err)) ? 'has-error' : ''; ?>">
                            <label>Complemento</label>
                            <input type="text" name="complemento" class="form-control" value="<?php echo $complemento; ?>">
                            <span class="help-block"><?php echo $complemento_err;?></span>
                        </div>                      
                        <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                            <label>Numero</label>
                            <input type="text" name="numero" class="form-control" value="<?php echo $numero; ?>">
                            <span class="help-block"><?php echo $numero_err;?></span>
                        </div>                       
                        <div class="form-group <?php echo (!empty($bairro_err)) ? 'has-error' : ''; ?>">
                            <label>Bairro</label>
                            <input type="text" name="bairro" class="form-control" value="<?php echo $bairro; ?>">
                            <span class="help-block"><?php echo $bairro_err;?></span>
                        </div>                      
                        <div class="form-group <?php echo (!empty($uf_err)) ? 'has-error' : ''; ?>">
                            <label>Estado (Formato reduzido (RJ/SP/ES/MG etc)</label>
                            <input type="text" name="uf" class="form-control" value="<?php echo $uf; ?>">
                            <span class="help-block"><?php echo $uf_err;?></span>
                        </div>                       
                        <div class="form-group <?php echo (!empty($cidade_err)) ? 'has-error' : ''; ?>">
                            <label>Cidade</label>
                            <input type="text" name="cidade" class="form-control" value="<?php echo $cidade; ?>">
                            <span class="help-block"><?php echo $cidade_err;?></span>
                        </div>                       
                        <div class="form-group <?php echo (!empty($data_err)) ? 'has-error' : ''; ?>">
                            <label>Data Formato YYYY-MM-DD (ano-mes-dia)</label>
                            <input type="text" name="data" class="form-control" value="<?php echo $data; ?>">
                            <span class="help-block"><?php echo $data_err;?></span>
                        </div>                     
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Modificar">
                        <a href="index.php" class="btn btn-default">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>