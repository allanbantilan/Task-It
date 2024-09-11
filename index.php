<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/Loopple/loopple-public-assets@main/motion-tailwind/motion-tailwind.css" rel="stylesheet">

    <title>Login</title>
</head>

<body class="overflow-hidden relative bg-gradient-to-r from-blue-400 via-purple-500 to-pink-500">



    <div class="flex flex-col mx-auto">
        <div class="flex justify-center  w-full h-full my-auto  gap-5 draggable">
            <div class="flex items-center justify-center  w-full lg:p-12">
                <div class="flex items-center xl:p-10 ">

                    <form class="flex flex-col w-full h-full p-6 text-center bg-white rounded-3xl shadow-lg" action="includes/functions/login.php" method="POST">

                        <img class="h-32 w-auto mx-auto mb-2" src="img/task_logo.png" alt="Logo" />
                        <hr class="h-0 border-b mb-4 border-solid border-gray-200 grow">

                        <h3 class="mb-3 text-4xl font-extrabold text-gray-900">Sign In</h3>
                        <p class="mb-4 text-gray-700">Enter your email and password</p>
                        <label for="email" class="mb-2 text-sm text-start text-gray-900">Email*</label>
                        <input id="email" name="email" type="email" placeholder="mail@loopple.com" class="flex items-center w-full px-5 py-4 mr-2 text-sm font-medium outline-none focus:bg-gray-400 mb-7 placeholder:text-gray-700 bg-gray-200 text-gray-900 rounded-2xl" required />
                        <label for="password" class="mb-2 text-sm text-start text-gray-900">Password*</label>
                        <input id="password" name="password" type="password" placeholder="Enter a password" class="flex items-center w-full px-5 py-4 mb-5 mr-2 text-sm font-medium outline-none focus:bg-gray-400 placeholder:text-gray-700 bg-gray-200 text-gray-900 rounded-2xl" required />
                        <div class="flex flex-row justify-between mb-8">
                            <label class="relative inline-flex items-center mr-3 cursor-pointer select-none">
                                <input type="checkbox" checked value="" class="sr-only peer">
                                <div class="w-5 h-5 bg-white border-2 rounded-sm border-gray-500 peer peer-checked:border-0 peer-checked:bg-purple-blue-500">
                                    <img class="" src="https://raw.githubusercontent.com/Loopple/loopple-public-assets/main/motion-tailwind/img/icons/check.png" alt="tick">
                                </div>
                                <span class="ml-3 text-sm font-normal text-gray-900">Keep me logged in</span>
                            </label>
                            <a href="javascript:void(0)" class="mr-4 text-sm font-medium text-purple-blue-500">Forget password?</a>
                        </div>
                        <button class="w-full px-6 py-5 mb-5 text-sm font-bold leading-none text-white transition duration-300 md:w-96 rounded-2xl hover:bg-purple-blue-600 focus:ring-4 focus:ring-purple-blue-100 bg-purple-blue-500">Sign In</button>
                        <p class="text-sm leading-relaxed text-gray-900">Not registered yet? <a href="register.php" class="font-bold text-gray-700">Create an Account</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>



</body>

</html>