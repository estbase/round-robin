<?php

namespace Roundrobin\Domain;

interface SchedulerInterface
{
    public function createSchedule(array $teams, int $rounds = null, bool $shuffle = true, int $seed = null): array;

    public function calculateMatches(int $totalOfTeams): int;

    public function rotate(array &$items);
}