<?php
function filterSudoku($grid, $filter) {
    if ($filter !== 'full') {
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($grid[$row][$col] !== $filter) {
                    $grid[$row][$col] = '';
                }
            }
        }
    }
    return $grid;
}
?>