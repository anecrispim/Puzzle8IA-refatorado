<?php
namespace Controllers;

use Models\Puzzle;
use Models\AStarSearchMode1;
use Models\AStarSearchMode2;
use Models\HorizontalSearch;

class PuzzleController {
    public function index() {
        require_once __DIR__ . '/../Views/puzzle_view.php';
    }

    public function solve() {
        $stateInput = $_GET['state'] ?? '1,2,3,4,5,6,7,8,0';
        $algorithm  = $_GET['algorithm'] ?? 'astar1';

        $stateArray = array_map('intval', explode(',', $stateInput));

        $puzzle = new Puzzle($stateArray);

        switch ($algorithm) {
            case 'astar2':
                $searchAlgorithm = new AStarSearchMode2();
                break;
            case 'horizontal':
                $searchAlgorithm = new HorizontalSearch();
                break;
            case 'astar1':
            default:
                $searchAlgorithm = new AStarSearchMode1();
                break;
        }

        $startTime = microtime(true);

        $solutionSteps = $searchAlgorithm->solve($puzzle);

        $endTime = microtime(true);
        $time = $endTime - $startTime;

        $visitedNodes = method_exists($searchAlgorithm, 'getVisitedNodesCount')
            ? $searchAlgorithm->getVisitedNodesCount()
            : 0;

        require_once __DIR__ . '/../Views/puzzle_view.php';
    }
}
