<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use App\Robot;

class RobotCliTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (file_exists('position.txt')) {
            unlink('position.txt');
        }
    }

    protected function tearDown(): void
    {
        if (file_exists('position.txt')) {
            unlink('position.txt');
        }
        parent::tearDown();
    }

    public function testRobotCliIntegration()
    {
        $this->assertFileDoesNotExist('position.txt');

        $output = shell_exec('php RobotCli.php "N E N E"');
        $this->assertStringContainsString('Command executed successfully. The robot is now at position (2, 2).', $output);
        $this->assertFileExists('position.txt');
        $this->assertSame("2,2", file_get_contents('position.txt'));

        $output = shell_exec('php RobotCli.php "S W S W"');
        $this->assertStringContainsString('Command executed successfully. The robot is now at position (0, 0).', $output);
        $this->assertFileExists('position.txt');
        $this->assertSame("0,0", file_get_contents('position.txt'));

        $output = shell_exec('php RobotCli.php "N N N N N N N N N N N N N"');
        $this->assertStringContainsString('An error occurred while executing the command', $output);
        $this->assertStringContainsString('Robot cannot move North (grid boundary reached)', $output);
        $this->assertFileExists('position.txt');
        $this->assertSame("0,0", file_get_contents('position.txt'));
    }
}
