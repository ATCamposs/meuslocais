<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <style type="text/css">
        .wrapper{
            width: 650px;
            margin: 0 auto;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Meus Locais</h2>
                        <a href="create.php" class="btn btn-primary pull-right">Criar novo local</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM locais";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Nome do local</th>";
                                        echo "<th>Data de visita</th>";
                                        echo "<th>Modificar / Deletar</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    if($row['uf'] == 'MG') {
                                        echo "<td class='minasGerais'>" . $row['nome'] . "</td>";
                                        echo "<td class='minasGerais'>" . $row['data'] . "</td>";
                                    }
                                    else if ($row['uf']!='[MG]') {
                                    echo "<td>" . $row['nome'] . "</td>";
                                    echo "<td>" . $row['data'] . "</td>";
                                    }
                                        if($row['uf'] == 'MG') {
                                            echo "<td class='minasGerais'>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Modificar Valor' data-toggle='tooltip'>Modificar</span></a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Deletar Valor' data-toggle='tooltip'>Deletar</span></a>";
                                        }
                                        else if ($row['uf']!='[MG]') {
                                            echo "<td>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Modificar Valor' data-toggle='tooltip'>Modificar</span></a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Deletar Valor' data-toggle='tooltip'>Deletar</span></a>";
                                        }
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>Nenhum registro foi encontrado.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>