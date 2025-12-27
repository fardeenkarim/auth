<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <!-- Alpine.js for interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Mobile Header -->
        <div
            class="md:hidden bg-white border-b border-gray-200 p-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-2 font-bold text-lg">
                <div class="w-8 h-8 bg-zinc-900 text-white rounded-lg flex items-center justify-center text-sm">W</div>
                Web Makeer
            </div>
            <button @click="sidebarOpen = !sidebarOpen"
                class="text-gray-500 hover:text-zinc-900 focus:outline-none p-1 rounded-md hover:bg-gray-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Sidebar Backdrop -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
            class="fixed inset-0 bg-gray-900/50 z-30 md:hidden"></div>

        <!-- Sidebar -->
        <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
            class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-40 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:h-screen flex flex-col">

            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <div class="flex items-center gap-2 font-bold text-xl tracking-tight">
                    <div
                        class="w-8 h-8 bg-zinc-900 text-white rounded-lg flex items-center justify-center text-sm shadow-sm">
                        W</div>
                    <span>Web Makeer</span>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-2">Main</p>

                <a href="<?= BASE_URL ?>/dashboard"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg bg-zinc-50 text-zinc-900 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-500 group-hover:text-zinc-900"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Dashboard
                </a>

                <a href="<?= BASE_URL ?>/customers"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-600 hover:text-zinc-900 hover:bg-gray-50 group transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-zinc-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Customers
                </a>

                <a href="<?= BASE_URL ?>/leads"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-600 hover:text-zinc-900 hover:bg-gray-50 group transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-zinc-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Leads
                    <span
                        class="ml-auto bg-zinc-100 text-zinc-600 py-0.5 px-2 rounded-full text-xs font-medium">8</span>
                </a>

                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Settings</p>

                <a href="<?= BASE_URL ?>/profile"
                    class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-gray-600 hover:text-zinc-900 hover:bg-gray-50 group transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-zinc-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
            </nav>

            <div class="p-4 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-600 font-bold border border-zinc-200">
                        <?= strtoupper(substr($user['username'] ?? 'U', 0, 1)) ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            <?= htmlspecialchars($user['username'] ?? 'User') ?>
                        </p>
                        <p class="text-xs text-gray-500 truncate">Administrator</p>
                    </div>
                    <a href="<?= BASE_URL ?>/logout" class="text-gray-400 hover:text-red-500 transition-colors p-1"
                        title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto h-screen bg-gray-50">
            <!-- Header -->
            <header
                class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-6 sticky top-0 z-10">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Dashboard</h1>
                </div>
                <div class="flex items-center gap-4">
                    <button class="text-gray-400 hover:text-gray-600 transition-colors relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>

                    <a href="<?= BASE_URL ?>/customers/add"
                        class="hidden sm:inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-zinc-900 hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New Customer
                    </a>
                </div>
            </header>

            <div class="p-6 max-w-7xl mx-auto">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Stat Card 1 -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-gray-300 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Revenue
                                </p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1">$48,500</h3>
                            </div>
                            <div class="p-2 bg-green-50 rounded-lg text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                12.5%
                            </span>
                            <span class="text-gray-400 ml-2">vs last month</span>
                        </div>
                    </div>

                    <!-- Stat Card 2 -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-gray-300 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Active Customers
                                </p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1">1,284</h3>
                            </div>
                            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                8.2%
                            </span>
                            <span class="text-gray-400 ml-2">vs last month</span>
                        </div>
                    </div>

                    <!-- Stat Card 3 -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-gray-300 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">New Leads</p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1">342</h3>
                            </div>
                            <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-green-600 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                5.3%
                            </span>
                            <span class="text-gray-400 ml-2">vs last month</span>
                        </div>
                    </div>

                    <!-- Stat Card 4 -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-gray-300 transition-colors">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending Tasks
                                </p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1">12</h3>
                            </div>
                            <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-red-500 font-medium flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                </svg>
                                -2
                            </span>
                            <span class="text-gray-400 ml-2">since yesterday</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Recent Activity -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Recent Activity</h3>
                            <a href="#"
                                class="text-sm font-medium text-zinc-600 hover:text-zinc-900 hover:underline">View
                                all</a>
                        </div>

                        <div class="space-y-6">
                            <div class="flex gap-4">
                                <div class="relative flex-shrink-0">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 ring-4 ring-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"
                                        aria-hidden="true"></span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">New customer added</p>
                                    <p class="text-sm text-gray-500">Sarah Smith was added by <span
                                            class="font-medium text-gray-700">You</span></p>
                                    <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="relative flex-shrink-0">
                                    <div
                                        class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 ring-4 ring-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200"
                                        aria-hidden="true"></span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Deal closed</p>
                                    <p class="text-sm text-gray-500">Project Phoenix contract signed ($12,000)</p>
                                    <p class="text-xs text-gray-400 mt-1">5 hours ago</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="relative flex-shrink-0">
                                    <div
                                        class="h-10 w-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600 ring-4 ring-white">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">New inquiry received</p>
                                    <p class="text-sm text-gray-500">Contact from Tech Giants Inc. regarding partnership
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">1 day ago</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Quick Actions</h3>

                        <div class="space-y-3">
                            <a href="<?= BASE_URL ?>/customers/add"
                                class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-zinc-300 hover:shadow-sm transition-all group">
                                <div
                                    class="h-10 w-10 rounded-lg bg-zinc-50 text-zinc-700 flex items-center justify-center group-hover:bg-zinc-900 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">New Customer</p>
                                    <p class="text-xs text-gray-500">Add a client to the database</p>
                                </div>
                            </a>

                            <a href="<?= BASE_URL ?>/deals/add"
                                class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-zinc-300 hover:shadow-sm transition-all group">
                                <div
                                    class="h-10 w-10 rounded-lg bg-zinc-50 text-zinc-700 flex items-center justify-center group-hover:bg-zinc-900 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Create Proposal</p>
                                    <p class="text-xs text-gray-500">Draft a new sales proposal</p>
                                </div>
                            </a>

                            <a href="<?= BASE_URL ?>/users/invite"
                                class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-zinc-300 hover:shadow-sm transition-all group">
                                <div
                                    class="h-10 w-10 rounded-lg bg-zinc-50 text-zinc-700 flex items-center justify-center group-hover:bg-zinc-900 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Invite Team</p>
                                    <p class="text-xs text-gray-500">Add members to your workspace</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-auto py-6 text-center text-sm text-gray-400">
                &copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.
            </footer>
        </main>
    </div>

    <!-- No custom JS needed, Alpine.js handles interactions -->
</body>

</html>