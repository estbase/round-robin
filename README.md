<p align="center"><img width="250" src="https://i.imgur.com/q54g9NC.png"></p>

<p align="center">
<a href="https://travis-ci.com/estbase/round-robin"><img alt="Travis (.org)" src="https://api.travis-ci.com/estbase/round-robin.svg?branch=master" alt="Continue Integration"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-green.svg" alt="License"></a>
</p>

# EST Base Packages
- RoundRobin Schedule Generator
- TBC

## About Round-Robin EST Package

This package will generate a tournament or seasonal calendar instantly, through the round-robin system.

Based on code of: https://github.com/mnito/round-robin

## Features
- Schedule generation by Round-robin system
- Support for any number of teams (Indicated for no more than 12 or 14 teams)
- Ability to generate a number of rounds on demand
- Ability to configure add a bye for odd-numbered team counts
- PHP 7.1
- PHPUnit tested

## Installation
If you use Composer, run on your terminal:
```sh
composer require estbase/round-robin
```

in other cases add the following line on required packages:

```json
"estbase/round-robin": "^1.0"
```

Ready to use!

## Usage

#### Short call, generating a schedule where each player meets every other player once:
```php
$schedule = Schedule::create(['A','B','C','D']);
```

or

```php
$teams = ['A','B','C','D'];
$schedule = Schedule::create($teams);
```

#### Generate schedule with personalized number of rounds or on each team plays with other team twice:
```php
$teams = ['A','B','C','D'];
$schedule = Schedule::create($teams, 5);
```

or

```php
$teams = ['A','B','C','D'];
$rounds = (($count = count($teams)) % 2 === 0 ? $count - 1 : $count) * 2;
$schedule = Schedule::create($teams, $rounds);
```

#### Generate schedule with or without adding a bye for an odd-numbered tournaments:
This case generates a Schedule adding a bye.
```php
$teams = ['A','B','C','D','E'];
$schedule = Schedule::create($teams);
```

or without it

```php
$teams = ['A','B','C','D','E'];
$schedule = Schedule::create($teams, null, false);
```

#### Generate a schedule without randomly shuffling the teams:
```php
$schedule = Schedule::createSchedule(['A','B','C','D'],null,true, false);
```

#### Using your own seed to generate the schedule with predetermined shuffling:
```php
$schedule = Schedule::createSchedule(['A','B','C','D'],null,true, true, 9);
```

## License
EST Base round-robin package is free software distributed under the terms of the MIT license.