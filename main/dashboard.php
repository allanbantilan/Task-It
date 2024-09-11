<?php


// Check if the user is logged in
// if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
//     header("Location: login.php");
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="../css/output.css">

</head>

<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php
        include '../includes/component/sidebar.php';
        ?>

        <!-- Main content -->

        <?php
        include '../includes/component/main.php';
        ?>

        <?php


        include('../includes/functions/db_connect.php');

     
        $userId = $_SESSION['user_id'];

        // Retrieve sorting and filtering parameters
        $sort_order = isset($_GET['sort']) ? $_GET['sort'] : 'all';
        $filter_category = isset($_GET['category']) ? $_GET['category'] : 'all';

        // Base query with initial conditions
        $where_clause = "WHERE task_done_number = 0 AND user_id = ?";

        // Add filtering for done/pending tasks
        if ($sort_order == 'done') {
            $where_clause .= " AND task_is_done = 1";
        } elseif ($sort_order == 'pending') {
            $where_clause .= " AND task_is_done = 0";
        }

        // Add filtering by category
        if ($filter_category !== 'all') {
            $where_clause .= " AND task_category = ?";
        }

        // Pagination logic
        $tasks_per_page = 8;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $tasks_per_page;

        // Prepare SQL query
        $sql = "SELECT * FROM tasks $where_clause ORDER BY task_order ASC LIMIT ?, ?";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        if ($filter_category === 'all') {
            $stmt->bind_param('iii', $userId, $start, $tasks_per_page);
        } else {
            $stmt->bind_param('issi', $userId, $filter_category, $start, $tasks_per_page);
        }

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Query failed: " . $stmt->error);
        }

        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }

        // Get total number of tasks with the same filter criteria
        $total_tasks_query = "SELECT COUNT(*) AS total FROM tasks $where_clause";
        $total_tasks_stmt = $conn->prepare($total_tasks_query);
        if ($filter_category === 'all') {
            $total_tasks_stmt->bind_param('i', $userId);
        } else {
            $total_tasks_stmt->bind_param('is', $userId, $filter_category);
        }
        $total_tasks_stmt->execute();
        $total_tasks_result = $total_tasks_stmt->get_result();
        $total_tasks_row = $total_tasks_result->fetch_assoc();
        $total_tasks = $total_tasks_row['total'];
        $total_pages = ceil($total_tasks / $tasks_per_page);

        $stmt->close();
        $total_tasks_stmt->close();
        $conn->close();
        ?>


        <div class="flex flex-col px-8 py-6">
            <!-- Section: Features -->

            <div class="mb-2">
                <div class="flex justify-between items-center">
                    <!-- Heading on the left side -->
                    <div class="flex items-center mb-4">
                        <h1 class="text-3xl font-bold text-gray-800 mr-4">Tasks</h1>

                    </div>


                    <!-- Sort Dropdown and Pagination on the right side -->
                    <div class="flex space-x-4 items-center">
                        <!-- Sort Dropdown -->
                        <form method="GET" action="" class="flex items-center space-x-2">
                            <label for="sort" class="text-sm font-medium text-gray-700">Show:</label>
                            <select name="sort" id="sort" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" <?php echo $sort_order == 'all' ? 'selected' : ''; ?>>All</option>
                                <option value="pending" <?php echo $sort_order == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="done" <?php echo $sort_order == 'done' ? 'selected' : ''; ?>>Done</option>
                            </select>
                            <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none">Apply</button>
                        </form>
                        <div class="h-8 border-l border-gray-300"></div>
                        <!-- Pagination -->
                        <nav class="flex space-x-2">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?>&sort=<?php echo htmlspecialchars($sort_order); ?>" class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-600 rounded hover:bg-blue-600 hover:text-white">
                                    Previous
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="?page=<?php echo $i; ?>&sort=<?php echo htmlspecialchars($sort_order); ?>" class="px-4 py-2 text-sm font-medium <?php echo $i === $page ? 'text-white bg-blue-600' : 'text-blue-600 bg-white border border-blue-600'; ?> rounded hover:bg-blue-600 hover:text-white">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?>&sort=<?php echo htmlspecialchars($sort_order); ?>" class="px-4 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-600 rounded hover:bg-blue-600 hover:text-white">
                                    Next
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
                <p class="text-sm text-gray-600">You can drag the cards to reorder them.</p>
            </div>
        </div>




        <section class="px-8 grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 mb-8">

            <?php if (empty($tasks)): ?>
                <!-- No Tasks Message -->
                <div class="col-span-full text-center text-gray-500 text-lg">
                    No tasks available.
                </div>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <!-- Task Card Component -->
                    <div class="w-full max-w-xs bg-white p-4 rounded-lg border border-blue-gray-50 text-blue-gray-500 shadow-lg shadow-blue-gray-500/10" data-task-id="<?php echo $task['task_id']; ?>">
                        <div class="mb-2 flex justify-between items-center">
                            <a href="#" class="block truncate text-base font-medium text-blue-gray-900 transition-colors hover:text-blue-600" onclick=" openTaskModal(<?php echo htmlspecialchars(json_encode($task)); ?>); return false;">
                                <?php echo htmlspecialchars($task['task_name']); ?>
                            </a>
                            <div class="flex items-center">
                                <div class="relative inline-flex items-center whitespace-nowrap rounded-full py-1 px-2 text-xs font-medium capitalize tracking-wide text-white <?php echo $task['task_is_done'] ? 'bg-green-500' : 'bg-red-500'; ?>">
                                    <?php echo $task['task_is_done'] ? 'Done' : 'Pending'; ?>
                                </div>
                                <?php if ($task['task_is_done']): ?>
                                    <!-- Delete Icon with Tooltip -->
                                    <div class="relative ml-2 group">
                                        <button onclick="openDeleteModal(<?php echo $task['task_id']; ?>)" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash fa-lg"></i>
                                        </button>
                                        <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            Remove from dashboard?
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <!-- Mark as Done Icon with Tooltip -->
                                    <div class="relative ml-2 group">
                                        <button onclick="markAsDone(<?php echo $task['task_id']; ?>)" class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-check-square fa-lg"></i>
                                        </button>
                                        <div class="absolute bottom-full mb-2 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            Mark as done?
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-normal text-gray-700 truncate">
                                <?php echo htmlspecialchars($task['task_description']); ?>
                            </p>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div class="flex items-center gap-1">
                                <span class="h-3 w-3 rounded-full bg-blue-500"></span>
                                <p class="text-xs font-normal text-gray-700">
                                    <?php echo htmlspecialchars($task['task_category']); ?>
                                </p>
                            </div>
                            <div class="flex items-center gap-1">
                                <p class="text-xs font-normal text-gray-700 bg-red-100 rounded-lg p-1">
                                    Due: <?php echo htmlspecialchars($task['task_due_date']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- modal for the task showing the task details  -->
        <script>
            function openTaskModal(task) {
                // Create modal HTML with a more modern design
                const modalHTML = `
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50" onclick="closeModal(event)">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 md:mx-auto p-8 transform transition-all duration-300 ease-in-out" 
             onclick="event.stopPropagation()"
             style="opacity: 0; transform: scale(0.9);">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">${task.task_name}</h3>
                <div class="space-y-4">
                    <p class="text-gray-600">
                        ${task.task_description}
                    </p>
                    <div class="flex justify-between items-center bg-gray-100 rounded-lg p-3">
                        <span class="text-sm font-medium text-gray-700">Category</span>
                        <span class="text-sm font-semibold text-indigo-600">${task.task_category}</span>
                    </div>
                    <div class="flex justify-between items-center bg-gray-100 rounded-lg p-3">
                        <span class="text-sm font-medium text-gray-700">Due Date</span>
                        <span class="text-sm font-semibold text-indigo-600">${task.task_due_date}</span>
                    </div>
                    <div class="flex justify-center items-center">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold ${task.task_is_done == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${task.task_is_done == '1' ? 'Completed' : 'Pending'}
                        </span>
                    </div>
                </div>
                <div class="mt-8">
                    <button id="closeModal" class="w-full py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    `;

                // Add modal to the body
                document.body.insertAdjacentHTML('beforeend', modalHTML);

                // Trigger animation after a short delay
                setTimeout(() => {
                    const modalContent = document.querySelector('#taskModal > div');
                    modalContent.style.opacity = '1';
                    modalContent.style.transform = 'scale(1)';
                }, 50);

                // Add event listener to close button
                document.getElementById('closeModal').addEventListener('click', closeModal);
            }

            function closeModal(event) {
                const modal = document.getElementById('taskModal');
                const modalContent = modal.querySelector('div');

                if (event.target === modal || event.target.id === 'closeModal') {
                    modalContent.style.opacity = '0';
                    modalContent.style.transform = 'scale(0.9)';

                    setTimeout(() => {
                        modal.remove();
                    }, 300); // Match this delay with the CSS transition duration
                }
            }
        </script>


        <!-- remove from dashboard modal  -->
        <div id="deleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg w-1/2">
                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b px-6 py-3">
                    <h2 class="text-lg font-semibold">Remove Task</h2>

                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form action="../includes/functions/move_task.php" method="POST">
                        <!-- Task Title -->
                        <div class="mb-4 bg-red-200 text-red-700 p-4 rounded-lg">
                            <p>Remove it in the Dashboard?</p>
                        </div>
                        <input type="text" id="deleteId" name="deleteId" hidden>
                        <!-- Action Buttons -->
                        <div class="flex justify-end">
                            <button type="button" id="deleteCloseModalBottom" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2">Cancel</button>
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Remove Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function openDeleteModal(taskId) {
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteId').value = taskId;
            }

            document.getElementById('deleteCloseModalBottom').addEventListener('click', function() {
                document.getElementById('deleteModal').classList.add('hidden');
            });
        </script>
    </div>


    <!-- mark as done modal  -->
    <div id="doneModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-1/2">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b px-6 py-3">
                <h2 class="text-lg font-semibold">Mark as done</h2>

            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form action="../includes/functions/mark_as_done.php" method="POST">
                    <!-- Task Title -->
                    <div class="mb-4 bg-gray-200 p-4 rounded-lg">
                        <p>Mark this task as done?</p>
                    </div>
                    <input type="text" id="doneId" name="doneId" hidden>
                    <!-- Action Buttons -->
                    <div class="flex justify-end">
                        <button type="button" id="doneCloseModal" class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    <script>
        function markAsDone(taskId) {
            document.getElementById('doneModal').classList.remove('hidden');
            document.getElementById('doneId').value = taskId;
        };

        document.getElementById('doneCloseModal').addEventListener('click', () => {
            document.getElementById('doneModal').classList.add('hidden');
        });
    </script>


    <script>
        // Function to enable drag and drop for task cards
        function enableDragAndDrop() {
            const taskSection = document.querySelector('.grid');
            let draggedItem = null;

            // Add event listeners to each task card
            taskSection.querySelectorAll('.w-full').forEach(taskCard => {
                taskCard.setAttribute('draggable', true);

                taskCard.addEventListener('dragstart', function(e) {
                    draggedItem = this;
                    setTimeout(() => this.style.opacity = '0.5', 0);
                });

                taskCard.addEventListener('dragend', function() {
                    setTimeout(() => this.style.opacity = '1', 0);
                    draggedItem = null;
                });

                taskCard.addEventListener('dragover', function(e) {
                    e.preventDefault();
                });

                taskCard.addEventListener('dragenter', function(e) {
                    e.preventDefault();
                    this.style.background = 'rgba(0, 0, 0, 0.1)';
                });

                taskCard.addEventListener('dragleave', function() {
                    this.style.background = '';
                });

                // After successful drop, call updateOrderInBackend
                taskCard.addEventListener('drop', function(e) {
                    e.preventDefault();
                    if (this !== draggedItem) {
                        let allCards = Array.from(taskSection.querySelectorAll('.w-full'));
                        let draggedIndex = allCards.indexOf(draggedItem);
                        let targetIndex = allCards.indexOf(this);

                        if (draggedIndex < targetIndex) {
                            taskSection.insertBefore(draggedItem, this.nextSibling);
                        } else {
                            taskSection.insertBefore(draggedItem, this);
                        }
                        updateOrderInBackend();
                    }
                    this.style.background = '';
                });
            });
        }

        // Call the function when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', enableDragAndDrop);

        // Function to update the order in the backend
        function updateOrderInBackend() {
            const taskSection = document.querySelector('.grid');
            const taskOrder = Array.from(taskSection.querySelectorAll('.w-full')).map(card => card.dataset.taskId);

            // console.log('Sending updated task order:', taskOrder);

            fetch('../includes/functions/update_task_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        taskOrder: taskOrder
                    }),
                })
                .then(response => {
                    // console.log('Response status:', response.status);
                    return response.text();
                })
                .then(text => {
                    // console.log('Raw response:', text);
                    try {
                        return JSON.parse(text);
                    } catch (error) {
                        console.error('Failed to parse response as JSON:', error);
                        throw new Error('Server returned non-JSON response: ' + text);
                    }
                })
                .then(data => {
                    if (data.success) {
                        // console.log('Task order updated successfully');
                    } else {
                        // console.error('Failed to update task order:', data.error);
                    }
                })
                .catch((error) => {
                    // console.error('Error updating task order:', error.message);
                });
        }

        // ... (rest of the code remains the same)
    </script>
    </main>
    </div>
    </div>
    </div>

</body>

</html>