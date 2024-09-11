<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/dataTables.dataTables.min.css">
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
        <div class="p-2">

            <!-- table -->
            <div class="py-4 overflow-hidden">
                <div class="inline-block min-w-full shadow-md rounded-lg overflow-hidden p-4">
                    <div class="flex">
                        <section class="mb-4 w-full">
                            <div class="flex justify-between items-center w-full">
                                <h1 class="text-3xl font-bold text-gray-800"></h1>
                                <div class="flex justify-end">
                                    <button id="addCategoryBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 ">Add Category</button>

                                </div>
                            </div>



                        </section>
                    </div>
                    <table id="categoryTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category Id</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider justify-center align-center">Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include '../includes/functions/db_connect.php';

                            // Start the session and get the user ID
                          
                            $user_id = $_SESSION['user_id'] ?? 0; // Default to 0 if user_id is not set

                            // Prepare the SQL statement to get default categories and user-specific categories
                            $sql = "SELECT * FROM category WHERE user_id = 0 OR user_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('i', $user_id);

                            // Execute the prepared statement and get the result
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Check if there are categories and display them
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr class='hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200'>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white'>" . htmlspecialchars($row['category_id']) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300'>" . htmlspecialchars($row['category_name']) . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm'>";
                                    echo "<div class='flex space-x-2 justify-center align-center'>";

                                    echo "<button type='button' class='edit-btn bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium py-2 px-4 rounded flex items-center transition duration-150 ease-in-out' 
                data-id='" . htmlspecialchars($row['category_id']) . "' 
                data-title='" . htmlspecialchars($row['category_name']) . "'>";
                                    echo "<svg class='w-3 h-3 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'></path></svg>";
                                    echo "Edit";
                                    echo "</button>";

                                    echo "<button type='button' id='deleteBtn' class='delete-btn bg-red-500 hover:bg-red-600 text-white text-xs font-medium py-2 px-4 rounded flex items-center transition duration-150 ease-in-out'
                data-id='" . htmlspecialchars($row['category_id']) . "'>";
                                    echo "<svg class='w-3 h-3 mr-1' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'></path></svg>";
                                    echo "Delete";
                                    echo "</button>";
                                    echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' class='px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300'>No categories found</td></tr>";
                            }

                            // Close the statement and connection
                            $stmt->close();
                            $conn->close();
                            ?>
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
        <!-- Status success alert -->
        <div id="customAlert" class="fixed top-4 right-4 transform translate-x-full transition-transform duration-300 ease-out max-w-sm w-full p-4">
            <div id="modalContent" class="text-center">
                <!-- Content will be inserted here -->
            </div>
        </div>
        <!-- add category confirmation  -->
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

                if (status === 'addCategory') {
                    showCustomAlert(`
            <div class="bg-green-100 p-2 rounded-lg bg-opacity-80">
                <h2 class="text-lg font-semibold text-green-800">Success</h2>
                <p class="text-green-600">Category Added successfully!</p>
            </div>
        `);
                }
            });
        </script>

        <!-- edit category confirmation  -->
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

                if (status === 'editCategory') {
                    showCustomAlert(`
            <div class="bg-blue-100 p-2 rounded-lg bg-opacity-80">
                <h2 class="text-lg font-semibold text-blue-800">Success</h2>
                <p class="text-blue-600">Category Edited successfully!</p>
            </div>
        `);
                }
            });
        </script>

        <!-- delete category confirmation  -->
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
                <p class="text-red-600">Category Deleted successfully!</p>
            </div>
        `);
                }
            });
        </script>



    </div>
    <!-- category modal  -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-1/2">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b px-6 py-3">
                <h2 class="text-lg font-semibold">Add Category</h2>
                <button id="categoryCloseModal" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form action="../includes/functions/add_category.php" method="POST" onsubmit="return validateForm()">
                    <!-- Task Title -->
                    <div class="mb-4">
                        <label for="" class="block text-gray-700 font-semibold mb-2">Category Name</label>
                        <input type="text" id="category" name="category" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <input type="text" id="categoryID" class="hidden">
                    <div id="edit-error-message" class="mb-4 bg-red-200 text-red-700 p-4 hidden"></div>
                    <!-- Action Buttons -->
                    <div class="flex justify-end">
                        <button type="button" id="CategoryCloseModalBtnBottom" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categoryModal = document.getElementById('categoryModal');
            const closeModalCategory = document.getElementById('categoryCloseModal');
            const closeModalBtn = document.getElementById('CategoryCloseModalBtnBottom');
            const addCategoryBtn = document.getElementById('addCategoryBtn');


            addCategoryBtn.addEventListener('click', function() {
                categoryModal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
                categoryModal.classList.add('scale-100');
            });


            function closeModal() {
                categoryModal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
                categoryModal.classList.remove('scale-100');
            }

            closeModalCategory.addEventListener('click', closeModal);
            closeModalBtn.addEventListener('click', closeModal);

        });

        function showErrorCategory(message) {
            const error = document.getElementById('edit-error-message');
            error.textContent = message;
            error.classList.remove('hidden');
        }

        function validateForm() {
            const categoryName = document.getElementById('category').value.trim();
            if (categoryName === '' || /^\d+$/.test(categoryName)) {
                showErrorCategory('Please enter a valid category name.');
                return false;
            }
            return true;
        }
    </script>

    <!-- edit modal  -->
    <div id="editCategory" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-1/2">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b px-6 py-3">
                <h2 class="text-lg font-semibold">Add Category</h2>
                <button id="closeEditTwo" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form action="../includes/functions/update_category.php" method="POST" onsubmit="return validateFormEdit()">
                    <!-- Task Title -->
                    <div class="mb-4">
                        <label for="categoryEdit" class="block text-gray-700 font-semibold mb-2">Category Name</label>
                        <input type="text" id="categoryEdit" name="categoryEdit" class="w-full border border-gray-300 rounded px-3 py-2">
                    </div>
                    <input type="text" id="categoryEditId" name="categoryEditId" class="hidden">
                    <div id="category-error-message" class="mb-4 bg-red-200 text-red-700 p-4 hidden"></div>
                    <!-- Action Buttons -->
                    <div class="flex justify-end">
                        <button type="button" id="closeEdit" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categoryModalEdit = document.getElementById('editCategory');
            const closeModalCategoryEdit = document.getElementById('closeEdit');
            const closeModalBtnEdit = document.getElementById('closeEditTwo');
            const editBtn = document.querySelectorAll('.edit-btn');



            editBtn.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const categoryName = btn.getAttribute('data-title');

                    // Populate the form fields
                    document.getElementById('categoryEditId').value = id;
                    document.getElementById('categoryEdit').value = categoryName;

                    // Show the modal
                    categoryModalEdit.classList.remove('opacity-0', 'pointer-events-none');
                    categoryModalEdit.classList.add('scale-100');
                    categoryModalEdit.classList.remove('scale-95');
                });
            });

            function closeModal() {
                categoryModalEdit.classList.add('opacity-0', 'pointer-events-none');
                categoryModalEdit.classList.remove('scale-100');
                categoryModalEdit.classList.add('scale-95');
            }

            closeModalCategoryEdit.addEventListener('click', closeModal);
            closeModalBtnEdit.addEventListener('click', closeModal);


        });

        function showErrorCategoryEdit(message) {
            const error = document.getElementById('category-error-message');
            error.textContent = message;
            error.classList.remove('hidden');
        }

        function validateFormEdit() {
            const category = document.getElementById('categoryEdit').value.trim();
            if (category === '' || /^\d+$/.test(category)) {
                showErrorCategoryEdit('Please put a valid category.')
                return false;
            }
            return true;
        }
    </script>

    <!-- delete modal  -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 ease-out">
        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-1/2">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b px-6 py-3">
                <h2 class="text-lg font-semibold">Add Category</h2>
                <button id="deleteCloseModal" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form action="../includes/functions/delete_category.php" method="POST">
                    <!-- Task Title -->
                    <div class="mb-4 bg-red-200 text-red-700 p-4 rounded-lg">
                        <p>Do you wish to delete this category?</p>
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
        document.addEventListener('DOMContentLoaded', () => {
            const deleteModal = document.getElementById('deleteModal');
            const closeModaldelete = document.getElementById('deleteCloseModal');
            const closeModalBtn = document.getElementById('deleteCloseModalBottom');

            const deleteBtn = document.querySelectorAll('.delete-btn');
            const deleteId = document.getElementById('deleteId');

            deleteBtn.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');

                    deleteId.value = id;

                    deleteModal.classList.remove('opacity-0', 'pointer-events-none');
                    deleteModal.classList.add('scale-100');
                    deleteModal.classList.remove('scale-95');


                });
            });

            function closeModal() {
                deleteModal.classList.add('opacity-0', 'pointer-events-none');
                deleteModal.classList.remove('scale-100');
                deleteModal.classList.add('scale-95');
            }

            closeModaldelete.addEventListener('click', closeModal);
            closeModalBtn.addEventListener('click', closeModal);


        });
    </script>

</body>
<script src="../js/jquery-3.6.0.min.js"></script>
<!-- <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script> -->
<script src="../js/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#categoryTable').DataTable({
            "order": [
                [0, 'desc']
            ], // Assuming 'task_id' is the first column

        });
    });
</script>

</html>