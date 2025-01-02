<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @stack('title')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('style')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&display=swap');

        .sticky-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .main-heading {
            font-family: "Space Grotesk", serif;
            font-weight: 900;
        }

        .sub-heading {
            font-family: "Space Grotesk", serif;
            font-weight: 300;
        }
    </style>
</head>

<body>
    <header id="main-header" class="sticky">
        <nav class="bg-white border-gray-200 w-full">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                    {{-- <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" /> --}}
                    <span class="self-center text-2xl font-semibold whitespace-nowrap">Prontowl</span>
                </a>
                <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <a type="button" href="javascript:;"
                        class="text-white bg-custom-green focus:ring-4 focus:outline-none focus:ring-custom-green font-medium rounded-lg text-sm px-4 py-2 text-center hover:bg-white hover:text-custom-green hover:border-custom-green border mr-1 transition-colors duration-300">Sign
                        In</a>
                    <a type="button" href="javascript:;"
                        class="text-white bg-custom-green focus:ring-4 focus:outline-none focus:ring-custom-green font-medium rounded-lg text-sm px-4 py-2 text-center hover:bg-white hover:text-custom-green hover:border-custom-green border transition-colors duration-300">
                        Get started
                    </a>
                    <button data-collapse-toggle="navbar-cta" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 transition-all duration-300 dark:hover:bg-gray-700 dark:focus:ring-gray-600 "
                        aria-controls="navbar-cta" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1 transition-all duration-300"
                    id="navbar-cta">
                        <ul
                            class="flex flex-col font-medium p-4 md:p-0 mt-4 rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 transition-all duration-300">
                            <li
                                class="rounded hover:bg-custom-green hover:text-white text-custom-green transition-colors duration-300">
                                <a href="{{ route('campaigns.discover', ['category' => 'online_course']) }}" class="block p-2 md:p-2 " aria-current="page">Online Course</a>
                            </li>
                            <li
                                class="rounded hover:bg-custom-green hover:text-white text-custom-green transition-colors duration-300">
                                <a href="{{ route('campaigns.discover', ['category' => 'tution']) }}" class="block p-2 md:p-2" aria-current="page">Tuition Fee</a>
                            </li>
                            <li
                                class="rounded hover:bg-custom-green hover:text-white text-custom-green transition-colors duration-300">
                                <a href="{{ route('campaigns.discover', ['category' => 'training']) }}" class="block p-2 md:p-2 " aria-current="page">Training</a>
                            </li>
                            <li
                                class="rounded hover:bg-custom-green hover:text-white text-custom-green transition-colors duration-300">
                                <a href="{{ route('campaigns.discover', ['category' => 'organization']) }}" class="block p-2 md:p-2 " aria-current="page">Organization</a>
                            </li>
                        </ul>
                </div>
            </div>
        </nav>
    </header>
    @yield('slider')
    @yield('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    @yield('script')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('main-header');
            const stickyOffset = header.offsetTop;

            window.addEventListener('scroll', function() {
                if (window.pageYOffset > stickyOffset) {
                    header.classList.add('sticky-header');
                } else {
                    header.classList.remove('sticky-header');
                }
            });

            @if (session('alert'))
                Swal.fire({
                    icon: '{{ session('alert.type') }}',
                    title: '{{ session('alert.message') }}',
                });
            @endif
        });
    </script>
</body>

</html>
