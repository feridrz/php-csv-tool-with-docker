
<form action="index.php" method="post" enctype="multipart/form-data">
    <input type="file" name="csvFile">
    <input type="submit" value="Upload CSV">
</form>


<?php if (!empty($data)): ?>

    <form action="index.php" method="get">
        Category: <input type="text" name="category"><br>
        Gender: <input type="text" name="gender"><br>
        Birth Date: <input type="date" name="birthDate"><br>
        Age: <input type="number" name="age"><br>
        Age Range: <input type="text" name="ageRange" placeholder="25-30"><br>
        <input type="submit" value="Filter">
    </form>

    <form action="index.php" method="get">
        <!-- Filter fields -->
        <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category'] ?? ''); ?>">
        <input type="hidden" name="gender" value="<?php echo htmlspecialchars($_GET['gender'] ?? ''); ?>">
        <input type="hidden" name="birthDate" value="<?php echo htmlspecialchars($_GET['birthDate'] ?? ''); ?>">
        <input type="hidden" name="age" value="<?php echo htmlspecialchars($_GET['age'] ?? ''); ?>">
        <input type="hidden" name="ageRange" value="<?php echo htmlspecialchars($_GET['ageRange'] ?? ''); ?>">

        <!-- Export button -->
        <input type="submit" name="export" value="Export to CSV">
    </form>

    <table>
        <tr>
            <th>Category</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Gender</th>
            <th>BirthDate</th>
        </tr>
        <?php foreach ($data as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                <td><?php echo htmlspecialchars($row['birthDate']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>


    <!-- Pagination -->
    <div class="pagination">
        <?php

        function createPageLink($pageNum, $filters) {
            $link = '?page=' . $pageNum;
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    $link .= "&$key=" . urlencode($value);
                }
            }
            return $link;
        }
        $range = 2; // Number of links to show before and after the current page
        $firstPage = max(1, $page - $range);
        $lastPage = min($totalPages, $page + $range);

        if ($firstPage > 1) {
            echo '<a href="' . createPageLink(1, $filters) . '">First</a>';
        }

        for ($i = $firstPage; $i <= $lastPage; $i++) {
            echo '<a href="' . createPageLink($i, $filters) . '"';
            if ($i === $page) echo ' class="active"';
            echo ">$i</a>";
        }

        if ($lastPage < $totalPages) {
            echo '<a href="' . createPageLink($totalPages, $filters) . '">Last</a>';
        }


        ?>
    </div>

<?php endif; ?>
