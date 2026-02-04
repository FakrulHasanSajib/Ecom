<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->title ?? 'সত্যের আলো প্রকাশন - এজেন্ট নিয়োগ' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Hind Siliguri', sans-serif; background-color: #fdfbf7; }
        .gradient-text { background: linear-gradient(to right, #e11d48, #be123c); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="text-gray-800">

    {{-- Navigation --}}
    <nav class="bg-gray-100 py-3 shadow-sm">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="text-2xl text-blue-800 font-bold">🏠 📖</div>
                <h1 class="text-xl font-bold text-blue-900 hidden sm:block">সত্যের আলো প্রকাশন</h1>
            </div>
            <div class="flex gap-2">
                {{-- Dynamic Links --}}
                <a href="{{ $page->login_url ?? url('/login') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm font-semibold transition">লগইন করুন</a>
                <a href="{{ $page->register_url ?? url('/register') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded text-sm font-semibold transition">একাউন্ট খুলুন</a>
            </div>
        </div>
    </nav>

    {{-- Header --}}
    <header class="bg-blue-700 text-white text-center py-6 px-4">
        <h2 class="text-xl md:text-2xl font-semibold leading-relaxed">
            সারা বাংলাদেশে “সত্যের আলো প্রকাশন” এর জন্য লাইব্রেরিয়ান সহ <br>
            থানা ও জেলা ভিত্তিক উদ্যোক্তা বা এজেন্ট নিয়োগ চলছে
        </h2>
    </header>

    <main class="container mx-auto px-4 pb-12">

        {{-- Title Badge --}}
        <div class="flex justify-center -mt-5 mb-8 relative z-10">
            <span class="bg-red-600 text-white text-xl md:text-2xl font-bold px-8 py-2 rounded-full shadow-lg border-4 border-white">
                {{ $page->title ?? 'উদ্যোক্তা বা এজেন্ট নিয়োগ' }}
            </span>
        </div>

        {{-- Dynamic Image Gallery (With Fallback) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 max-w-4xl mx-auto">
            <img src="{{ !empty($page->image_one) ? asset($page->image_one) : 'https://placehold.co/400x250/333/FFF?text=Library+Image' }}" alt="Library" class="rounded-lg shadow border-2 border-white w-full object-cover h-48">
            <img src="{{ !empty($page->image_two) ? asset($page->image_two) : 'https://placehold.co/400x250/333/FFF?text=Reading+Quran' }}" alt="Reading" class="rounded-lg shadow border-2 border-white w-full object-cover h-48">
            <img src="{{ !empty($page->image_three) ? asset($page->image_three) : 'https://placehold.co/400x250/333/FFF?text=Delivery+Service' }}" alt="Delivery" class="rounded-lg shadow border-2 border-white w-full object-cover h-48">
        </div>

        {{-- Description from Admin Panel (Optional - If you want to add extra text) --}}
        @if(!empty($page->description))
        <div class="max-w-5xl mx-auto mb-8 prose prose-lg text-center">
            {!! $page->description !!}
        </div>
        @endif

        <div class="text-center mb-8">
            <span class="bg-red-600 text-white text-lg font-bold px-6 py-1.5 rounded-full shadow inline-block">
                আমাদের সাথে কাজ করার ৪টি ধাপ
            </span>
        </div>

        {{-- Program Steps (Static Design Preserved) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto mb-16">
            <div class="bg-stone-700 text-white rounded-2xl overflow-hidden shadow-lg border border-gray-600">
                <div class="bg-green-600 py-2 text-center font-bold text-lg">কোরআন খাদেম প্রোগ্রাম</div>
                <div class="p-6 text-center text-gray-200 text-sm md:text-base">
                    আপনি যদি কোরআনের খাদেম হয়ে আমাদের সাথে কাজ করতে চান! মক্তব বা মসজিদ ভিত্তিক তাহলে এক্সট্রা ইনকামের একটা বিশেষ মাধ্যম হতে পারে আপনার জন্য আমাদের এই কোরআন খাদেম প্রোগ্রাম।
                </div>
            </div>
            <div class="bg-stone-700 text-white rounded-2xl overflow-hidden shadow-lg border border-gray-600">
                <div class="bg-green-600 py-2 text-center font-bold text-lg">লাইব্রেরিয়ান প্রোগ্রাম</div>
                <div class="p-6 text-center text-gray-200 text-sm md:text-base">
                    আপনি যদি বইয়ের দোকান বা ইসলামী শপ থাকে তাহলে আমাদের সাথে যুক্ত হয়ে আপনার শপে আমাদের প্রোডাক্টস সেল করে লাভবান হতে পারবেন ইন শা আল্লাহ।
                </div>
            </div>
            <div class="bg-stone-700 text-white rounded-2xl overflow-hidden shadow-lg border border-gray-600">
                <div class="bg-green-600 py-2 text-center font-bold text-lg">এজেন্ট প্রোগ্রাম</div>
                <div class="p-6 text-center text-gray-200 text-sm md:text-base">
                    আপনার এলাকা বা থানার এজেন্ট হয়ে আমাদের সাথে যুক্ত হয়ে আপনার একটি সুন্দর ক্যারিয়ার গড়তে পারেন। সর্বোচ্চ সাপোর্ট দিয়ে আনলিমিটেড ইনকাম করার সুযোগ থাকছে ইন-শা-আল্লাহ।
                </div>
            </div>
            <div class="bg-stone-700 text-white rounded-2xl overflow-hidden shadow-lg border border-gray-600">
                <div class="bg-green-600 py-2 text-center font-bold text-lg">কর্পোরেট গিফট</div>
                <div class="p-6 text-center text-gray-200 text-sm md:text-base">
                    আপনি যদি আপনার প্রিয় মানুষদের জন্য আল কোরআন হাদিয়া করতে চান বা আপনার প্রতিষ্ঠানের জন্য আল কোরআন হাদিয়া করতে চান তাদের জন্য আমাদের এই কর্পোরেট পাইকারী অফার।
                </div>
            </div>
        </div>

        {{-- Requirements Section (Static Design Preserved) --}}
        <div class="bg-blue-50/50 rounded-xl p-4 md:p-8 mb-16 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/arabesque.png')]"></div>
            
            <div class="flex flex-col md:flex-row items-center justify-center gap-8 relative z-10">
                <div class="w-full md:w-1/3">
                    {{-- You can make this image dynamic too if you want, otherwise keep placeholder --}}
                    <img src="https://placehold.co/400x500/eee/333?text=Man+With+Quran" alt="Man holding Quran" class="rounded-lg shadow-xl mx-auto">
                </div>
                
                <div class="w-full md:w-1/2">
                    <div class="bg-gradient-to-br from-red-600 to-red-800 text-white p-6 md:p-10 rounded-3xl shadow-2xl transform md:-skew-x-6">
                        <div class="md:skew-x-6"> 
                            <h3 class="bg-yellow-400 text-black font-bold text-xl inline-block px-4 py-1 mb-2 transform -rotate-1 shadow">উদ্যোক্তা বা এজেন্ট নিয়োগ</h3>
                            <h4 class="text-2xl font-bold mb-6 border-b-2 border-white/30 pb-2 inline-block">যা থাকতে হবে ...</h4>
                            
                            <ul class="space-y-2 text-sm md:text-base font-medium">
                                <li>১. কাজ করার মত পর্যাপ্ত সময়।</li>
                                <li>২. বাইক/সাইকেল।</li>
                                <li>৩. মানুষকে বুঝানোর মত ক্ষমতা।</li>
                                <li>৪. স্মার্ট ফোন থাকতে হবে।</li>
                                <li>৫. বয়স ২২ থেকে ৩৫ বছর।</li>
                                <li>৬. মুসলিম হতে হবে।</li>
                                <li>৭. শিক্ষাগত যোগ্যতা: এইচ.এস.সি (সমমান)।</li>
                                <li class="font-bold bg-white/20 p-1 rounded">৮. মাদ্রাসা পড়ুয়াদের অগ্রাধিকার দেয়া হবে।</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Video Section (Dynamic IDs) --}}
        <div class="text-center mb-10">
            <span class="bg-yellow-300 text-black text-xl font-bold px-8 py-2 shadow border-b-4 border-yellow-500 inline-block mb-8">
                কিভাবে কাজ করবেন ভিডিও
            </span>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                {{-- Agent Video --}}
                <div>
                    <div class="aspect-video bg-black rounded-lg overflow-hidden shadow-lg mb-3">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $page->agent_video_id ?? 'dQw4w9WgXcQ' }}" title="এজেন্ট ভিডিও" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="bg-purple-700 text-white py-2 rounded-full text-sm font-semibold">এজেন্ট হয়ে কাজ করার ধাপ</div>
                </div>
                {{-- Khadem Video --}}
                <div>
                    <div class="aspect-video bg-black rounded-lg overflow-hidden shadow-lg mb-3">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $page->khadem_video_id ?? 'dQw4w9WgXcQ' }}" title="খাদেম ভিডিও" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="bg-purple-700 text-white py-2 rounded-full text-sm font-semibold">খাদেম হয়ে কাজ করার ধাপ</div>
                </div>
            </div>
        </div>

        {{-- Footer CTA --}}
        <div class="text-center mt-16 max-w-3xl mx-auto">
             <div class="flex justify-center gap-4 mb-8">
                <a href="{{ $page->login_url ?? url('/login') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded text-lg font-bold shadow-lg transition">লগইন করুন</a>
                <a href="{{ $page->register_url ?? url('/register') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded text-lg font-bold shadow-lg transition">একাউন্ট খুলুন</a>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-center gap-6 mb-8">
                <img src="https://placehold.co/200x200/22c55e/ffffff?text=BD+Map" alt="Bangladesh Map" class="w-32 opacity-80">
                <p class="text-lg font-semibold text-center md:text-left">
                    হেরার আলোয় আলোকিত হোক আপনার জীবন। <br>
                    বাংলাদেশের প্রতিটি প্রান্তরে আপনার মাধ্যমে <br>
                    আমরা ছড়িয়ে দিতে চাই আল কোরআনের আলো।
                </p>
            </div>
        </div>

    </main>

    {{-- Footer --}}
    <footer class="bg-blue-900 text-white py-8 border-t-4 border-green-500">
        <div class="container mx-auto px-4 text-center space-y-2">
            <p class="text-lg">মেইল: sotteraloprokashon@gmail.com</p>
            <p>ফোন: ০১৪০৪৩০১২৫০ , হটলাইন: ০৯৬৩৮৭৪৬৪৭৪</p>
            <p class="text-gray-300 text-sm">হেড অফিস: হাউজ-৬৩, রোড-৮, ব্লক-জি, আফতাবনগর, ঢাকা-১২০০</p>
        </div>
    </footer>

</body>
</html>