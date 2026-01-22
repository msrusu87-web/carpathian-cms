<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Carphatian CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    @include('partials.navigation')

    <!-- Page Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-4xl font-bold">My Profile</h1>
            <p class="text-lg mt-2">Manage your account information</p>
        </div>
    </header>

    <!-- Success Message -->
    @if(session('success'))
    <div class="max-w-3xl mx-auto px-4 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Profile Content -->
    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Navigation Tabs -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <a href="{{ route('dashboard') }}" class="py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Orders
                    </a>
                    <a href="{{ route('dashboard.profile') }}" class="py-4 px-1 border-b-2 border-blue-600 font-medium text-sm text-blue-600">
                        Profile
                    </a>
                </nav>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-800">Personal Information</h2>
            </div>
            <form action="{{ route('dashboard.profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($user->email_verified_at)
                        <p class="mt-1 text-sm text-green-600">
                            <i class="fas fa-check-circle mr-1"></i> Email verified
                        </p>
                    @else
                        <p class="mt-1 text-sm text-yellow-600">
                            <i class="fas fa-exclamation-circle mr-1"></i> Email not verified
                        </p>
                    @endif
                </div>

                <!-- Phone (optional) -->
                <div class="mb-6">
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-gray-400">(Optional)</span>
                    </label>
                    <input type="text" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $user->phone ?? '') }}"
                           placeholder="+40 123 456 789"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Member Since -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar mr-2"></i>
                        Member since: <strong>{{ $user->created_at->format('F d, Y') }}</strong>
                    </p>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-user-tag mr-2"></i>
                        Role: <strong>{{ $user->roles->pluck('name')->join(', ') ?: 'Customer' }}</strong>
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Update Link -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mt-6">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-2">Security</h3>
                <p class="text-gray-600 mb-4">Keep your account secure by updating your password regularly.</p>
                <a href="/dashboard/profile/password" class="text-blue-600 hover:text-blue-800 font-semibold">
                    Change Password <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
