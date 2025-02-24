<?php
// src/Models/SearchAlgorithmInterface.php

namespace Models;

interface SearchAlgorithmInterface
{
    /**
     * Retorna um array com o passo a passo da solução.
     * Cada elemento do array deve conter:
     *   - 'movimento' => descrição do passo
     *   - 'estado'    => array com o estado do puzzle (3x3) após o movimento
     *
     * @param Puzzle $puzzle
     * @return array
     */
    public function solve(Puzzle $puzzle): array;
}
