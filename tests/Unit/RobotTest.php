<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Robot;

class RobotTest extends TestCase
{
    /**
     * @dataProvider getMoveData
     */
    public function testRobotMove($x, $y, $directions, $expectedX, $expectedY, $expectedException = null, $expectedExceptionMessage = null)
    {
        $robot = new Robot($x, $y);

        if ($expectedException) {
            $this->expectException($expectedException);
            $this->expectExceptionMessage($expectedExceptionMessage);
        }

        $position = $robot->move($directions);
        $this->assertSame($expectedX, $position[0]);
        $this->assertSame($expectedY, $position[1]);
    }

    public function getMoveData()
    {
        return [
            [0, 0, '', 0, 0, \InvalidArgumentException::class, 'Empty direction string'],
            [0, 0, 'D', 0, 0, \InvalidArgumentException::class, 'Invalid direction: D'],
            [0, 9, 'N', 0, 1, \OutOfBoundsException::class, 'Robot cannot move North (grid boundary reached)'],
            [0, 0, 'S', 0, 0, \OutOfBoundsException::class, 'Robot cannot move South (grid boundary reached)'],
            [9, 0, 'E', 1, 0, \OutOfBoundsException::class, 'Robot cannot move East (grid boundary reached)'],
            [0, 0, 'W', 0, 0, \OutOfBoundsException::class, 'Robot cannot move West (grid boundary reached)'],
            [0, 0, 'N', 0, 1],
            [0, 0, 'E', 1, 0],
            [5, 5, 'N', 5, 6],
            [5, 5, 'S', 5, 4],
            [5, 5, 'E', 6, 5],
            [5, 5, 'W', 4, 5],
            [0, 0, 'N E S W', 0, 0],
            [0, 0, 'N E N E N E N E', 4, 4],
        ];
    }

    public function testInvalidMove()
    {
        $robot = new Robot(0, 0);

        $this->expectException(\InvalidArgumentException::class);
        $robot->move('Invalid Direction');
    }

    public function testEmptyMove()
    {
        $robot = new Robot(0, 0);

        $this->expectException(\InvalidArgumentException::class);
        $robot->move('');
    }

    public function testExecuteCommands()
    {
        $robot = new Robot(0, 0);

        // Test case: No exception should be thrown
        $output = $robot->executeCommands('N');
        $this->assertStringContainsString('Command executed successfully. The robot is now at position (0, 1).', $output);

        // Test case: Expect InvalidArgumentException
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Empty command string');
        $robot->executeCommands('');

        // Test case: Invalid command
        $output = $robot->executeCommands('D');
        $this->assertStringContainsString("Invalid command: 'D'", $output);
        $this->assertStringContainsString("An error occurred during command execution. The robot is now at position (0, 1).", $output);

        // Test case: Expect OutOfBoundsException
        $output = $robot->executeCommands('S');
        $this->assertStringContainsString("An error occurred while executing the command 'S': Robot cannot move South (grid boundary reached)", $output);
        $this->assertStringContainsString("An error occurred during command execution. The robot is now at position (0, 1).", $output);
    }

    public function testResetPosition()
    {
        $robot = new Robot(0, 0);
        $robot->move('N E');
        $robot->resetPosition();

        $this->assertSame(0, $robot->getX());
        $this->assertSame(0, $robot->getY());
    }

}
