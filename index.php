<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Calculadora PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
        /* Estilo personalizado */
        /* Definir um fundo colorido para a área da calculadora */
        .calculadora-fundo {
            background-color: #f0f8ff; /* Cor de fundo (você pode ajustar conforme desejado) */
            padding: 20px; /* Padding para espaçamento interno */
            border-radius: 15px; /* Bordas arredondadas */
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
                    <input type="number" class="form-control" name="num1">
                    <span class="input-group-text ms-2">Operação</span>
                    <select class="form-select" name="op">
                        <option value="" selected>Operação</option>
                        <option value="+">+</option>
                        <option value="-">-</option>
                        <option value="*">*</option>
                        <option value="/">/</option>
                        <option value="^">^</option>
                        <option value="!">!</option>
                    </select>
                    <span class="input-group-text ms-2">Número 2</span>
                    <input type="number" class="form-control" name="num2">
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
                return $_SESSION['memoria'];
            }

            function limpar() {
                unset($_SESSION['memoria']);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Verifica a ação do usuário
                if (isset($_GET['action'])) {
                    if ($_GET['action'] === 'Apagar Histórico') {
                        apagar();
                        echo '<div class="alert alert-success mt-2">Histórico limpo</div>';
                    } elseif ($_GET['action'] === 'Limpar') {
                        limpar();
                        echo '<div class="alert alert-success mt-2">Memória limpa</div>';
                    } elseif ($_GET['action'] === 'Recuperar') {
                        if (isset($_SESSION['memoria'])) {
                            echo '<div class="alert alert-info mt-2">Valor na memória: ' . $_SESSION['memoria'] . '</div>';
                        } else {
                            echo '<div class="alert alert-warning mt-2">Nenhum valor armazenado na memória</div>';
                        }
                    }
                }
                
                // Verifica os valores de entrada
                if (isset($_GET['num1']) && isset($_GET['num2']) && isset($_GET['op'])) {
                    if ($_GET['num1'] === '' || $_GET['num2'] === '' || $_GET['op'] === '') {
                        echo '<div class="alert alert-danger mt-2">Erro: Todos os campos (Número 1, Operação, Número 2) devem ser preenchidos</div>';
                    } else {
                        $num1 = (float)$_GET['num1'];
                        $num2 = (float)$_GET['num2'];
                        $op = $_GET['op'];
                        $res = 0;

                        switch ($op) {
                            case '+':
                                $res = $num1 + $num2;
                                break;
                            case '-':
                                $res = $num1 - $num2;
                                break;
                            case '*':
                                $res = $num1 * $num2;
                                break;
                            case '/':
                                if ($num2 !== 0) {
                                    $res = $num1 / $num2;
                                } else {
                                    echo '<div class="alert alert-danger mt-2">Erro: Divisão por zero!</div>';
                                    exit;
                                }
                                break;
                            case '^':
                                $res = pow($num1, $num2);
                                break;
                            case '!':
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
                                $res = fatorial($num1);
                                break;
                            default:
                                echo '<div class="alert alert-danger mt-2">Erro: Operação inválida</div>';
                                exit;
                        }

                        $guardar = "$num1 $op $num2 = $res";
                        adicionar($guardar);

                        echo '<div class="alert alert-info mt-2">Resultado: ' . $res . '</div>';
                        echo '<div class="alert alert-info mt-2"><strong>Histórico:</strong></div>';
                        if (isset($_SESSION['historico'])) {
                            foreach ($_SESSION['historico'] as $operacao) {
                                echo '<div>' . $operacao . '</div>';
                            }
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
</body>

</html>
