<x-app-layout>
<x-slot name="title">
    Grant Conference
</x-slot>

    <!-- HEADER -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Conference Grant Application') }}
        </h2>
    </x-slot>

    <!-- STYLE -->
    <style>
        /* Background image */
        body {
            background: url("{{ asset('images/background4.jpg') }}") no-repeat center center fixed;
            background-size: cover;
        }

        /* Overlay for readability */
        .page-overlay {
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.85);
            padding-bottom: 40px;
        }

        /* Reusable card link */
        .claim-link {
            display: block;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.25s ease;
            position: relative;
            background: white;
        }

        /* Hover */
        .claim-link:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        /* Click */
        .claim-link:active {
            transform: scale(0.96);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        /* Soft hover overlay */
        .claim-link::after {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 12px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .claim-link:hover::after {
            opacity: 1;
            background: rgba(0, 0, 0, 0.02);
        }

        /* Fix spacing inside link text */
        .claim-title {
            font-weight: 600;
            margin-bottom: 4px;
        }

        .claim-desc {
            font-size: 0.875rem;
            color: #4b5563;
        }
    </style>

    <!-- PAGE CONTENT -->
    <div class="page-overlay">

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- Welcome -->
                <div class="bg-white shadow-sm rounded-xl p-3 mb-5 text-center">
                    <p class="text-gray-500 mt-1">
                        Click an option below to start your conference grant application
                    </p>
                </div></br>

                <!-- Conference Options -->
                <div class="bg-white rounded-xl shadow-md p-6">

                    <h2 class="text-xl font-bold text-blue-600 mb-4">
                        Conference Grant Types
                    </h2>

                    <div class="space-y-3">

                        <!-- Local -->
                        <a href="{{ url('/conference/local') }}"
                           class="claim-link hover:bg-blue-50 hover:border-blue-300">

                            <div class="claim-title">
                                Local Conference Grant
                            </div>

                            <div class="claim-desc">
                                Apply for funding to attend conferences within Malaysia.
                            </div>

                        </a>

                        <!-- Overseas -->
                        <a href="{{ url('/conference/overseas') }}"
                           class="claim-link hover:bg-blue-50 hover:border-blue-300">

                            <div class="claim-title">
                                Overseas Conference Grant
                            </div>

                            <div class="claim-desc">
                                Apply for funding to attend international conferences.
                            </div>

                        </a>

                    </div>

                </div>

            </div>
        </div>

    </div>

</x-app-layout>
