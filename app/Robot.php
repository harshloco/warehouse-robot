<?php

namespace App;

class Robot
{
    // Since we have 10*10 grid, we start position at 0 and max is 9
    const MAX_GRID_SIZE = 9;
    const NORTH = 'North';
    private array $position;

    public function __construct($x=0, $y=0)
    {
        $this->position = [];
        $this->position["$x,$y"] = 1;
    }

    public function move(string $directions): ?array
    {
        // Save the original position
        list($originalX, $originalY) = $this->getPosition();

        // Sanitize input
        $directions = trim($directions);
        if (empty($directions)) {
            $this->setPosition(0, 0);
            throw new \InvalidArgumentException('Empty direction string');
        }

        // Split directions into an array
        $directionsArray = explode(' ', $directions);

        // Process each direction
        foreach ($directionsArray as $direction) {
            $direction = strtoupper($direction);
            if (!in_array($direction, ['N', 'S', 'E', 'W'])) {
                $this->setPosition(0, 0);
                throw new \InvalidArgumentException('Invalid direction: ' . $direction);
            }

            list($x, $y) = $this->getPosition();

            switch ($direction) {
                case 'N':
                    if ($y === self::MAX_GRID_SIZE) {
                        $this->setPosition(0, 0);
                        throw new \OutOfBoundsException($this->getOutOfBoundsMessage('North'));
                    }
                    $y++;
                    break;
                case 'S':
                    if ($y === 0) {
                        $this->setPosition(0, 0);
                        throw new \OutOfBoundsException($this->getOutOfBoundsMessage('South'));
                    }
                    $y--;
                    break;
                case 'E':
                    if ($x === self::MAX_GRID_SIZE) {
                        $this->setPosition(0, 0);
                        throw new \OutOfBoundsException($this->getOutOfBoundsMessage('East'));
                    }
                    $x++;
                    break;
                case 'W':
                    if ($x === 0) {
                        $this->setPosition(0, 0);
                        throw new \OutOfBoundsException($this->getOutOfBoundsMessage('West'));
                    }
                    $x--;
                    break;
            }
            $this->setPosition($x, $y);
        }

        // If no errors occurred, return the current position
        return $this->getPosition();
    }

    /**
     * @param string $commands
     * @return void
     */
    public function executeCommands(string $command): string
    {
        // Sanitize input
        $command = trim($command);
        if (empty($command)) {
            throw new \InvalidArgumentException('Empty command string');
        }

        $errorsOccurred = false;
        $output = '';

        // Sanitize and validate the command
        if (!$this->validateDirections($command)) {
            $output .= "Invalid command: '{$command}'\n";
            $errorsOccurred = true;
        } else {
            // Execute the command
            try {
                $this->move($command);
            } catch (\Exception $e) {
                $output .= "An error occurred while executing the command '{$command}': " . $e->getMessage() . "\n";
                $errorsOccurred = true;
            }
        }

        if (!$errorsOccurred) {
            $output .= "Command executed successfully. The robot is now at position (" . $this->getX() . ", " . $this->getY() . ").\n";
        } else {
            $output .= "An error occurred during command execution. The robot is now at position (" . $this->getX() . ", " . $this->getY() . ").\n";
        }

        return $output;
    }

    /**
     * @param string $directions
     * @return bool
     */
    private function validateDirections(string $directions): bool
    {
        // Split directions into an array
        $directionsArray = explode(' ', $directions);

        // Check each direction
        foreach ($directionsArray as $direction) {
            $direction = strtoupper($direction);
            if (!in_array($direction, ['N', 'S', 'E', 'W'])) {
                return false;
            }
        }

        return true;
    }

    public function getX()
    {
        list($x, $y) = $this->getPosition();
        return $x;
    }

    public function getY()
    {
        list($x, $y) = $this->getPosition();
        return $y;
    }

    private function getPosition()
    {
        foreach ($this->position as $key => $value) {
            if ($value === 1) {
                return array_map('intval', explode(',', $key));
            }
        }
    }

    /**
     * @param $x
     * @param $y
     * @return void
     */
    private function setPosition($x, $y): void
    {
        $this->position = [];
        $this->position["$x,$y"] = 1;
    }

    /**
     * @param string $direction
     * @return string
     */
    private function getOutOfBoundsMessage(string $direction): string
    {

        return "Robot cannot move {$direction} (grid boundary reached)";
    }

    public function resetPosition(): void
    {
        $this->setPosition(0, 0);
    }
}
