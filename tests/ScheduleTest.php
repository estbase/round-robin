<?php

declare(strict_types=1);

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

        $this->assertEquals(28, $totalMatches);
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

        $this->assertEquals(60, $totalMatches);
    }

    public function testCreateScheduleWithOddTeamsAndBye()
    {
        $teams = ['A','B','C','D','E','F','G'];

        $schedule = Schedule::create($teams, null,true,true,2);

        $totalMatches = 0;
        foreach ($schedule as $round) {
            foreach ($round as $match) {
                $totalMatches++;
            }
        }

        $this->assertEquals(28, $totalMatches);
        $this->assertNull($schedule[1][2][1]);
        $this->assertEquals($schedule[1][2][0], 'D');
        $this->assertNull($schedule[5][1][0]);
    }

    public function testErrorWhenCreateScheduleWithEightTeamsAndSeedWithoutShuffle()
    {
        $teams = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        // Establece un manejador de errores personalizado para convertir el error en una excepción
        set_error_handler(function ($errno, $errstr) {
            if (error_reporting() === 0) {
                return false;
            }
            throw new \Exception($errstr);
        });

        try {
            $this->expectException(\Exception::class);
            $this->expectExceptionMessage('Seed parameter has no effect when shuffle parameter is set to false');
            Schedule::create($teams, null, true, false, 9);
        } finally {
            // Restaura el manejador de errores predeterminado
            restore_error_handler();
        }
    }
}
