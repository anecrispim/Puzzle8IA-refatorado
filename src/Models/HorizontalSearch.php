<?php
namespace Models;

class HorizontalSearch implements SearchAlgorithmInterface {
    private int $visitedNodes = 0;

    public function solve(Puzzle $puzzle): array {

        if ($puzzle->isGoal()) {
            return [[
                'movimento' => 'Puzzle já está resolvido!',
                'estado'    => $puzzle->getState()
            ]];
        }

        $startState = $puzzle->getState();
        $goalState  = $puzzle->getGoal();

        $queue = [];
        $visited = [];

        $startKey = implode(',', $startState);
        $queue[] = [
            'state' => $startState,
            'parent' => null,
            'move' => 'Início'
        ];
        $visited[$startKey] = true;

        while (!empty($queue)) {
            $current = array_shift($queue);
            $this->visitedNodes++;

            if ($current['state'] === $goalState) {
                return $this->reconstructPath($current);
            }

            $neighbors = $this->getNeighbors($current);
            foreach ($neighbors as $neighbor) {
                $neighborKey = implode(',', $neighbor['state']);
                if (!isset($visited[$neighborKey])) {
                    $visited[$neighborKey] = true;
                    $queue[] = $neighbor;
                }
            }
        }

        return [[
            'movimento' => 'Sem solução (BFS).',
            'estado'    => $startState
        ]];
    }

    private function getNeighbors(array $current): array {
        $state = $current['state'];
        $neighbors = [];
        
        $zeroPos = array_search(0, $state);
        $row = intdiv($zeroPos, 3);
        $col = $zeroPos % 3;

        $moves = [
            ['dr' => -1, 'dc' => 0, 'desc' => 'Mover para Cima'],
            ['dr' => 1,  'dc' => 0, 'desc' => 'Mover para Baixo'],
            ['dr' => 0,  'dc' => -1,'desc' => 'Mover para Esquerda'],
            ['dr' => 0,  'dc' => 1, 'desc' => 'Mover para Direita'],
        ];

        foreach ($moves as $move) {
            $newRow = $row + $move['dr'];
            $newCol = $col + $move['dc'];
            if ($newRow >= 0 && $newRow < 3 && $newCol >= 0 && $newCol < 3) {
                $newState = $state;
                $newPos = $newRow * 3 + $newCol;
                $newState[$zeroPos] = $newState[$newPos];
                $newState[$newPos]  = 0;

                $neighbors[] = [
                    'state' => $newState,
                    'parent' => $current,
                    'move' => $move['desc']
                ];
            }
        }

        return $neighbors;
    }

    private function reconstructPath(array $node): array {
        $path = [];
        while ($node !== null) {
            $path[] = [
                'movimento' => $node['move'],
                'estado'    => $node['state']
            ];
            $node = $node['parent'];
        }
        return array_reverse($path);
    }

    public function getVisitedNodesCount(): int {
        return $this->visitedNodes;
    }
}