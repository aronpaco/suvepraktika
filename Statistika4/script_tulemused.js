let currentSortColumn = 0;
    let currentSortOrder = 'asc';

    function sortTable(column, order) {
      currentSortColumn = column;
      currentSortOrder = order;

      const table = document.getElementById("resultsTable");
      const tbody = table.querySelector("tbody");
      const rows = Array.from(tbody.getElementsByTagName("tr"));

      rows.sort((rowA, rowB) => {
        const cellA = rowA.getElementsByTagName("td")[column].textContent.trim();
        const cellB = rowB.getElementsByTagName("td")[column].textContent.trim();

        if (cellA < cellB) return order === "asc" ? -1 : 1;
        if (cellA > cellB) return order === "asc" ? 1 : -1;
        return 0;
      });

      while (tbody.firstChild) {
        tbody.firstChild.remove();
      }

      rows.forEach((row) => {
        tbody.appendChild(row);
      });
    }

    function filterResults() {
      const table = document.getElementById("resultsTable");
      const tbody = table.querySelector("tbody");
      const rows = Array.from(tbody.getElementsByTagName("tr"));

      const fromDate = document.getElementById("from").value;
      const toDate = document.getElementById("to").value;

      rows.forEach((row) => {
        const dateCell = row.getElementsByTagName("td")[0];
        const dateValue = dateCell.textContent.trim();

        if (
          (fromDate && dateValue < fromDate) ||
          (toDate && dateValue > toDate + "T23:59:59")
        ) {
          row.style.display = "none";
        } else {
          row.style.display = "";
        }
      });
    }

    function clearFilter() {
      const table = document.getElementById("resultsTable");
      const rows = Array.from(table.getElementsByTagName("tr"));

      rows.forEach((row) => {
        row.style.display = "";
      });

      document.getElementById("from").value = "";
      document.getElementById("to").value = "";
    }

    function exportToCSV() {
      const table = document.getElementById("resultsTable");
      const rows = Array.from(table.getElementsByTagName("tr"));
      const headers = Array.from(table.getElementsByTagName("th"));

      const csvContent = [
        headers.map((header) => header.textContent).join(","),
        ...rows.map((row) =>
          Array.from(row.getElementsByTagName("td"))
            .map((cell) => cell.textContent)
            .join(",")
        ),
      ].join("\n");

      const encodedCSVContent = encodeURIComponent(csvContent);
      const downloadLink = document.createElement("a");
      downloadLink.href = "data:text/csv;charset=utf-8," + encodedCSVContent;
      downloadLink.download = "tulemused.csv";
      downloadLink.click();
    }

    function exportToExcel() {
      const table = document.getElementById("resultsTable");
      const workbook = XLSX.utils.table_to_book(table, { sheet: "Tulemused" });
      XLSX.writeFile(workbook, "tulemused.xlsx");
    }

    function switchTable(tableType) {
      if (tableType === "statistics") {
        window.location.href = "tulemused.php";
      } else if (tableType === "feedback") {
        window.location.href = "tagasiside.php";
      }
    }

    function changePage(page) {
      // Modify the URL with the new page number
      window.location.href = window.location.pathname + "?page=" + page;
    }

    function logout() {
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          window.location.href = 'logi_sisse.php';
          // Forcing page reload on browser back
          window.onunload = function(){};
          setTimeout(function(){window.onunload = function(){};}, 0);
        }
      };
      xhr.open('GET', 'log_out.php?logout=1', true);
      xhr.send();
    }