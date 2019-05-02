<?php

namespace Tests\Rroundrobin;

use PHPUnit\Framework\TestCase;
use Roundrobin\Domain\Schedule;

class ScheduleTest extends TestCase
{

    public function testCreateScheduleWithTwoTeams()
    {
        $teams = ['A','B'];
        $schedule = (new Schedule())->createSchedule($teams);

        $estimatedResult1 = [1=>[['A','B']]];
        $estimatedResult2 = [1=>[['B','A']]];

        $this->assertThat($schedule, $this->logicalOr($estimatedResult1, $estimatedResult2));
    }

    public function testCreateScheduleWithEightTeams()
    {
        $teams = ['A','B','C','D','E','F','G'];
        $schedule = (new Schedule())->createSchedule($teams);

        $totalMatches = 0;
        foreach ($schedule as $round) {
            foreach ($round as $match) {
                $totalMatches++;
            }
        }
print_r($schedule);
        $this->assertEquals($totalMatches, 21);
    }

    public function testCalculateRoundFunctionRunsOk()
    {
        $rounds = (new Schedule())->calculateMatches(8);
        $this->assertEquals(28,$rounds);

        $rounds = (new Schedule())->calculateMatches(7);
        $this->assertEquals(21,$rounds);

        $rounds = (new Schedule())->calculateMatches(2);
        $this->assertEquals(1,$rounds);

        $rounds = (new Schedule())->calculateMatches(30);
        $this->assertEquals(435,$rounds);
    }
}
