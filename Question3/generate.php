<?php

require __DIR__ . '/vendor/autoload.php';

require ('config.php');
require ('prefixes.php');

$faker = Faker\Factory::create();

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

try
{
    $pdo = new PDO($dsn, $user, $password);

    if ($pdo)
    {
        echo "Connected to the $db database successfully!";

        for ($i = 0; $i < $insertRecords; $i++)
        {
            $prefix = array_rand($prefixes, 1);
            $phoneNumber = $prefix.$faker->randomNumber(10 - strlen($prefix),true);

            $sql = 'INSERT INTO prefix_map(prefix,destination) VALUES(:prefix,:destination)';
            $statement = $pdo->prepare($sql);
            $statement->execute([':prefix' => $phoneNumber,':destination' => $faker->sentence($nbWords = 6, $variableNbWords = true)]);
        }
    }
}
catch (PDOException $e)
{
    echo $e->getMessage();
}

// select * from prefix_map where prefix = '0899976098'; 1.06 seconds
// ALTER TABLE `prefix_map` ADD INDEX `prefix_map_prefix` (`prefix`);
// select * from prefix_map where prefix = '0899976098'; 1 row in set (0.00 sec)
