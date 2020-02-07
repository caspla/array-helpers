<?php
include 'functions.php';

$data = [
    'uid' => 9675342,
    'errors' => [
        0 => ['code' => 1, 'message' => 'foo', 'x' => [['foo' => 'bar', 'bar' => 'foo']]],
        1 => ['code' => 2, 'message' => 'bar', 'x' => [['foo' => 'bar', 'bar' => 'foo']]],
        2 => ['code' => 3, 'message' => 'baz', 'x' => [['foo' => 'bar', 'bar' => 'foo']]],
    ]
];

echo '<pre>';

echo '<h2>$data:</h2>';
print_r($data);

echo '<h2>array_flatten($data):</h2>';
print_r(array_flatten($data));

echo '<h2>array_unflatten(array_flatten($data)):</h2>';
print_r(array_unflatten(array_flatten($data)));

echo '<h2>array_get($data, \'errors.*.x.*.bar\'):</h2>';
print_r(array_get($data, 'errors.*.x.*.bar'));

echo '<h2>array_omit($data, \'errors.*.x.*.bar\')</h2>';
print_r(array_omit($data, 'errors.*.x.*.bar'));

echo '<h2>array_keep($data, \'errors.*.x.*.foo\')</h2>';
print_r(array_keep($data, 'errors.*.x.*.foo'));

echo '<h2>array_set($data, \'errors.0.hello\', \'world\')</h2>';
array_set($data, 'errors.0.hello', 'world');
print_r($data);
echo '</pre>';