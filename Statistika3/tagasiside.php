<?php
session_start(); // Start the session at the beginning

if (!isset($_SESSION["kasutaja_id"])) {
  // Redirect to the login page if user is not logged in
  header("Location: logi_sisse.php");
  exit();
}

if (isset($_GET["logout"])) {
  // Destroy the session.
  session_unset();
  session_destroy();
  header("Location: logi_sisse.php");
  exit();
} 
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Tagasisiside tabel</title>
  <link rel="stylesheet" type="text/css" href="style_stat.css">
  <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>
<body>
  <h1>Tagasiside tabel</h1>

  <div id="filter">
    <label for="from">Alates:</label>
    <input type="date" id="from" lang="fr-CA">
    <label for="to">Kuni:</label>
    <input type="date" id="to">
    <button class="filterButton" onclick="filterResults()">Filtreeri</button>
    <button class="filterButton" onclick="clearFilter()">Tühjenda</button>
    <button id="csvButton" onclick="exportToCSV()">Export CSV</button>
    <button id="excelButton" onclick="exportToExcel()">Export Excel</button>
    <button class="button notClicked" id="statisticsButton" onclick="switchTable('statistics')">Tulemused</button>
    <button class="button clicked" id="feedbackButton" onclick="switchTable('feedback')">Tagasiside</button>
    <button id="logoutButton" onclick="logout()">Log Out</button>
  </div>

  <table id="resultsTable">
    <thead>
      <tr>
        <th data-column="0" data-type="date">
          Kuupäev
          <span class="clickable" onclick="sortTable(0, 'asc')">&#x25B2;</span>
          <span onclick="sortTable(0, 'desc')">&#x25BC;</span>
        </th>
        <th data-column="1">Nimi <span class="clickable" onclick="sortTable(1, 'asc')">&#x25B2;</span><span class="clickable" onclick="sortTable(1, 'desc')">&#x25BC;</span></th>
        <th data-column="2">Nupp <span class="clickable" onclick="sortTable(2, 'asc')">&#x25B2;</span><span class="clickable" onclick="sortTable(2, 'desc')">&#x25BC;</span></th>
        <th data-column="3">Tagasiside <span class="clickable" onclick="sortTable(3, 'asc')">&#x25B2;</span><span class="clickable" onclick="sortTable(3, 'desc')">&#x25BC;</span></th>
        <th data-column="4">Küsimus <span class="clickable" onclick="sortTable(4, 'asc')">&#x25B2;</span><span class="clickable" onclick="sortTable(4, 'desc')">&#x25BC;</span></th>
        <th data-column="5">Email <span class="clickable" onclick="sortTable(5, 'asc')">&#x25B2;</span><span class="clickable" onclick="sortTable(5, 'desc')">&#x25BC;</span></th>
      </tr>
    </thead>
    <tbody>
    <?php
    require_once "config.php";
    $conn = new mysqli($server_host, $server_user_name, $server_password, $dbname);
    $stmt = $conn->prepare("SELECT tagasiside.id, tagasiside.tagasiside, tagasiside.email, tagasiside.nupp, tagasiside.kysimus, tagasiside.kasutaja_id, tagasiside.lisatud_kuupaev, k.nimi FROM tagasiside tagasiside INNER JOIN kasutaja k ON tagasiside.kasutaja_id = k.id");
    $conn->set_charset("utf8");
    echo $conn->error;
    $stmt->execute();
    $stmt->bind_result($id, $tagasiside, $email, $nupp, $kysimus, $kasutaja_id, $lisatud_kuupaev, $nimi);

    // Pagination variables
    $resultsPerPage = 15;
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
    $startIndex = ($currentPage - 1) * $resultsPerPage;
    $endIndex = $startIndex + $resultsPerPage;
    $rowCounter = 0;

    while ($stmt->fetch()) {
      if ($rowCounter >= $startIndex && $rowCounter < $endIndex) {
        if ($nupp == 1) {
          $nupp = "&#128077";
        } else {
          $nupp = "&#128078";
        }

        echo "<tr>";
        echo "<td class='wrap-cell'>$lisatud_kuupaev</td>";
        echo "<td class='wrap-cell'>$nimi</td>";
        echo "<td class='wrap-cell'>$nupp</td>";
        echo "<td class='wrap-cell'>$tagasiside</td>";
        echo "<td class='wrap-cell'>$kysimus</td>";
        echo "<td class='wrap-cell'>$email</td>";
        echo "</tr>";
      }

      $rowCounter++;
    }

    $stmt->close();
    $conn->close();
    ?>
    </tbody>
  </table>

  <div id="pagination">
    <?php
    $totalResults = $rowCounter;
    $totalPages = ceil($totalResults / $resultsPerPage);

    if ($totalPages > 1): ?>
      <button class="pagination-button" onclick="changePage(<?php echo $currentPage - 1; ?>)" <?php echo $currentPage === 1 ? 'disabled' : ''; ?>>Previous</button>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <button class="pagination-button <?php echo $i === $currentPage ? 'active' : ''; ?>" onclick="changePage(<?php echo $i; ?>)"><?php echo $i; ?></button>
      <?php endfor; ?>
      <button class="pagination-button" onclick="changePage(<?php echo $currentPage + 1; ?>)" <?php echo $currentPage === $totalPages ? 'disabled' : ''; ?>>Next</button>
    <?php endif; ?>
  </div>
  <script>
    function changePage(page) {
      // Validate the page number
      if (page <= 0) {
        page = 1; // Set the page to 1 if it is negative or zero
      } else if (page > <?php echo $totalPages; ?>) {
        page = <?php echo $totalPages; ?>; // Set the page to the maximum allowed value
      }

      // Modify the URL with the new page number
      window.location.href = window.location.pathname + "?page=" + page;
    }

  </script>

  <script src="script_tagasiside.js"></script>
</body>
</html>
