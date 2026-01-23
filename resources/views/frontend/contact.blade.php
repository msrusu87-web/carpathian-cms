@extends('layouts.frontend')

@section('title', 'Contact - Carpathian CMS Demo')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 py-16">
    <div class="container mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Contact Us</h1>
        <p class="text-gray-300 text-lg max-w-2xl mx-auto">Get in touch with our team</p>
    </div>
</section>

<!-- Contact Form -->
<section class="py-12">
    <div class="container mx-auto px-6">
        <div class="max-w-2xl mx-auto">
            <div class="bg-slate-800 rounded-2xl border border-slate-700 p-8">
                <form action="/contact" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-300 font-medium mb-2">Name</label>
                            <input type="text" name="name" required class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition-colors" placeholder="Your name">
                        </div>
                        <div>
                            <label class="block text-gray-300 font-medium mb-2">Email</label>
                            <input type="email" name="email" required class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition-colors" placeholder="your@email.com">
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-300 font-medium mb-2">Subject</label>
                        <input type="text" name="subject" required class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition-colors" placeholder="How can we help?">
                    </div>
                    <div>
                        <label class="block text-gray-300 font-medium mb-2">Message</label>
                        <textarea name="message" rows="5" required class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-1 focus:ring-purple-500 outline-none transition-colors resize-none" placeholder="Your message..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-pink-600 text-white py-3 rounded-lg font-semibold hover:opacity-90 transition-all">
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 text-center">
                    <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-purple-400 text-xl"></i>
                    </div>
                    <h3 class="text-white font-semibold mb-2">Email</h3>
                    <p class="text-gray-400 text-sm">demo@qubitpage.com</p>
                </div>
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 text-center">
                    <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-globe text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-white font-semibold mb-2">Website</h3>
                    <p class="text-gray-400 text-sm">qubitpage.com</p>
                </div>
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 text-center">
                    <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-green-400 text-xl"></i>
                    </div>
                    <h3 class="text-white font-semibold mb-2">Support</h3>
                    <p class="text-gray-400 text-sm">24/7 Available</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
