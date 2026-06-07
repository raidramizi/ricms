<x-app-layout>
    <x-slot name="title">
    Claim Application
</x-slot>

    <!-- HEADER -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Claim Application') }}
        </h2>
    </x-slot>

    <!-- STYLE -->
    <style>
        /* Background image for whole page */
        body {
            background: url("{{ asset('images/background3.jpg') }}") no-repeat center center fixed;
            background-size: cover;
        }

        /* Overlay to improve readability */
        .page-overlay {
            min-height: 100vh;
            background: rgba(255, 255, 255, 0.85);
            padding-bottom: 40px;
        }

        /* Reusable animated link card */
        .claim-link {
            display: block;
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            transition: all 0.25s ease;
            position: relative;
            background: white;
        }

        /* Hover effect */
        .claim-link:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        /* Click effect */
        .claim-link:active {
            transform: scale(0.96);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
        }

        /* Soft hover glow */
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
    </style>

    <!-- PAGE CONTENT -->
    <div class="page-overlay">

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

                <!-- Welcome Box -->
                <div class="bg-white shadow-sm rounded-xl p-3 mb-5 text-center">
                    <p class="text-gray-500 mt-1">
                        Click an option below to start your claim application
                    </p>
                </div></br>

                <!-- Publication Claims -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h2 class="text-xl font-bold text-red-600 mb-4">
                        Publication Claims
                    </h2>

                    <div class="space-y-3">
                        <a href="{{ route('publication.funding') }}"
                           class="claim-link hover:bg-blue-50 hover:border-blue-300">
                            Publication Funding
                        </a>

                        <a href="{{ route('publication.reward') }}"
                           class="claim-link hover:bg-blue-50 hover:border-blue-300">
                            Publication Reward
                        </a>
                    </div>
                </div>

                <!-- Grant Claims -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-green-600 mb-4">
                        Grant Claims
                    </h2>

                    <div class="space-y-3">

                        <a href="{{ route('grant.general') }}"
                           class="claim-link hover:bg-green-50 hover:border-green-300">
                            General
                        </a>

                        <a href="{{ route('grant.purchase') }}"
                           class="claim-link hover:bg-green-50 hover:border-green-300">
                            Purchase
                        </a>
                        <a href="{{ route('grant.virement') }}"
                           class="claim-link hover:bg-green-50 hover:border-green-300">
                            Virement
                        </a>

                        <a href="{{ route('grant.graduate') }}"
                           class="claim-link hover:bg-green-50 hover:border-green-300">
                            GRA / RA
                        </a>

                    </div>
                </div>

            </div>
        </div>

    </div>

</x-app-layout>
