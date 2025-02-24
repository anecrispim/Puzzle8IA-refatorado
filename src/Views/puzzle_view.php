<?php
// src/Views/puzzle_view.php

// Função para renderizar um estado do puzzle (3x3)
function renderPuzzleGrid(array $state) {
    echo '<table class="table table-bordered text-center" style="width:180px;">';
    for ($i = 0; $i < 3; $i++) {
        echo '<tr>';
        for ($j = 0; $j < 3; $j++) {
            $index = $i * 3 + $j;
            $value = ($state[$index] === 0) ? '' : $state[$index];
            echo "<td style='height:60px; vertical-align:middle; font-size:20px;'>$value</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
}

// Valores padrão ou vindos do controller
$stateInput   = $_GET['state'] ?? '1,2,3,4,5,6,7,8,0';
$algorithm    = $_GET['algorithm'] ?? 'astar1';
$solutionSteps = $solutionSteps ?? [];
$time          = $time ?? 0;
$visitedNodes  = $visitedNodes ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Puzzle8</title>
    <!-- Bootstrap CSS -->
    <link 
      rel="stylesheet" 
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Puzzle8</h1>
    
    <!-- Formulário -->
    <form method="GET" action="index.php">
        <input type="hidden" name="action" value="solve">
        
        <div class="form-group">
            <label for="state">Estado Inicial (separado por vírgulas)</label>
            <input 
              type="text" 
              class="form-control" 
              id="state" 
              name="state"
              value="<?= htmlspecialchars($stateInput) ?>">
        </div>
        
        <div class="form-group">
            <label for="algorithm">Resoluções por</label>
            <select class="form-control" id="algorithm" name="algorithm">
                <option value="astar1" <?= $algorithm === 'astar1' ? 'selected' : '' ?>>Busca A* (g + h1)</option>
                <option value="astar2" <?= $algorithm === 'astar2' ? 'selected' : '' ?>>Busca A* (g + h2)</option>
                <option value="horizontal" <?= $algorithm === 'horizontal' ? 'selected' : '' ?>>Busca Horizontal (BFS)</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Resolver</button>
    </form>

    <?php if (!empty($solutionSteps)): ?>
      <hr>
      <p>
        <strong>Solução encontrada em:</strong> <?= round($time, 6) ?> segundos<br>
        <strong>Quantidade de nós visitados:</strong> <?= $visitedNodes ?>
      </p>

      <!-- Exibe cada passo da solução -->
      <?php foreach ($solutionSteps as $index => $step): ?>
        <h5 class="mt-4">
          <?= ($index+1) . '. ' . htmlspecialchars($step['movimento']) ?>
        </h5>
        <?php renderPuzzleGrid($step['estado']); ?>
      <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Bootstrap JS + dependências -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script 
  src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
