<?php

use ClintonRocha\Chat\Services\MessageXpCalculator;

it('calculates xp for empty message', function () {
    $calc = new MessageXpCalculator();
    expect($calc->calculate(''))->toBe(0);
});

it('calculates xp for short messages', function () {
    $calc = new MessageXpCalculator();
    expect($calc->calculate('hey'))->toBe(1)
        ->and($calc->calculate('hello!'))->toBe(2);
});

it('calculates xp for medium messages', function () {
    $calc = new MessageXpCalculator();
    expect($calc->calculate('this is a medium message'))->toBe(5);
});

it('calculates xp for longer messages', function () {
    $calc = new MessageXpCalculator();
    expect($calc->calculate(str_repeat('a', 30)))->toBe(5)
        ->and($calc->calculate(str_repeat('a', 100)))->toBe(10);
});
