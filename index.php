<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Calculadora PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        .calculadora-fundo {
            background-color: #f0f8ff;
            padding: 20px;
            border-radius: 15px;
        }
    </style>
</head>

<body>
    <div class="position-absolute top-50 start-50 translate-middle calculadora-fundo">
        <div class="text-bg-dark p-3 rounded mx-auto">
            <h1 class="display-6 text-center">Calculadora PHP</h1>
            <form action="" method="GET">
                <div class="input-group mx-auto p-2">
                    <span class="input-group-text">Número 1</span>
                    <input type="number" class="form-control" name="num1" id="num1">
                    <span class="input-group-text ms-2">Operação</span>
                    <select class="form-select" name="op" id="op">
                        <option value="" selected>Operação</option>
                        <option value="+">+</option>
                        <option value="-">-</option>
                        <option value="*">*</option>
                        <option value="/">/</option>
                        <option value="^">^</option>
                        <option value="!">!</option>
                    </select>
                    <span class="input-group-text ms-2">Número 2</span>
                    <input type="number" class="form-control" name="num2" id="num2">
                    <input type="submit" value="Calcular" class="btn btn-outline-success ms-2">
                </div>
                <div class="my-2">
                    <input type="submit" name="action" value="Salvar" class="btn btn-outline-warning">
                    <input type="submit" name="action" value="Recuperar" class="btn btn-outline-secondary">
                    <input type="submit" name="action" value="Limpar" class="btn btn-outline-info">
                    <input type="submit" name="action" value="Apagar Histórico" class="btn btn-outline-danger">
                </div>
            </form>

            <?php
            session_start();

            function adicionar($guardar) {
                if (!isset($_SESSION['historico'])) {
                    $_SESSION['historico'] = [];
                }
                $_SESSION['historico'][] = $guardar;
            }

            function apagar() {
                unset($_SESSION['historico']);
            }

            function salvar($save) {
                $_SESSION['memoria'] = $save;
            }

            function recuperar() {
                return isset($_SESSION['memoria']) ? $_SESSION['memoria'] : null;
            }

            function limpar() {
                unset($_SESSION['memoria']);
            }

            function fatorial($n) {
                if ($n < 0) {
                    return "Erro: Fatorial não definido para números negativos";
                }
                $resultado = 1;
                for ($i = 2; $i <= $n; $i++) {
                    $resultado *= $i;
                }
                return $resultado;
            }

            function calcular($num1, $num2, $op) {
                switch ($op) {
                    case '+':
                        return $num1 + $num2;
                    case '-':
                        return $num1 - $num2;
                    case '*':
                        return $num1 * $num2;
                    case '/':
                        if ($num2 !== 0) {
                            return $num1 / $num2;
                        } else {
                            return 'Erro: Divisão por zero!';
                        }
                    case '^':
                        return pow($num1, $num2);
                    case '!':
                        return fatorial($num1);
                    default:
                        return 'Erro: Operação inválida';
                }
            }

           

            function exibirErro($mensagem) {
                echo '<div class="alert alert-danger mt-2">' . $mensagem . '</div>';
            }

            function exibirAlerta($mensagem) {
                echo '<div class="alert alert-info mt-2">' . $mensagem . '</div>';
            }

            $num1 = $num2 = $op = '';

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (isset($_GET['action'])) {
                    if ($_GET['action'] === 'Apagar Histórico') {
                        apagar();
                        exibirAlerta('Histórico limpo');
                    } elseif ($_GET['action'] === 'Limpar') {
                        limpar();
                        exibirAlerta('Memória limpa');
                    } elseif ($_GET['action'] === 'Recuperar') {
                        $valor = recuperar();
                        if ($valor !== null) {
                            $num1 = $valor['num1'];
                            $op = $valor['op'];
                            $num2 = $valor['num2'];
                            exibirAlerta('Valores recuperados');
                        } else {
                            exibirAlerta('Nenhum valor armazenado na memória');
                        }
                    }
                }

                echo '<script>
                        document.getElementById("num1").value = "' . $num1 . '";
                        document.getElementById("op").value = "' . $op . '";
                        document.getElementById("num2").value = "' . $num2 . '";
                      </script>';

                      if (isset($_GET['num1']) && isset($_GET['num2']) && isset($_GET['op'])) {
                        $num1 = $_GET['num1'];
                        $num2 = $_GET['num2'];
                        $op = $_GET['op'];
                    
                        if ($op !== '!' && ($num1 === '' || $num2 === '')) {
                            exibirErro('Erro: Todos os campos (Número 1, Operação, Número 2) devem ser preenchidos');
                        } else {
                            if ($num1 !== '' && $num2 !== '') {
                                $num1 = (float)$num1;
                                $num2 = (float)$num2;
                            }
                    
                            $res = calcular($num1, $num2, $op);
                    
                            if (is_numeric($res)) {
                                $guardar = "$num1 $op $num2 = $res";
                                adicionar($guardar);
                                salvar(['num1' => $num1, 'op' => $op, 'num2' => $num2]);
                    
                                exibirAlerta('Resultado: ' . $res);
                                echo '<div class="alert alert-info mt-2"><strong>Histórico:</strong></div>';
                                if (isset($_SESSION['historico'])) {
                                    foreach ($_SESSION['historico'] as $operacao) {
                                        echo '<div>' . $operacao . '</div>';
                                    }
                                }
                            } else {
                                exibirErro($res);
                            }
                        }
                    }
                    
            }
            ?>
        </div>
    </div>
</body>

</html>
