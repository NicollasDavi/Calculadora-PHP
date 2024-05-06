<?php
session_start();

// Função para adicionar uma operação ao histórico
function adicionarAoHistorico($operacao) {
    if (!isset($_SESSION['historico'])) {
        $_SESSION['historico'] = array();
    }
    array_push($_SESSION['historico'], $operacao);
}

// Função para limpar o histórico
function limparHistorico() {
    $_SESSION['historico'] = array();
}

// Função para salvar os valores dos campos em memória
function salvarValoresEmMemoria($num1, $num2, $operador) {
    $_SESSION['memoria'] = array('num1' => $num1, 'num2' => $num2, 'operador' => $operador);
}

// Função para recuperar os valores salvos em memória
function recuperarValoresDaMemoria() {
    return $_SESSION['memoria'];
}

// Função para calcular e retornar o resultado
function calcular($num1, $num2, $operador) {
    switch ($operador) {
        case '+':
            return $num1 + $num2;
        case '-':
            return $num1 - $num2;
        case '*':
            return $num1 * $num2;
        case '/':
            if ($num2 != 0) {
                return $num1 / $num2;
            } else {
                return "Erro: Divisão por zero!";
            }
        case '^':
            return pow($num1, $num2);
        case '!':
            return fatorial($num1);
        default:
            return "Erro: Operador inválido!";
    }
}

// Função para calcular o fatorial
function fatorial($n) {
    $resultado = 1;
    for ($i = 2; $i <= $n; $i++) {
        $resultado *= $i;
    }
    return $resultado;
}

// Verifica se a requisição é do tipo POST e realiza as operações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se é a ação de "Pegar Valor"
    if (isset($_POST['pegar_valor'])) {
        $valores = recuperarValoresDaMemoria();
        $_POST['num1'] = $valores['num1'];
        $_POST['num2'] = $valores['num2'];
        $_POST['operador'] = $valores['operador'];
    }
    
    // Verifica se é a ação de "Apagar Histórico"
    if (isset($_POST['apagar_historico'])) {
        limparHistorico();
    }
    
    // Obtém os valores dos campos do formulário
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $operador = $_POST['operador'];
    
    // Calcula o resultado
    $resultado = calcular($num1, $num2, $operador);
    
    // Adiciona a operação ao histórico
    $operacao = "$num1 $operador $num2 = $resultado";
    adicionarAoHistorico($operacao);
    
    // Salva os valores em memória
    salvarValoresEmMemoria($num1, $num2, $operador);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .calculator {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .display {
            margin-bottom: 10px;
        }

        .display input {
            width: calc(100% - 10px);
            margin-right: 5px;
            text-align: right;
        }

        .buttons {
            display: flex;
            gap: 5px;
        }

        .buttons button {
            width: calc(25% - 5px);
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .buttons button.operator {
            background-color: #f0ad4e;
        }

        .buttons button.calculate {
            background-color: #5cb85c;
        }

        .buttons button.memory {
            background-color: #5bc0de;
        }

        .buttons button.history {
            background-color: #d9534f;
        }

        .history {
            margin-top: 20px;
        }

        .history div {
            margin-bottom: 5px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
<div class="calculator">
    <!-- Visor -->
    <div class="display">
        <form method="POST" action="">
            <?php
            $valores = recuperarValoresDaMemoria();
            ?>
            <input type="number" id="num1" name="num1" placeholder="Número 1" value="<?php echo $valores['num1']; ?>" required>
            <input type="text" id="operador" name="operador" placeholder="Operador" value="<?php echo $valores['operador']; ?>" required>
            <input type="number" id="num2" name="num2" placeholder="Número 2" value="<?php echo $valores['num2']; ?>" required>
            <input type="submit" value="Calcular" class="calculate">
        </form>
    </div>
    
    <!-- Botões -->
    <div class="buttons">
        <form method="POST" action="">
            <input type="hidden" name="pegar_valor" value="true">
            <button class="memory">Pegar Valor</button>
        </form>
        <form method="POST" action="">
            <input type="hidden" name="apagar_historico" value="true">
            <button class="history">Apagar Histórico</button>
        </form>
    </div>
    
    <!-- Histórico de Operações -->
    <div class="history">
        <?php
        if(isset($_SESSION['historico'])) {
            foreach ($_SESSION['historico'] as $operacao) {
                echo '<div>' . $operacao . '</div>';
            }
        }
        ?>
    </div>
</div>
</body>
</html>
