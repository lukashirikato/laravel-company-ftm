<!-- filepath: c:\Users\hp\Desktop\progres\progres\resources\views\feedback.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTM - Customer Feedback</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-100 to-blue-300 min-h-screen">
    <!-- Header Menu -->
    <nav class="bg-white shadow mb-8">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-blue-700">FTM Admin Panel</h1>
            <div class="flex gap-4">
                <a href="{{ route('admin.home') }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">Home</a>
                <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Customers</a>
                <a href="{{ route('schedules.index') }}" class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">Schedules</a>
            </div>
        </div>
    </nav>
    <div class="container mx-auto p-5">
        <h1 class="text-3xl font-bold text-blue-700 mb-8 text-center">Customer Feedback</h1>
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-2xl mx-auto">
            @if($feedbacks->isEmpty())
                <p class="text-gray-500 text-center">No feedback available.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($feedbacks as $feedback)
                        <li class="py-4">
                            <div class="flex items-center mb-2">
                                <div class="bg-blue-200 text-blue-700 rounded-full w-10 h-10 flex items-center justify-center font-bold mr-3">
                                    {{ strtoupper(substr($feedback->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-blue-700">{{ $feedback->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $feedback->email }}</p>
                                    <p class="text-xs text-gray-400 italic">{{ $feedback->subject }}</p>
                                </div>
                            </div>
                            <p class="text-gray-700 italic pl-12">"{{ $feedback->message }}"</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</body>
</html>