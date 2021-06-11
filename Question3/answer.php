<?php

/**
 * NOTE: I generated 2.5 million records for testing using generate.php.
 * I manually added ranges for block update testing
 * NOTE: prefix was created as an index
 * ALTER TABLE `prefix_map` ADD INDEX `prefix_map_prefix` (`prefix`);
 * Without an Index: select * from prefix_map where prefix = '0899976098'; 1.06 seconds
 * With an Index: select * from prefix_map where prefix = '0899976098'; 1 row in set (0.00 sec)
 * NOTE: fulltext search created as well
 * ALTER TABLE prefix_map ADD FULLTEXT INDEX prefix_map_fulltext (prefix, destination)
 * For any fulltext searches
 * SELECT * FROM prefix_map WHERE MATCH(prefix, destination) AGAINST('0899977*' IN BOOLEAN MODE);
 */

require ('config.php');

$pdo = null;

try
{
    $pdo = new PDO($dsn, $user, $password);

    if ($pdo)
    {
        // question 3.1
        $result = getDestination($pdo,'0111234567');
        echo "\$result:$result".PHP_EOL;

        // question 3.2
        try
        {
            // update a single number
            setDestination($pdo,null,null,'yet another destination','0111234567');

            // update a block of numbers
            setDestination($pdo,'0111534560','0111534569','changed destination');
        }
        catch (Exception $e)
        {
            die($e->getMessage());
        }
    }
}
catch (PDOException $e)
{
    echo $e->getMessage();
}

/**
 * Find the closest destination using a Telephone Number
 *
 * @param PDO $pdo
 * @param string $telephoneNumber
 * @return mixed
 */
function getDestination(PDO $pdo,string $telephoneNumber)
{
    $stmt = $pdo->prepare('SELECT destination FROM prefix_map WHERE prefix = ?');
    $stmt->execute([$telephoneNumber]);
    $result = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$result)
    {
        // keep reducing the number until it finds a result
        return getDestination($pdo,substr_replace($telephoneNumber, "", -1));
    }

    if (is_object($result))
    {
        return $result->destination;
    }
}

/**
 * Create a block of phone numbers
 *
 * @param string $start
 * @param string $end
 * @return array
 */
function generateDestinationRange(string $start,string $end) : array
{
    $items = [];

    foreach (range($start,$end) as $item)
    {
        array_push($items,str_pad($item,10,'0',STR_PAD_LEFT));
    }

    return $items;
}

/**
 * Check if the destination is allowed to change
 *
 * @param PDO $pdo
 * @param $range
 * @return bool
 */
function canDestinationChange(PDO $pdo,$range) : bool
{
    $in = str_repeat('?,', count($range) - 1) . '?';
    $sql = "SELECT destination FROM prefix_map WHERE prefix IN ($in) group by destination";
    $stm = $pdo->prepare($sql);
    $stm->execute($range);
    $data = $stm->fetchAll();

    return !(count($data) > 1);
}

/**
 * Update the destination for a specific telephone number or a block of numbers
 *
 * To update a single telephone number: setDestination($pdo,null,null,'new','0101001138');
 * To update a block of telephone numbers: setDestination($pdo,'0111234560','0111234569','new destination');
 *
 * @param PDO $pdo
 * @param string|null $startBlock e.g. 0111234560 or 0111234500
 * @param string|null $endBlock e.g. 0111234569 or 0111234599
 * @param string $destination
 * @param string|null $telephoneNumber
 * @throws Exception
 */
function setDestination(PDO $pdo,?string $startBlock,?string $endBlock,string $destination,string $telephoneNumber = null) : void
{
    if (strlen($destination) < 3)
    {
        throw new \Exception('Destination needs to be at least 3 numbers');
    }

    // if it's a single number to update
    if (strlen($telephoneNumber) > 0)
    {
        $sql = "UPDATE prefix_map SET destination = ? WHERE prefix = ?";
        $pdo->prepare($sql)->execute([$destination, $telephoneNumber]);
        return;
    }

    // if it's a block of numbers to update
    if (strlen($startBlock) > 0 && strlen($endBlock) > 0)
    {
        if ((int)substr($startBlock, -1) !== 0)
        {
            throw new \Exception('A starting block should start with a 0');
        }

        if ((int)substr($endBlock, -1) !== 9)
        {
            throw new \Exception('An end block should end with a 9');
        }

        $range = generateDestinationRange($startBlock,$endBlock);

        if (count($range) > 0)
        {
            $canChange = canDestinationChange($pdo,$range);

            if (!$canChange)
            {
                throw new \Exception('Destination cannot be changed because not all numbers in the range have the same Destination');
            }

            $sql = "UPDATE prefix_map SET destination = :destination WHERE prefix BETWEEN :start_block AND :end_block";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':destination', $destination, PDO::PARAM_STR);
            $statement->bindParam(':start_block', $startBlock, PDO::PARAM_STR);
            $statement->bindParam(':end_block', $endBlock, PDO::PARAM_STR);
            $statement->execute();
        }
    }
}