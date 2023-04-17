# Robot CLI

This project simulates a simple robot that can move within a 10x10 grid. The robot accepts basic commands (N, S, E, and W) to move north, south, east, or west, respectively.


## Assumptions Made
The robot is initialized at position (0,0) unless otherwise specified.
The grid size is 10x10 and cannot be changed.
The RobotCli.php file is located in the project's root directory.
The robot can only move in four directions: North, South, East, and West.
The robot cannot move diagonally.
The robot cannot move beyond the grid boundary.
The robot will return an error message if given an invalid command or direction.
The robot's position is saved in a file named "position.txt".
The "position.txt" file is located in the project's root directory.
The "position.txt" file is created if it does not exist.
The robot's position is reset to (0,0) after the "reset" command is executed.
The Robot class is designed to be used in a single-threaded environment.
## Requirements

- PHP 7.4 or higher

## Installation

1. Clone the repository or download the source code.
2. Navigate to the project directory in your terminal or command prompt.

## Usage

To run the robot CLI script, use the following command format:

php RobotCli.php "COMMAND"

or

php RobotCli.php RESET

### Examples:

Move the robot north and east: php RobotCli.php "N E"

Reset the robot position to (0, 0): php RobotCli.php RESET


## Commands

- N: Move the robot one step north
- S: Move the robot one step south
- E: Move the robot one step east
- W: Move the robot one step west
- RESET: Reset the robot position to (0, 0)

## Testing

To run the test suite, navigate to the project directory and execute:

php artisan test
