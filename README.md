I have share zip file with including database in DB folder for reference also we create migration files to create database without import .sql file.


1. Database Setup
    -> Open PhpMyadmin from Hosting Cpanel.
    -> create database and assign user for that database with given previleges like CREATE, INSERT, READ, UPDATE, DELETE etc.
    -> If we want fresh record then we used migration:-
        1. Go Console from Cpanel.
        2. go to project folder and run below command to create tables automatically.
                php artisan migrate
    -> else we can import .sql file from PhpMyadmin import menu.   
    
2. Project Setup.
    -> Open File Manager from Hosting Cpanel.
    -> open domain folder of project.
    -> unzip compress file.
    -> update .env file with database creadentials
    -> run composer install command to install php library
    -> run npm install command to install node packages

##########################################################################################

Drag and Drop code below:-

1. HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="styles.css">
  <title>Drag and Drop</title>
</head>
<body>

<div class="container">
  <div class="column" id="todo-column">
    <h2>To Do</h2>
    <div class="cards" id="todo-cards" ondrop="drop(event, 'todo')" ondragover="allowDrop(event)">
      <!-- Cards in the "To Do" column -->
      <div class="card" draggable="true" ondragstart="drag(event)" data-task-id="1">Task 1</div>
      <div class="card" draggable="true" ondragstart="drag(event)" data-task-id="2">Task 2</div>
    </div>
  </div>

  <div class="column" id="in-progress-column">
    <h2>In Progress</h2>
    <div class="cards" id="in-progress-cards" ondrop="drop(event, 'in-progress')" ondragover="allowDrop(event)">
      <!-- Cards in the "In Progress" column -->
    </div>
  </div>

  <div class="column" id="done-column">
    <h2>Done</h2>
    <div class="cards" id="done-cards" ondrop="drop(event, 'done')" ondragover="allowDrop(event)">
      <!-- Cards in the "Done" column -->
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="script.js"></script>

</body>
</html>

------------------------------------------------------------------------------------
CSS:-

body {
  font-family: 'Arial', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f0f0f0;
}

.container {
  display: flex;
}

.column {
  flex: 1;
  background-color: #fff;
  margin: 10px;
  padding: 15px;
  border-radius: 5px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

.cards {
  min-height: 100px;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 10px;
  margin-top: 10px;
}

.card {
  background-color: #eaeaea;
  padding: 10px;
  margin-bottom: 5px;
  cursor: move;
}

------------------------------------------------------------------------------------
JS:-

function allowDrop(event) {
  event.preventDefault();
}

function drag(event) {
  event.dataTransfer.setData('text/plain', event.target.dataset.taskId);
}

function drop(event, targetColumn) {
  event.preventDefault();
  const taskId = event.dataTransfer.getData('text/plain');
  const card = document.querySelector(`[data-task-id="${taskId}"]`);

  // Remove the card from its current column
  card.parentNode.removeChild(card);

  // Append the card to the target column
  const targetColumnElement = document.getElementById(`${targetColumn}-cards`);
  targetColumnElement.appendChild(card);

  // Update the server-side record using AJAX
  updateRecord(taskId, targetColumn);
}

function updateRecord(taskId, status) {
  // Use AJAX to send the taskId and status to the server for updating the record
  $.ajax({
    type: 'POST',
    url: 'update_record.php', // Update this with your server-side script
    data: { taskId, status },
    success: function(response) {
      console.log('Record updated successfully');
    },
    error: function(error) {
      console.error('Error updating record:', error);
    }
  });
}
