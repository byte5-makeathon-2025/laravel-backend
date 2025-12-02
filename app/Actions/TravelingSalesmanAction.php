<?php

namespace App\Actions;

use Location\Coordinate;
use Location\Distance\Vincenty;

class GeocodeAddressAction {
    public function execute(array $coordinates): array
    {
        $matrix = $this->buildDistanceMatrix($coordinates);
        return $this->solveTsp($matrix);
    }

    private function buildDistanceMatrix(array $coordinates): array
    {
        $calculator = new Vincenty();
        $matrix = [];

        foreach ($coordinates as $i => $a) {
            foreach ($coordinates as $j => $b) {
                $matrix[$i][$j] = ($i === $j) ? 0 : $calculator->getDistance($a, $b);
            }
        }

        return $matrix;
    }

    /**
     * Brute Force TSP-Solver – für 10 Punkte gerade noch machbar
     */
    private function solveTsp(array $matrix): array
    {
        $n = count($matrix);
        $nodes = range(1, $n - 1); // Start bei 0 fixieren
        $bestRoute = [];
        $bestDistance = PHP_INT_MAX;

        foreach ($this->permute($nodes) as $perm) {
            $route = array_merge([0], $perm, [0]); // Start und Ende bei Punkt 0
            $distance = 0;
            for ($i = 0; $i < count($route) - 1; $i++) {
                $distance += $matrix[$route[$i]][$route[$i+1]];
            }
            if ($distance < $bestDistance) {
                $bestDistance = $distance;
                $bestRoute = $route;
            }
        }

        return $bestRoute;
    }

    private function permute(array $items): \Generator
    {
        if (empty($items)) {
            yield [];
        } else {
            foreach ($items as $i => $item) {
                $rest = $items;
                unset($rest[$i]);
                foreach ($this->permute($rest) as $perm) {
                    yield array_merge([$item], $perm);
                }
            }
        }
    }
}