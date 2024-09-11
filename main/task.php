<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="../css/output.css">

    <link rel="stylesheet" href="../css/dataTables.dataTables.min.css">

</head>

<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        <?php include '../includes/component/sidebar.php'; ?>

        <!-- Main content -->
        <?php include '../includes/component/main.php'; ?>

        <!-- Section: Dashboard Overview -->


        <div class="p-2">

            <!-- table -->
            <div class="py-4 overflow-hidden">
                <div class="inline-block min-w-full shadow-md rounded-lg overflow-hidden p-4">
                    <div class="flex">
                        <section class="mb-4 w-full">
                            <div class="flex justify-between items-center w-full">
                                <h1 class="text-3xl font-bold text-gray-800"></h1>
                                <div class="flex justify-end">
                                    <button id="createTaskBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 ml-2">Create Task</button>
                                </div>
                            </div>


                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const createTaskBtn = document.getElementById('createTaskBtn');
                                    const closeModalBtn = document.getElementById('closeModalBtn');
                                    const closeModalBtnBottom = document.getElementById('closeModalBtnBottom');
                                    const modal = document.getElementById('modal');
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

                                    createTaskBtn.addEventListener('click', openModal);
                                    closeModalBtn.addEventListener('click', closeModal);
                                    closeModalBtnBottom.addEventListener('click', closeModal);

                                    // Optional: Close modal when clicking outside
                                    modal.addEventListener('click', function(event) {
                                        if (event.target === modal) {
                                            closeModal();
                                        }
                                    });
                                });
                            </script>
                        </section>
                    </div>
                    <table id="taskTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-2 py-3 border-b-2 border-gray-300 text-left hidden">fake id</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Task Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date Added</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Is Done</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider justify-center align-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../includes/functions/db_connect.php';

                            $user_id = $_SESSION['user_id'];

                            $sql = "SELECT * FROM tasks WHERE user_id = ?";
                            $stmt = $conn->prepare($sql);

                            $stmt->bind_param('i', $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200'>";
                                    echo "<td class='hidden'>" . $row['task_id'] . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white truncate' style='max-width: 150px;'>" . $row['task_name'] . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_category'] . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 truncate' style='max-width: 150px;'>" . $row['task_description'] . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_date_added'] . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . $row['task_due_date'] . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>";
                                    $checked = $row['task_is_done'] == 1 ? 'checked' : '';
                                    echo "<input type='checkbox' class='task-checkbox' data-id='" . $row['task_id'] . "' $checked>";
                                    echo "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm'>";
                                    echo "<div class='flex space-x-2'>";
                                    echo "<button type='button' id='editBtn' class='edit-btn bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium py-1 px-2 rounded flex items-center transition duration-150 ease-in-out'
                                    data-id='" . htmlspecialchars($row['task_id']) . "' 
                                    data-title='" . htmlspecialchars($row['task_name']) . "'
                                    data-category='" . htmlspecialchars($row['task_category']) . "'
                                    data-description='" . htmlspecialchars($row['task_description']) . "'>";
                                    echo "<svg class='w-3 h-3 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'></path></svg>";
                                    echo "Edit";
                                    echo "</button>";

                                    echo "<button type='button' id='deleteBtn' class='delete-btn bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-1 px-2 rounded flex items-center transition duration-150 ease-in-out'
                                      data-id='" . htmlspecialchars($row['task_id']) . "'>";
                                    echo "<svg class='w-3 h-3 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'></path></svg>";
                                    echo "Delete";
                                    echo "</button>";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            }

                            ?>



                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- delete modal  -->
        <div id="deleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg w-1/2">
                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b px-6 py-3">
                    <h2 class="text-lg font-semibold">Edit Task</h2>
                    <button id="deleteCloseModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form action="../includes/functions/delete_task.php" method="POST">
                        <!-- Task Title -->
                        <div class="mb-4 bg-red-200 text-red-700 p-4 rounded-lg">
                            <p>Do you wish to delete this task?</p>
                        </div>
                        <input type="text" id="deleteId" name="deleteId" hidden>
                        <!-- Action Buttons -->
                        <div class="flex justify-end">
                            <button type="button" id="deleteCloseModalBottom" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteBtn = document.querySelectorAll('.delete-btn');
                const deleteModal = document.getElementById('deleteModal');
                const deleteCloseModal = document.getElementById('deleteCloseModal');
                const deleteCloseModalBot = document.getElementById('deleteCloseModalBottom');

                const modalContent = deleteModal.querySelector('div');

                const deleteId = document.getElementById('deleteId');

                deleteBtn.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');

                        deleteId.value = id;

                        deleteId.value = id;

                        //open modal
                        deleteModal.classList.remove('opacity-0', 'pointer-events-none');
                        modalContent.classList.remove('scale-95');
                        modalContent.classList.add('scale-100');
                    });
                });

                function closeModal() {
                    deleteModal.classList.add('opacity-0', 'pointer-events-none');
                    modalContent.classList.remove('scale-100');
                    modalContent.classList.add('scale-95');
                }

                deleteCloseModal.addEventListener('click', closeModal);
                deleteCloseModalBot.addEventListener('click', closeModal);
            });
        </script>
        <!-- delete modal  -->

        <!-- Status success alert -->
        <div id="customAlert" class="fixed top-4 right-4 transform translate-x-full transition-transform duration-300 ease-out max-w-sm w-full p-4">
            <div id="modalContent" class="text-center">
                <!-- Content will be inserted here -->
            </div>
        </div>

        <!-- adding confirmation  -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const customAlert = document.getElementById('customAlert');
                const modalContent = document.getElementById('modalContent');

                function showCustomAlert(htmlContent) {
                    modalContent.innerHTML = htmlContent;
                    customAlert.classList.remove('translate-x-full');
                    customAlert.classList.add('translate-x-0');

                    // Automatically close the modal after 3 seconds
                    setTimeout(() => {
                        closeCustomAlert();
                    }, 3000);
                }

                function closeCustomAlert() {
                    customAlert.classList.remove('translate-x-0');
                    customAlert.classList.add('translate-x-full');

                    // Remove the status parameter from the URL
                    const url = new URL(window.location);
                    url.searchParams.delete('status');
                    window.history.replaceState({}, '', url);
                }

                // Check for status query parameter
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');

                if (status === 'success') {
                    showCustomAlert(`
            <div class="bg-green-100 p-2 rounded-lg bg-opacity-80">
                <h2 class="text-lg font-semibold text-green-800">Success</h2>
                <p class="text-green-600">Task added successfully!</p>
            </div>
        `);
                }
            });
        </script>

        <!-- editing confirmation  -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const customAlert = document.getElementById('customAlert');
                const modalContent = document.getElementById('modalContent');

                function showCustomAlert(htmlContent) {
                    modalContent.innerHTML = htmlContent;
                    customAlert.classList.remove('translate-x-full');
                    customAlert.classList.add('translate-x-0');

                    // Automatically close the modal after 3 seconds
                    setTimeout(() => {
                        closeCustomAlert();
                    }, 3000);
                }

                function closeCustomAlert() {
                    customAlert.classList.remove('translate-x-0');
                    customAlert.classList.add('translate-x-full');

                    // Remove the status parameter from the URL
                    const url = new URL(window.location);
                    url.searchParams.delete('status');
                    window.history.replaceState({}, '', url);
                }

                // Check for status query parameter
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');

                if (status === 'edited') {
                    showCustomAlert(`
            <div class="bg-blue-200 p-2 rounded-lg bg-opacity-80">
                <h2 class="text-lg font-semibold text-blue-800">Success</h2>
                <p class="text-blue-600">Task edited successfully!</p>
            </div>
        `);
                }
            });
        </script>

        <!-- deleting confirmation  -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const customAlert = document.getElementById('customAlert');
                const modalContent = document.getElementById('modalContent');

                function showCustomAlert(htmlContent) {
                    modalContent.innerHTML = htmlContent;
                    customAlert.classList.remove('translate-x-full');
                    customAlert.classList.add('translate-x-0');

                    // Automatically close the modal after 3 seconds
                    setTimeout(() => {
                        closeCustomAlert();
                    }, 3000);
                }

                function closeCustomAlert() {
                    customAlert.classList.remove('translate-x-0');
                    customAlert.classList.add('translate-x-full');

                    // Remove the status parameter from the URL
                    const url = new URL(window.location);
                    url.searchParams.delete('status');
                    window.history.replaceState({}, '', url);
                }

                // Check for status query parameter
                const urlParams = new URLSearchParams(window.location.search);
                const status = urlParams.get('status');

                if (status === 'deleted') {
                    showCustomAlert(`
            <div class="bg-red-100 p-2 rounded-lg bg-opacity-80">
                <h2 class="text-lg font-semibold text-red-800">Success</h2>
                <p class="text-red-600">Task deleted successfully!</p>
            </div>
        `);
                }
            });
        </script>



        <!-- edit modal  -->
        <div id="editModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg w-1/2">
                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b px-6 py-3">
                    <h2 class="text-lg font-semibold">Edit Task</h2>
                    <button id="editCloseModal" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form action="../includes/functions/update_task.php" method="POST">
                        <!-- Task Title -->
                        <div class="mb-4">
                            <label for="editTaskTitle" class="block text-gray-700 font-semibold mb-2">Task Name</label>
                            <input type="text" id="editTaskTitle" name="editTaskTitle" class="w-full border border-gray-300 rounded px-3 py-2">
                        </div>

                        <?php
                        include '../includes/functions/db_connect.php'; // Make sure this file connects to your database

                        // Query to fetch categories
                        $query = "SELECT category_id, category_name FROM category";
                        $result = $conn->query($query);

                        if ($result === FALSE) {
                            die("Database query failed: " . $conn->error);
                        }

                        // Generate options for the select element
                        $options = "";
                        while ($row = $result->fetch_assoc()) {
                            $categoryId = htmlspecialchars($row['category_id']);
                            $categoryName = htmlspecialchars($row['category_name']);
                            $options .= "<option value='$categoryName'>$categoryName</option>";
                        }

                        // Close the database connection
                        $conn->close();
                        ?>

                        <div class="mb-4">
                            <label for="editTaskCategory" class="block text-gray-700 font-semibold mb-2">Category</label>
                            <select id="editTaskCategory" name="editTaskCategory" class="w-full border border-gray-300 rounded px-3 py-2">
                                <option value="" disabled selected>Select a Category</option>
                                <?php echo $options; ?>
                            </select>
                        </div>
                        <input type="text" id="editId" name="editId" class="hidden">


                        <!-- Description -->
                        <div class="mb-4">
                            <label for="editDescription" class="block text-gray-700 font-semibold mb-2">Description</label>
                            <textarea id="editDescription" name="editDescription" class="w-full border border-gray-300 rounded px-3 py-2" rows="3"></textarea>
                        </div>


                        <div id="edit-error-message" class="mb-4 bg-red-200 text-red-700 p-4 hidden">

                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end">
                            <button type="button" id="editCloseModalBottom" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editBtn = document.querySelectorAll('.edit-btn');
                const editModal = document.getElementById('editModal');
                const editCloseModal = document.getElementById('editCloseModal');
                const editCloseModalBot = document.getElementById('editCloseModalBottom');

                const modalContent = editModal.querySelector('div');

                const editId = document.getElementById('editId');
                const editTitle = document.getElementById('editTaskTitle');
                const editTaskCategory = document.getElementById('editTaskCategory');
                const editDescription = document.getElementById('editDescription');



                editBtn.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        const title = this.getAttribute('data-title')
                        const category = this.getAttribute('data-category')
                        const description = this.getAttribute('data-description')

                        console.log('click');

                        editId.value = id;
                        editTitle.value = title;
                        editTaskCategory.value = category;
                        editDescription.value = description;

                        editModal.classList.remove('opacity-0', 'pointer-events-none');
                        modalContent.classList.remove('scale-95');
                        modalContent.classList.add('scale-100');
                    });
                });



                function closeModal() {
                    editModal.classList.add('opacity-0', 'pointer-events-none');
                    modalContent.classList.remove('scale-100');
                    modalContent.classList.add('scale-95');
                }

                editCloseModal.addEventListener('click', closeModal);
                editCloseModalBot.addEventListener('click', closeModal);

                function showEditError(message) {
                    const editError = document.getElementById('edit-error-message');
                    editError.textContent = message;
                    editError.classList.remove('hidden');
                }



            });
        </script>
        <!-- edit modal  -->


        <!-- check box modal  -->
        <div id="checkboxModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg w-1/2">
                <!-- Modal Header -->
                <div class="flex justify-between items-center border-b px-6 py-3">
                    <h2 class="text-lg font-semibold">Task</h2>
                    <button id="checkBoxClose" class="text-gray-500 hover:text-gray-700">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <form action="../includes/functions/task_is_done.php" method="POST">
                        <div id="checkbox-message-check" class="mb-4 bg-red-200 text-red-700 p-4 hidden"></div>
                        <div id="checkbox-message-uncheck" class="mb-4 bg-gray-200  p-4 hidden"></div>
                        <input type="text" id="taskId" name="taskId" class="hidden">
                        <div class="flex justify-end">
                            <button type="button" id="checkBoxCloseBot" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Yes</button>
                        </div>
                    </form>

                </div>
            </div>
            <!-- Action Buttons -->
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.task-checkbox');
                const modal = document.getElementById('checkboxModal');
                const closeModalBtn = document.getElementById('checkBoxClose');
                const closeModalBtnBottom = document.getElementById('checkBoxCloseBot');

                const modalContent = modal.querySelector('div');

                const messageCheck = document.getElementById('checkbox-message-check');
                const messageUncheck = document.getElementById('checkbox-message-uncheck');
                const taskIdInput = document.getElementById('taskId');

                let currentCheckbox = null;

                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent the default checkbox behavior
                        currentCheckbox = this;

                        const taskId = this.getAttribute('data-id');
                        const isCurrentlyChecked = this.checked;

                        // Set the task ID in the input field
                        taskIdInput.value = taskId;

                        // Determine the message based on the current state
                        if (!isCurrentlyChecked) {
                            messageCheck.textContent = 'Are you sure you want to mark this task as not done?';
                            messageCheck.classList.remove('hidden');
                            messageUncheck.classList.add('hidden');
                        } else {
                            messageUncheck.textContent = 'Is this task done?';
                            messageUncheck.classList.remove('hidden');
                            messageCheck.classList.add('hidden');
                        }
                        modal.classList.remove('opacity-0', 'pointer-events-none');
                        modalContent.classList.remove('scale-95');
                        modalContent.classList.add('scale-100');
                    });
                });

                // Close modal functions
                function closeModal() {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    modalContent.classList.remove('scale-100');
                    modalContent.classList.add('scale-95');
                    currentCheckbox = null;
                }

                closeModalBtn.addEventListener('click', closeModal);
                closeModalBtnBottom.addEventListener('click', closeModal);
            });
        </script>
        <!-- check box modal  -->
    </div>
    </div>
    </div>



    <!-- adding modal -->
    <div id="modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-1/2">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b px-6 py-3">
                <h2 class="text-lg font-semibold">Add New Task</h2>
                <button id="closeModalBtn" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form action="../includes/functions/add_task.php" method="POST" onsubmit="return validateForm()">
                    <!-- Task Title -->
                    <div class="mb-4">
                        <label for="taskTitle" class="block text-gray-700 font-semibold mb-2">Task Name</label>
                        <input type="text" id="taskTitle" name="taskTitle" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <?php
                    include '../includes/functions/db_connect.php'; // Make sure this file connects to your database

                    // Query to fetch categories
                    $query = "SELECT category_id, category_name FROM category";
                    $result = $conn->query($query);

                    if ($result === FALSE) {
                        die("Database query failed: " . $conn->error);
                    }

                    // Generate options for the select element
                    $options = "";
                    while ($row = $result->fetch_assoc()) {
                        $categoryId = htmlspecialchars($row['category_id']);
                        $categoryName = htmlspecialchars($row['category_name']);
                        $options .= "<option value='$categoryName'>$categoryName</option>";
                    }

                    $conn->close();
                    ?>

                    <div class="mb-4">
                        <label for="taskCategory" class="block text-gray-700 font-semibold mb-2">Category</label>
                        <select id="taskCategory" name="taskCategory" class="w-full border border-gray-300 rounded px-3 py-2" required>
                            <option value="">Select a Category</option>
                            <?php echo $options; ?>
                        </select>
                    </div>


                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-semibold mb-2">Description</label>
                        <textarea id="description" name="description" class="w-full border border-gray-300 rounded px-3 py-2" rows="3"></textarea>
                    </div>

                    <!-- Due Date -->
                    <div class="mb-4">
                        <label for="dueDate" class="block text-gray-700 font-semibold mb-2">Due Date</label>
                        <input type="date" id="dueDate" name="dueDate" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <div id="error-message" class="mb-4 bg-red-200 text-red-700 p-4 hidden">

                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end">
                        <button type="button" id="closeModalBtnBottom" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // Function to show error messages
        function showError(message) {
            const error = document.getElementById('error-message');
            if (error) {
                error.textContent = message;
                error.classList.remove('hidden');
            } else {
                console.error('Error element not found');
            }
        }


        // Function to validate form inputs
        function validateForm() {
            function getCurrentDate() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            const currentDate = getCurrentDate();

            // Retrieve form elements
            const taskTitle = document.getElementById('taskTitle').value.trim();
            const description = document.getElementById('description').value.trim();
            const dueDate = document.getElementById('dueDate').value.trim();
            const taskCategory = document.getElementById('taskCategory').value;

            console.log('Task Title:', taskTitle);
            console.log('Description:', description);
            console.log('Due Date:', dueDate);
            console.log('Task Category:', taskCategory);
            console.log('Current Date:', currentDate);

            // Check for empty fields
            if (taskTitle === '' || description === '' || dueDate === '') {
                showError('All fields are required');
                return false;
            } else if (dueDate <= currentDate) {
                showError('Due date must be after today.');
                return false;
            }
            return true;
        }

        // Add this function to show errors
        function showError(message) {
            const errorElement = document.getElementById('error-message');
            if (errorElement) {
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
            } else {
                console.error('Error element not found');
            }
        }
    </script>
    </div>


    <script src="../js/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script> -->
    <script src="../js/datatables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#taskTable').DataTable({
                "order": [
                    [0, 'desc']
                ], // Assuming 'task_id' is the first column
                "columnDefs": [{
                        "width": "20%",
                        "targets": 1
                    }, // Set the width of Task Name
                    {
                        "width": "30%",
                        "targets": 3
                    }, // Set the width of Description
                    {
                        "width": "5%",
                        "targets": 6
                    }, // Set the width of Is Done
                    {
                        "width": "12%",
                        "targets": 4
                    }, // Set the date added
                    {
                        "width": "12%",
                        "targets": 5
                    }, // Set the due date
                    {
                        "width": "10%",
                        "targets": 7
                    } // Set the width of Action
                ],
                "autoWidth": false // Disable automatic column width calculation
            });
        });
    </script>



</body>

</html>