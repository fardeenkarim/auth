<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= \App\Core\CSRF::generate() ?>">
    <title><?= $pageTitle ?? APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title><?= $pageTitle ?? APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              glass: {
                bg: 'rgba(255, 255, 255, 0.1)',
                border: 'rgba(255, 255, 255, 0.2)',
              }
            },
            fontFamily: {
              sans: ['Inter', 'sans-serif'],
            }
          }
        }
      }
    </script>
    
    <style>
        /* Standard CSS Animations to prevent FOUC */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out both;
        }
        
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <style type="text/tailwindcss">
      @layer base {
        body {
          @apply antialiased text-gray-900 bg-gray-50 flex justify-center items-center min-h-screen p-4;
        }
      }

      @layer components {
        /* Clean Card */
        .glass-container {
          @apply bg-white rounded-2xl shadow-xl border border-gray-100 p-8 w-full max-w-[400px] text-center relative overflow-hidden mx-auto;
        }
        
        /* Buttons */
        .btn {
          @apply w-full py-2.5 rounded-lg font-medium text-sm transition-all duration-200 inline-flex justify-center items-center cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2;
        }
        
        .btn-primary {
          @apply bg-zinc-900 text-white hover:bg-zinc-800 focus:ring-zinc-900 shadow-sm border border-transparent;
        }
        
        .btn-secondary {
          @apply bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 focus:ring-gray-200 shadow-sm mt-3;
        }
        
        /* Inputs */
        .input-group {
          @apply mb-5 text-left;
        }
        
        .input-field {
          @apply w-full px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 transition-colors shadow-sm;
        }
        
        .label-text {
          @apply block mb-1.5 text-xs font-medium text-gray-700;
        }
        
        /* Alerts */
        .alert {
          @apply p-3 mb-5 text-sm rounded-lg border font-medium;
        }
        
        .alert-error {
          @apply bg-red-50 text-red-700 border-red-200;
        }
        
        .alert-success {
          @apply bg-green-50 text-green-700 border-green-200;
        }
        
        /* Utils */
        .links {
          @apply text-sm text-gray-500;
        }
        
        .links a {
          @apply text-zinc-900 hover:underline hover:text-black transition-colors;
        }

        /* Modal */
        .modal-overlay {
          @apply fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex justify-center items-center opacity-0 pointer-events-none transition-all duration-200;
        }
        
        .modal-overlay.active {
          @apply opacity-100 pointer-events-auto;
        }
        
        .modal-content {
          @apply bg-white rounded-xl shadow-2xl border border-gray-100 p-6 w-full max-w-sm transform scale-95 transition-all duration-200;
        }
        
        .modal-overlay.active .modal-content {
          @apply scale-100;
        }
      }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased font-sans">
    <div class="glass-container" <?= isset($containerStyle) ? 'style="'.$containerStyle.'"' : '' ?>>
        <?= $content ?>
    </div>
    
    <div id="toast-container" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-[100] flex flex-col gap-2 pointer-events-none"></div>

    <?php if (isset($extraScripts)): ?>
        <?= $extraScripts ?>
    <?php endif; ?>
</body>
</html>
