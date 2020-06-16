const apiURL = "api/tasks.php";

function addTask(name, onSuccess) {
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
    success: onSuccess,
    // error: error,
    dataType: "json",
  });
}

function deleteTask(id, onSuccess) {
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
    success: onSuccess,
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

function changeStatus(checkbox) {
  const taskData = getTaskData($(checkbox));
  updateTask(taskData.id, taskData.name, taskData.status, taskData.due_date);
}

function getTaskData(inputSelector) {
  const inputsData = $(inputSelector)
    .parents(".todo-item")
    .find("input")
    .map(function () {
      return { name: this.name, value: this.value, checked: this.checked };
    });

  const task = {
    id: inputsData.toArray().find(function (x) {
      return x.name === "id";
    }).value,
    name: inputsData.toArray().find(function (x) {
      return x.name === "name";
    }).value,
    due_date: inputsData.toArray().find(function (x) {
      return x.name === "due_date";
    }).value,
    status: inputsData.toArray().find(function (x) {
      return x.name === "status";
    }).checked
      ? 1
      : 0,
  };

  return task;
}
