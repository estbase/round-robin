<?php

namespace estbase\Roundrobin;

class Schedule
{
    /**
     * @param array $teams
     * @param int|null $rounds
     * @param bool $bye
     * @param bool $shuffle
     * @param int|null $seed
     * @return array
     * @throws \Exception
     */
    public static function createSchedule(array $teams, int $rounds = null, bool $bye = true, bool $shuffle = true, int $seed = null): array
    {
        $teamCount = count($teams);

        // Guard clause
        if($teamCount < 2) {
            return [];
        }

        if ($bye) {
            // In case of odd number of teams add or not a bye
            if($teamCount % 2 === 1) {
                array_push($teams, null);
                $teamCount += 1;
            }
        }

        if($shuffle) {
            // Seed shuffle with random_int for better randomness if seed is null, and mt_rand adding entropy and speed
            mt_srand($seed ?? random_int(PHP_INT_MIN, PHP_INT_MAX));
            shuffle($teams);
        } elseif(!is_null($seed)) {
            trigger_error('Seed parameter has no effect when shuffle parameter is set to false', E_USER_ERROR);
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
                // Home-away swapping
                $matchup = $round % 2 === 0 ? [$team1, $team2] : [$team2, $team1];
                $schedule[$round][] = $matchup;
            }
            self::rotate($teams);
        }

        return $schedule;
    }

    /**
     * @param array $items
     */
    private static function rotate(array &$items)
    {
        $itemCount = count($items);
        if($itemCount < 3) {
            return;
        }
        $lastIndex = $itemCount - 1;
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
