<?php

test('strings can be manipulated', function () {
    $name = 'DiagnoSense';
    expect($name)->toBeString()->toStartWith('Diagno');
});
