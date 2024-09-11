<div class="flex-1 overflow-auto">
    <header class="bg-white  shadow">
        <div class="flex items-center justify-between p-4">
            <div class="flex items-center">
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $page_titles = [
                    'dashboard.php' => 'Dashboard',
                    'task.php' => 'Tasks',
                    'categories.php' => 'Categories',
                    'statistics.php' => 'Statistics'
                ];
                ?>

                <h1 class="text-2xl font-bold text-gray-900">
                    <?php echo isset($page_titles[$current_page]) ? $page_titles[$current_page] : ''; ?>
                </h1>

            </div>
            <div class="flex items-center">
                <?php
                session_start();

                // Check if the user is logged in
                if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                    header("Location:  ../index.php");
                    exit();
                }

                $firstName = htmlspecialchars($_SESSION['user_first_name']);
                $lastName = htmlspecialchars($_SESSION['user_last_name']);
                ?>


                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const customAlert = document.getElementById('customAlert');
                        const modalContent = document.getElementById('modalContent');

                        function showCustomAlert(htmlContent) {
                            modalContent.innerHTML = htmlContent;
                            customAlert.classList.remove('translate-x-full');
                            customAlert.classList.add('translate-x-0');

                            // Automatically close the alert after 3 seconds
                            setTimeout(() => {
                                closeCustomAlert();
                            }, 3000);
                        }

                        function closeCustomAlert() {
                            customAlert.classList.remove('translate-x-0');
                            customAlert.classList.add('translate-x-full');

                            const url = new URL(window.location);
                            url.searchParams.delete('status');
                            window.history.replaceState({}, '', url);
                        }

                        // Check for status query parameter
                        const urlParams = new URLSearchParams(window.location.search);
                        const status = urlParams.get('status');

                        if (status === 'loggedin') {
                            const firstName = <?php echo json_encode($firstName); ?>;
                            const lastName = <?php echo json_encode($lastName); ?>;

                            showCustomAlert(`
                <div class="bg-green-100 p-2 rounded-lg bg-opacity-80">
                    <h2 class="text-lg font-semibold text-green-800">Logged In</h2>
                    <p class="text-green-600">Welcome ${firstName} ${lastName}!</p>
                </div>
            `);
                        }
                    });
                </script>

                <div id="customAlert" class="fixed top-4 right-4 transform translate-x-full transition-transform duration-300 ease-out max-w-sm w-full p-4">
                    <div id="modalContent" class="text-center">
                        <!-- Content will be inserted here -->
                    </div>
                </div>

                <a href="/" id="logoutButton" class="inline-block bg-indigo-600 text-white px-5 hover:bg-indigo-700 py-2.5 rounded-md text-sm font-medium shadow ">Log out</a>

            </div>
        </div>
        <!-- Logout Modal -->
        <!-- Logout Modal -->
        <!-- Modal Backdrop -->
        <div id="logoutModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300 z-50">
            <!-- Modal Content -->
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full transform scale-95 transition-transform duration-300">
                <form action="../includes/functions/logout.php" method="POST">
                    <h3 class="text-lg font-semibold mb-4">Log Out</h3>
                    <p class="mb-4">Are you sure you want to log out?</p>
                    <div class="flex justify-end gap-4">
                        <button id="cancelLogout" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-300">
                            Cancel
                        </button>
                        <button id="confirmLogout" type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700">
                            Log Out
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const logoutButton = document.getElementById('logoutButton'); // Ensure this button exists
                const logoutModal = document.getElementById('logoutModal');
                const cancelLogout = document.getElementById('cancelLogout');
                const confirmLogout = document.getElementById('confirmLogout');

                // Show the modal with transition
                logoutButton.addEventListener('click', (e) => {
                    e.preventDefault(); // Prevent default link behavior
                    logoutModal.classList.remove('hidden');
                    setTimeout(() => {
                        logoutModal.classList.remove('opacity-0', 'scale-95');
                        logoutModal.classList.add('opacity-100', 'scale-100');
                    }, 10); // Small delay for the transition to kick in
                });

                // Hide the modal with transition
                cancelLogout.addEventListener('click', (e) => {
                    e.preventDefault(); // Prevent default button behavior
                    logoutModal.classList.remove('opacity-100', 'scale-100');
                    logoutModal.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        logoutModal.classList.add('hidden');
                    }, 300); // Match duration with transition duration
                });

                // Optionally, hide modal when clicking outside of it
                logoutModal.addEventListener('click', (e) => {
                    if (e.target === logoutModal) { // Clicked on the backdrop
                        cancelLogout.click(); // Trigger cancel action
                    }
                });
            });
        </script>


    </header>