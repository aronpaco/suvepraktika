// Fetch data from the database using PHP
function fetchData() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (this.readyState === 4 && this.status === 200) {
        var data = JSON.parse(this.responseText);
        renderData(data);
      }
    };
    xhr.open("GET", "fetch_data.php", true);
    xhr.send();
  }
  
  // Render the data in the table
  function renderData(data) {
    var tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";
  
    for (var i = 0; i < data.length; i++) {
      var row = document.createElement("tr");
      row.innerHTML = "<td>" + data[i].id + "</td><td>" + data[i].name + "</td><td>" + data[i].age + "</td>";
      tableBody.appendChild(row);
    }
  }
  
  // Sort the table based on the clicked column
  function sortTable(column) {
    var property;
    switch (column) {
      case 0:
        property = "id";
        break;
      case 1:
        property = "name";
        break;
      case 2:
        property = "age";
        break;
    }
  
    // Fetch sorted data from the database using PHP
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (this.readyState === 4 && this.status === 200) {
        var data = JSON.parse(this.responseText);
        renderData(data);
      }
    };
    xhr.open("GET", "fetch_data.php?sort=" + property, true);
    xhr.send();
  }
  
  // Initial data fetching and rendering
  fetchData();
  