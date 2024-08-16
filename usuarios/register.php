<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/81581fb069.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Registro</h2>
        <form action="procesar_registro.php" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nombre:</label>
                <input type="text" class="w-full p-2 border border-gray-300 rounded mt-1" id="name" name="name" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" class="w-full p-2 border border-gray-300 rounded mt-1" id="email" name="email" required>
            </div>
            <div class="mb-4 relative">
                <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña:</label>
                <div class="relative">
                    <input type="password" class="w-full p-2 border border-gray-300 rounded mt-1 pr-10" id="password" name="password" required>
                    <i class="fa fa-eye absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer" id="togglePassword"></i>
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Registrarse</button>
        </form>
    </div>

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // Toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // Toggle the eye slash icon
            this.classList.toggle("fa-eye-slash");
        });
    </script>

</body>
</html>
