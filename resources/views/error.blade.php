<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-2xl shadow-lg p-10 w-full max-w-md text-center">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Payment Failed!</h1>
        <p class="text-slate-500 mb-4">Something went wrong with your payment.</p>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6">
                <p class="text-red-600 text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <a href="/" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl transition-all">
            Try Again
        </a>
    </div>

</body>
</html>