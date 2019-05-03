<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use estbase\Roundrobin\Schedule;

class ScheduleTest extends TestCase
{

    public function testCreateScheduleWithTwoTeams()
    {
        $teams = ['A','B'];
        $schedule = Schedule::create($teams);

        $estimatedResult1 = [1=>[['A','B']]];
        $estimatedResult2 = [1=>[['B','A']]];

        $this->assertThat($schedule, $this->logicalOr($estimatedResult1, $estimatedResult2));
    }

    public function testCreateScheduleWithEightTeams()
    {
        $teams = ['A','B','C','D','E','F','G','H'];
        $schedule = Schedule::create($teams);

        $totalMatches = 0;
        foreach ($schedule as $round) {
            foreach ($round as $match) {
                $totalMatches++;
            }
        }

        $this->assertEquals($totalMatches, 28);
    }

    public function testCreateScheduleWithEightTeamsAndDoubleRound()
    {
        $teams = ['A','B','C','D','E','F','G','H'];
        $totalTeams = (count($teams)*2)-1;

        $schedule = Schedule::create($teams, $totalTeams);

        $totalMatches = 0;
        foreach ($schedule as $round) {
            foreach ($round as $match) {
                $totalMatches++;
            }
        }

        $this->assertEquals($totalMatches, 60);
    }

    public function testErrorWhenCreateScheduleWithEightTeamsAndSeedWithoutShuffle()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $teams = ['A','B','C','D','E','F','G','H'];

        $this->expectException('Seed parameter has no effect when shuffle parameter is set to false');

        $schedule = Schedule::create($teams, null, true, false, 9);
    }
}
