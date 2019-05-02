<?php

namespace Roundrobin\Domain;

use Roundrobin\Domain\SchedulerInterface;

class Schedule implements SchedulerInterface
{

    public function createSchedule(array $teams, int $rounds = null, bool $shuffle = true, int $seed = null): array
    {
        $teamCount = count($teams);
        if($teamCount < 2) {
            return [];
        }
        //Account for odd number of teams by adding a bye
        if($teamCount % 2 === 1) {
            array_push($teams, null);
            $teamCount += 1;
        }
        if($shuffle) {
            //Seed shuffle with random_int for better randomness if seed is null
            srand($seed ?? random_int(PHP_INT_MIN, PHP_INT_MAX));
            shuffle($teams);
        } elseif(!is_null($seed)) {
            //Generate friendly notice that seed is set but shuffle is set to false
            trigger_error('Seed parameter has no effect when shuffle parameter is set to false');
        }
        $halfTeamCount = $teamCount / 2;
        if($rounds === null) {
            $rounds = $teamCount - 1;
        }
        $schedule = [];
        for($round = 1; $round <= $rounds; $round += 1) {
            foreach($teams as $key => $team) {
                if($key >= $halfTeamCount) {
                    break;
                }
                $team1 = $team;
                $team2 = $teams[$key + $halfTeamCount];
                //Home-away swapping
                $matchup = $round % 2 === 0 ? [$team1, $team2] : [$team2, $team1];
                $schedule[$round][] = $matchup;
            }
            $this->rotate($teams);
        }
        return $schedule;

    }

    public function calculateMatches(int $totalTeams): int
    {
        return $totalTeams*($totalTeams-1)/2;
    }

    function rotate(array &$items)
    {
        $itemCount = count($items);
        if($itemCount < 3) {
            return;
        }
        $lastIndex = $itemCount - 1;
        /**
         * Though not technically part of the round-robin algorithm, odd-even
         * factor differentiation included to have intuitive behavior for arrays
         * with an odd number of elements
         */
        $factor = (int) ($itemCount % 2 === 0 ? $itemCount / 2 : ($itemCount / 2) + 1);
        $topRightIndex = $factor - 1;
        $topRightItem = $items[$topRightIndex];
        $bottomLeftIndex = $factor;
        $bottomLeftItem = $items[$bottomLeftIndex];
        for($i = $topRightIndex; $i > 0; $i -= 1) {
            $items[$i] = $items[$i - 1];
        }
        for($i = $bottomLeftIndex; $i < $lastIndex; $i += 1) {
            $items[$i] = $items[$i + 1];
        }
        $items[1] = $bottomLeftItem;
        $items[$lastIndex] = $topRightItem;
    }


}
