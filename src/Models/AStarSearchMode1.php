<?php
namespace Models;

class AStarSearchMode1 implements SearchAlgorithmInterface {
    private int $visitedNodes = 0;

    public function solve(Puzzle $puzzle): array {
        $start = $puzzle->getState();
        $goal  = $puzzle->getGoal();

        if ($start === $goal) {
            return [[
                'movimento' => 'Puzzle já está resolvido!',
                'estado'    => $start
            ]];
        }

        $startKey = implode(',', $start);
        
        $open = new \SplPriorityQueue();
        $open->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);

        $closed = [];

        $startNode = [
            'state'  => $start,
            'g'      => 0,
            'h'      => $this->manhattan($start, $goal),
            'f'      => 0 + $this->manhattan($start, $goal),
            'parent' => null,
            'move'   => 'Início'
        ];

        $open->insert($startNode, -$startNode['f']);
        $closed[$startKey] = 0;

        while (!$open->isEmpty()) {
            $current = $open->extract();
            $node = $current['data'];
            $this->visitedNodes++;

            if ($node['state'] === $goal) {
                return $this->reconstructPath($node);
            }

            $neighbors = $this->getNeighbors($node, $goal);
            foreach ($neighbors as $n) {
                $nKey = implode(',', $n['state']);
                if (!isset($closed[$nKey]) || $n['g'] < $closed[$nKey]) {
                    $closed[$nKey] = $n['g'];
                    $open->insert($n, -$n['f']);
                }
            }
        }

        return [[
            'movimento' => 'Sem solução (A* Modo 1).',
            'estado'    => $start
        ]];
    }

    private function getNeighbors(array $node, array $goal): array {
        $neighbors = [];
        $state = $node['state'];

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

                $g = $node['g'] + 1;
                $h = $this->manhattan($newState, $goal);
                $f = $g + $h;

                $neighbors[] = [
                    'state'  => $newState,
                    'g'      => $g,
                    'h'      => $h,
                    'f'      => $f,
                    'parent' => $node,
                    'move'   => $move['desc']
                ];
            }
        }
        return $neighbors;
    }

    private function manhattan(array $current, array $goal): int {
        $dist = 0;
        for ($i = 0; $i < 9; $i++) {
            $val = $current[$i];
            if ($val !== 0) {
                $goalIndex = array_search($val, $goal);
                $dist += abs(intdiv($i,3) - intdiv($goalIndex,3)) 
                       + abs(($i % 3) - ($goalIndex % 3));
            }
        }
        return $dist;
    }

    function reconstructPath(array $node): array {
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
