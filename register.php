<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Loopple/loopple-public-assets@main/motion-tailwind/motion-tailwind.css" rel="stylesheet">

    <title>Register</title>
</head>

<body class="rounded-lg overflow-hidden bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500">

    <div class=" flex flex-col mx-auto p-2 ">
        <div class="flex justify-center w-full h-full my-auto xl:gap-14 lg:justify-normal md:gap-5 draggable">
            <div class="flex items-center justify-center w-full lg:p-12">
                <div class="flex items-center xl:p-10">
                    <form class="flex flex-col w-full max-w-lg p-6 text-center bg-white rounded-3xl shadow-lg" action="includes/functions/register.php" method="POST">
                        <img class="h-32 w-auto mx-auto mb-4" src="img/task_logo.png" alt="Logo" />
                        <hr class="h-0 border-b mb-4 border-solid border-gray-200 grow">

                        <h3 class="mb-8 text-3xl font-extrabold text-gray-900">Sign Up</h3>

                        <div class="flex flex-wrap gap-4 mb-6">
                            <div class="flex-1">
                                <label for="firstName" class="block text-left mb-2 text-sm text-gray-900">First Name*</label>
                                <input id="firstName" type="text" name="firstName" placeholder="Enter first name" class="w-full px-5 py-4 text-sm font-medium outline-none focus:bg-gray-400 placeholder:text-gray-700 bg-gray-200 text-gray-900 rounded-2xl" required />
                            </div>
                            <div class="flex-1">
                                <label for="lastName" class="block text-left mb-2 text-sm text-gray-900">Last Name*</label>
                                <input id="lastName" type="text" name="lastName" placeholder="Enter last name" class="w-full px-5 py-4 text-sm font-medium outline-none focus:bg-gray-400 placeholder:text-gray-700 bg-gray-200 text-gray-900 rounded-2xl" required />
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4 mb-6">
                            <div class="flex-1">
                                <label for="email" class="block text-left mb-2 text-sm text-gray-900">Email*</label>
                                <input id="email" type="email" name="email" placeholder="mail@loopple.com" class="w-full px-5 py-4 text-sm font-medium outline-none focus:bg-gray-400 placeholder:text-gray-700 bg-gray-200 text-gray-900 rounded-2xl" required />
                            </div>
                            <div class="flex-1">
                                <label for="password" class="block text-left mb-2 text-sm text-gray-900">Password*</label>
                                <input id="password" type="password" name="password" placeholder="Enter a password" class="w-full px-5 py-4 text-sm font-medium outline-none focus:bg-gray-400 placeholder:text-gray-700 bg-gray-200 text-gray-900 rounded-2xl" required />
                            </div>
                        </div>

                        <button class="w-full px-6 py-5 mb-5 text-sm font-bold leading-none text-white transition duration-300 rounded-2xl hover:bg-purple-blue-600 focus:ring-4 focus:ring-purple-blue-100 bg-purple-blue-500">Sign Up</button>
                        <p class="text-sm leading-relaxed text-gray-900">Already signed up? <a href="index.php" class="font-bold text-gray-700">Sign In</a></p>
                    </form>


                </div>
            </div>
        </div>
    </div>



</body>

</html>