<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/dataTables.dataTables.min.css">
</head>

<bo class="bg-gray-100">

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

        $user_id = $_SESSION['user_id'];

        // Query to get the total number of tasks
        $totalTasksQuery = "SELECT COUNT(*) AS total FROM tasks WHERE user_id = ?";
        $totalTasksResult = $conn->prepare($totalTasksQuery);
        $totalTasksResult->bind_param('i', $user_id);
        $totalTasksResult->execute();
        $totalTasksResult->bind_result($totalTasks);
        $totalTasksResult->fetch();
        $totalTasksResult->close();

        // Query to get the count of completed tasks for the user
        $completedTasksQueryTasks = "SELECT COUNT(*) AS completed FROM tasks WHERE user_id = ? AND task_is_done = 1";
        $completedTasksResultTasks = $conn->prepare($completedTasksQueryTasks);
        $completedTasksResultTasks->bind_param('i', $user_id);
        $completedTasksResultTasks->execute();
        $completedTasksResultTasks->bind_result($completedTasks);
        $completedTasksResultTasks->fetch();
        $completedTasksResultTasks->close();

        // Calculate pending tasks
        $pendingTasks = $totalTasks - $completedTasks;
        ?>

     

        <!-- component -->
        <div class="min-w-screen min-h-screen flex bg-gray-100">
            <div class="max-w-7xl w-full mx-auto py-6 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row w-full lg:space-x-2 space-y-2 lg:space-y-0 mb-2 lg:mb-4">

                    <!-- Task Done Widget -->
                    <div class="w-full lg:w-1/3">
                        <div class="widget w-full p-4 rounded-lg bg-white border-l-4 border-green-400">
                            <div class="flex items-center space-x-4">
                                <div class="icon w-14 h-14 flex items-center justify-center bg-green-400 text-white rounded-full">
                                    <i class="far fa-check-circle text-3xl"></i>
                                </div>
                                <div class="flex flex-col justify-center flex-grow">
                                    <div class="text-lg font-semibold"><?php echo $completedTasks; ?></div>
                                    <div class="text-sm text-gray-400">Tasks Done</div>

                                </div>
                                <div class="flex justify-end">
                                    <button id="CompleteBtn" data-tasks="<?php echo $completedTasks; ?>" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-700">View</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tasks Pending Widget -->
                    <div class="w-full lg:w-1/3">
                        <div class="widget w-full p-4 rounded-lg bg-white border-l-4 border-yellow-400">
                            <div class="flex items-center space-x-4">
                                <div class="icon w-14 h-14 flex items-center justify-center bg-yellow-400 text-white rounded-full">
                                    <i class="far fa-clock text-3xl"></i>
                                </div>
                                <div class="flex flex-col justify-center flex-grow">
                                    <div class="text-lg font-semibold"><?php echo $pendingTasks; ?></div>
                                    <div class="text-sm text-gray-400">Tasks Pending</div>
                                </div>
                                <div class="flex justify-end">
                                    <button id="pendingBtn" class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">View</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- No. of Tasks Widget -->
                    <div class="w-full lg:w-1/3">
                        <div class="widget w-full p-4 rounded-lg bg-white border-l-4 border-blue-400">
                            <div class="flex items-center space-x-4">
                                <div class="icon w-14 h-14 flex items-center justify-center bg-blue-400 text-white rounded-full">
                                    <i class="far fa-list-alt text-3xl"></i>
                                </div>
                                <div class="flex flex-col justify-center flex-grow">
                                    <div class="text-lg font-semibold"><?php echo $totalTasks; ?></div>
                                    <div class="text-sm text-gray-400">No. of Tasks in Total</div>
                                </div>
                                <div class="flex justify-end">
                                    <button id="allTaskBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">View</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        </main>
    </div>
    <!-- tasks completed modal -->
    <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-2xl w-11/12 max-w-5xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Completed Tasks</h2>
                <button id="taskCompleted" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table id="completedTask" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left hidden">fake id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task Done Date</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php
                        include '../includes/functions/db_connect.php';

                        $user_id = $_SESSION['user_id'] ?? null;

                        $sql = "SELECT * FROM tasks WHERE task_done_date IS NOT NULL AND user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200'>";
                                echo "<td class='hidden'>" . $row['task_id'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white'>" . $row['task_name'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_category'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_description'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_done_date'] . "</td>";
                                echo "</tr>";
                            }
                        }

                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            // Get modal elements
            const modal = document.getElementById('modal');
            const openModalButton = document.getElementById('CompleteBtn');
            const taskCompleted = document.getElementById('taskCompleted');
            const modalContent = modal.querySelector('div');


            function openModal() {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }

            function closeModal() {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
            }

            openModalButton.addEventListener('click', openModal);
            taskCompleted.addEventListener('click', closeModal);
        });
    </script>

    <!-- tasks pending modal -->
    <div id="pendingModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-2xl w-11/12 max-w-5xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Pending Tasks</h2>
                <button id="pendingCloseModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table id="pendingTask" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left hidden"> id</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task Name</th>
                            <th class=" px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th class=" px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task Due Date</th>

                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php
                        include '../includes/functions/db_connect.php';
                        $user_id = $_SESSION['user_id'] ?? null;
                        $sql = "SELECT * FROM tasks WHERE task_done_date IS NOT NULL AND user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $userId);
                        $result->$stmt->get_result();

                        // Execute the prepared statement
                        if (!$stmt->execute()) {
                            die('Execute failed: ' . htmlspecialchars($stmt->error));
                        }

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='hidden'>" . $row['task_id'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white'>" . $row['task_name'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_category'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_description'] . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_due_date'] . "</td>";
                                echo "</tr>";
                            }
                        }

                        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const pendingModal = document.getElementById('pendingModal');
            const pendingCloseModal = document.getElementById('pendingCloseModal');
            const pendingBtn = document.getElementById('pendingBtn');
            const modalContent = pendingModal.querySelector('div');

            function openModal() {
                pendingModal.classList.remove('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }

            function closeModal() {
                pendingModal.classList.add('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
            }

            pendingBtn.addEventListener('click', openModal);
            pendingCloseModal.addEventListener('click', closeModal);
        });
    </script>

    <!-- all task modal -->
    <div id="allTaskModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 ease-out">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-2xl w-11/12 max-w-5xl transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">All Tasks</h2>
                <button id="allTaskCloseModal" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="overflow-x-auto">
                <?php
                include '../includes/functions/db_connect.php';
                $hasStatusColumn = false;
                $user_id = $_SESSION['user_id'] ?? null;
                $sql = "SELECT * FROM tasks WHERE task_done_date IS NOT NULL AND user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $userId);
                $result->$stmt->get_result();

                // Execute the prepared statement
                if (!$stmt->execute()) {
                    die('Execute failed: ' . htmlspecialchars($stmt->error));
                }
                // Check if any task has a 'task_is_done' value
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if (!is_null($row['task_is_done']) && $row['task_is_done'] !== '') {
                            $hasStatusColumn = true;
                            break;  // Stop the loop once a non-null 'task_is_done' is found
                        }
                    }
                }
                ?>
                <table id="allTask" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        <?php
                        $result->data_seek(0);  // Reset the result pointer
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200'>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white'>" . htmlspecialchars($row['task_name']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . htmlspecialchars($row['task_category']) . "</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . htmlspecialchars($row['task_description']) . "</td>";

                                echo "<td class='px-6 py-4 whitespace-nowrap text-sm'>";
                                if (is_null($row['task_is_done']) || $row['task_is_done'] === '') {
                                    echo "<span class='px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-200 dark:text-gray-900'>N/A</span>";
                                } elseif ($row['task_is_done'] == 1) {
                                    echo "<span class='px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-200 dark:text-green-900'>Done</span>";
                                } else {
                                    echo "<span class='px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-200 dark:text-red-900'>Pending</span>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            // Ensure the message spans all columns, including 'Status'
                            echo "<tr><td colspan='4' class='px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300'>No tasks found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>



            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const allTaskModal = document.getElementById('allTaskModal');
            const allTaskCloseModal = document.getElementById('allTaskCloseModal');
            const allTaskBtn = document.getElementById('allTaskBtn');
            const modalContent = allTaskModal.querySelector('div')

            function openModal() {
                allTaskModal.classList.remove('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }

            function closeModal() {
                allTaskModal.classList.add('opacity-0', 'pointer-events-none');
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
            }

            allTaskBtn.addEventListener('click', openModal);
            allTaskCloseModal.addEventListener('click', closeModal);


        });
    </script>


    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#completedTask').DataTable({
                "order": [
                    [0, 'desc']
                ] // Assuming 'task_id' is the first column
            });
        });
        $(document).ready(function() {
            $('#pendingTask').DataTable({
                "order": [
                    [0, 'desc']
                ] // Assuming 'task_id' is the first column
            });
        });
        $(document).ready(function() {
            $('#allTask').DataTable({
                "columns": [{
                        "data": "task_name"
                    },
                    {
                        "data": "task_category"
                    },
                    {
                        "data": "task_description"
                    },
                    {
                        "data": "task_is_done"
                    } // Define a column for the status
                ],
                "columnDefs": [{
                        "targets": 0,
                        "orderable": true
                    }, // Task Name
                    {
                        "targets": 1,
                        "orderable": true
                    }, // Category
                    {
                        "targets": 2,
                        "orderable": true
                    }, // Description
                    {
                        "targets": 3,
                        "orderable": false
                    } // Status
                ],
                "data": function(d) {
                    // Convert server data format to DataTables format if necessary
                }
            });
        });
    </script>
    </body>
    </html?