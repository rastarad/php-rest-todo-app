const apiURL = "api/tasks.php";
function addTask(name) {
  //zapakuj dane w JSONa
  //przygotuj zapytanie
  //wyślij zapytanie
  //obsłuż sukces
  //obsłuż bląd

  let task = {
    name: name,
    status: 0,
    due_date: "2021-06-13 21:45:24",
  };

  function success(data) {
    console.log("added " + data.id);
    console.log(data);
  }

  $.ajax({
    type: "POST",
    url: apiURL,
    contentType: "application/x-www-form-urlencoded",
    data: task,
    success: success,
    // error: error,
    dataType: "json",
  });
}

function deleteTask(id) {
  let task = {
    id: id,
  };

  function success() {
    console.log("deleted " + id);
  }

  $.ajax({
    type: "DELETE",
    url: apiURL,
    contentType: "application/x-www-form-urlencoded",
    data: task,
    success: success,
    // error: error,
    dataType: "json",
  });
}

function updateTask(id, name, status, dueDate) {
  let task = {
    id: id,
    name: name,
    status: status,
    due_date: dueDate,
  };

  function success(data) {
    console.log("updated " + id);
    console.log(data);
  }

  $.ajax({
    type: "PUT",
    url: apiURL,
    contentType: "application/x-www-form-urlencoded",
    data: task,
    success: success,
    // error: error,
    dataType: "json",
  });
}
