<?php
// Query and fetch a single row
function queryAndFetch($db, $query) {
    $result = $db->query($query);
    return $result->fetch_assoc();
}

// Fetch all mysqli results
function fetchAll($result) {
    $results = array();
    while ($row = $result->fetch_assoc()){
        $results[] = $row;
    }
    return $results;
}

// Query and fetch all mysqli results
function queryAndFetchAll($db, $query) {
    $result = $db->query($query);
    return fetchAll($result);
}
?>
