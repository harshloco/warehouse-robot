#!/usr/bin/env php
<?php

require_once 'app/Robot.php';

use app\Robot;

function printUsage(): void
{
    echo "Usage: php RobotCli.php \"COMMAND\"\n";
    echo "       php RobotCli.php RESET\n";
}

if ($argc !== 2) {
    printUsage();
    exit(1);
}

$command = $argv[1];

// Function to read the robot's position from a file
function readPositionFromFile(): array
{
    if (!file_exists('position.txt')) {
        return [0, 0];
    }

    $position = file_get_contents('position.txt');
    return array_map('intval', explode(',', $position));
}

// Function to save the robot's position to a file
function savePositionToFile(int $x, int $y): void
{
    file_put_contents('position.txt', "{$x},{$y}");
}

// Read the current position from the file
list($x, $y) = readPositionFromFile();
$robot = new Robot($x, $y);


if (strtoupper($command) === 'RESET') {
    $robot->resetPosition();
    echo "Robot position reset to (0, 0).\n";
} else {
    echo $robot->executeCommands($command);
}

// Save the new position to the file
savePositionToFile($robot->getX(), $robot->getY());
