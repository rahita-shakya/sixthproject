<?php
function generateRecommendations($applicant_id, $conn) {
    // Step 1: Build the user-item matrix
    $matrix = [];

    // Get all unique applicant IDs and job IDs
    $applicant_ids = [];
    $job_ids = [];

    $result = $conn->query("SELECT * FROM job_views");
    while ($row = $result->fetch_assoc()) {
        $a_id = $row['applicant_id'];
        $j_id = $row['job_id'];
        $view_count = $row['view_count'];

        $matrix[$a_id][$j_id] = $view_count;
        if (!in_array($a_id, $applicant_ids)) $applicant_ids[] = $a_id;
        if (!in_array($j_id, $job_ids)) $job_ids[] = $j_id;
    }

    // Re-index applicant and job IDs
    $user_index_map = array_flip($applicant_ids); // applicant_id => index
    $job_index_map = array_flip($job_ids);        // job_id => index
    $index_user_map = $applicant_ids;             // index => applicant_id
    $index_job_map = $job_ids;                    // index => job_id

    $num_users = count($applicant_ids);
    $num_jobs = count($job_ids);
    $k = 3;

    // Initialize P and Q with random values
    $P = [];
    for ($i = 0; $i < $num_users; $i++) {
        for ($j = 0; $j < $k; $j++) {
            $P[$i][$j] = rand(1, 5);
        }
    }

    $Q = [];
    for ($i = 0; $i < $num_jobs; $i++) {
        for ($j = 0; $j < $k; $j++) {
            $Q[$i][$j] = rand(1, 5);
        }
    }

    $steps = 5000;
    $alpha = 0.002;
    $beta = 0.02;

    for ($step = 0; $step < $steps; $step++) {
        foreach ($matrix as $a_id => $jobs) {
            foreach ($jobs as $j_id => $view_count) {
                $u = $user_index_map[$a_id];
                $j = $job_index_map[$j_id];
                $predicted = dotProduct($P[$u], $Q[$j]);
                $error = $view_count - $predicted;

                for ($f = 0; $f < $k; $f++) {
                    $P[$u][$f] += $alpha * (2 * $error * $Q[$j][$f] - $beta * $P[$u][$f]);
                    $Q[$j][$f] += $alpha * (2 * $error * $P[$u][$f] - $beta * $Q[$j][$f]);
                }
            }
        }
    }

    // Now generate recommendations for given applicant_id
    if (!isset($user_index_map[$applicant_id])) {
        return []; // No data available for this applicant
    }

    $u_index = $user_index_map[$applicant_id];
    $recommendations = [];

    for ($j = 0; $j < $num_jobs; $j++) {
        $score = dotProduct($P[$u_index], $Q[$j]);
        $recommendations[$index_job_map[$j]] = $score; // Use real job ID as key
    }

    arsort($recommendations); // Sort descending
    return array_keys(array_slice($recommendations, 0, 5)); // Top 5 job IDs
}

function dotProduct($a, $b) {
    return array_sum(array_map(fn($x, $y) => $x * $y, $a, $b));
}
?>
