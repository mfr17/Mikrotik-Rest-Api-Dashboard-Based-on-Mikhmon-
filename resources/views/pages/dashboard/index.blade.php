<x-app-layout>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="grid grid-cols-4 gap-4 mb-6 bg-white p-4 rounded-lg shadow-sm"> <!-- Grid container for inline cards -->
        <x-card type="text" icon="heroicon-o-server" bgColor="bg-blue-200" title="Routerboard" :desc="[
            'Model: ' . $routerboard['model'],
            'Boardname: ' . $resource['board-name'],
            'Version: ' . $resource['version'],
        ]" />
        <div id="system">

        </div>
        <x-card type="text" icon="heroicon-o-chip" bgColor="bg-red-200" title="CPU" :desc="[
            'Architecture: ' . $resource['architecture-name'],
            'CPU Type: ' . $resource['cpu'],
            'CPU Count: ' . $resource['cpu-count'],
        ]" />
        <x-card type="text" icon="heroicon-o-database" bgColor="bg-yellow-200" title="Memory" :desc="['Free HDD: ' . $free_hdd_formatted, 'Free Memory: ' . $free_memory_formatted]" />
    </div>
    <div class="grid grid-cols-4 gap-4 mb-6"> <!-- Grid container for inline cards -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Hotspot</h2>
            <div class="grid grid-cols-2 gap-4">
                <!-- Grid container for inline cards -->
                <x-card type="stats" icon="heroicon-o-user" bgColor="bg-green-200" title="{{ $hs_active }} Item"
                    desc="User Active" />
                <x-card type="stats" icon="heroicon-o-wifi" bgColor="bg-yellow-200" title="{{ $hs_host }} Item"
                    desc="User Connected" />
                <x-card type="stats" icon="heroicon-o-ticket" bgColor="bg-purple-200" title="{{ $hs_user }} Item"
                    desc="Voucher" />
                <x-card type="stats" icon="heroicon-o-users" bgColor="bg-red-200" title="{{ $hs_profile }} Item"
                    desc="Voucher Profile" />
            </div>
        </div>
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">PPPoE</h2>
            <div class="grid grid-cols-2 gap-4">
                <!-- Grid container for inline cards -->
                <x-card type="stats" icon="heroicon-o-link" bgColor="bg-teal-200" textColor="text-teal-800"
                    title="{{ $ppp_active }} Item" desc="PPPoE Active" />
                <x-card type="stats" icon="heroicon-o-users" bgColor="bg-orange-200" textColor="text-orange-800"
                    title="{{ $ppp_secret }} Item" desc="PPPoE Secret" />
                <x-card type="stats" icon="heroicon-o-user-group" bgColor="bg-indigo-200" textColor="text-indigo-800"
                    title="{{ $ppp_profile }} Item" desc="PPPoE Profile" />
                <x-card type="stats" icon="heroicon-o-server" bgColor="bg-pink-200" textColor="text-pink-800"
                    title="{{ $ppp_server }} Item" desc="PPPoE Server" />
            </div>
        </div>
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Create</h2>
            <div class="grid grid-cols-2 gap-4">
                <a href="#" target="_blank">
                    <x-card type="stats" icon="heroicon-o-user-add" bgColor="bg-cyan-200" textColor="text-cyan-800"
                        title="Add" desc="Create Voucher" />
                </a>
                <a href="#" target="_blank">
                    <x-card type="stats" icon="heroicon-o-ticket" bgColor="bg-lime-200" textColor="text-lime-800"
                        title="Generate" desc="Generate Voucher" />
                </a>
                <a href="#" target="_blank">
                    <x-card type="stats" icon="heroicon-o-user-add" bgColor="bg-sky-200" textColor="text-sky-800"
                        title="Add" desc="PPPoE User" />
                </a>
                <a href="#" target="_blank">
                    <x-card type="stats" icon="heroicon-o-user-add" bgColor="bg-fuchsia-200"
                        textColor="text-fuchsia-800" title="Add " desc="PPPoE Profile" />
                </a>
            </div>
        </div>
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Income</h2>
            <div class="grid gap-4">
                <x-card type="text" icon="heroicon-o-currency-dollar" title="Today" :desc="[$vcr_today . ' Voucher', $today]" />
                <x-card type="text" icon="heroicon-o-currency-dollar" title="Monthly" :desc="[$vcr_monthly . ' Voucher', $monthly]" />
            </div>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4 mb-6">
        <!-- Traffic Interface section -->
        <div class="col-span-2 mb-6 bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Traffic Interface</h2>
            <!-- Content for Traffic Interface -->
            <div id="traffic-chart-container">
                <x-form.label for="interface-select" :value="__('Select Interface')" />
                <x-form.select id="interface-select" :options="$interface" />
                <div id="traffic-chart"></div>
            </div>
        </div>

        <!-- Hotspot Logs section -->
        <div class="col-span-1 mb-6 bg-white p-4 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Hotspot Logs</h2>
            <div class="overflow-x-auto h-96 bg-white rounded-lg shadow-sm mb-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Table header -->
                    <thead class="sticky top-0 bg-gray-100">
                        <tr>
                            <th scope="col"
                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time
                            </th>
                            <th scope="col"
                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col"
                                class="px-2 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Message
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body with scrollable content -->
                    <tbody class="divide-y divide-gray-200 overflow-y-auto max-h-80">
                        @foreach ($log_messages as $log)
                            <tr>
                                <td class="px-2 py-4 whitespace text-sm font-medium text-gray-900">
                                    {{ $log['time'] }}
                                </td>
                                <td class="px-2 py-4 whitespace text-sm text-gray-500">
                                    {{ $log['user'] }}
                                </td>
                                <td class="px-2 py-4 whitespace text-sm text-gray-500">
                                    {{ $log['message'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function getCurrentDateTimeInTimezone(timezone) {
            var date = new Date(); // Get current local time
            var formatter = new Intl.DateTimeFormat('en-US', {
                timeZone: timezone,
                hour12: false, // 24-hour format
                year: 'numeric',
                month: 'numeric',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            });

            // Format the date and time, then return the timestamp in milliseconds
            return Date.parse(formatter.format(date));
        }

        var gmt = @json($clock['time-zone-name']);
        $(document).ready(function() {
            // Function to fetch real-time data and update the card
            function fetchRealtimeData() {
                $.ajax({
                    url: '/realtime-data', // Adjust URL as per your route configuration
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Assuming response contains 'uptime_formatted' and 'cpu_load'
                        var uptimeFormatted = response.resource.uptime_formatted;
                        var cpuLoad = response.resource['cpu-load'];
                        var date = response.clock.date;
                        var time = response.clock.time;

                        // Update the x-card component using JavaScript
                        $('#system').html(`
                        <x-card type="text" icon="heroicon-o-desktop-computer" bgColor="bg-green-200" title="System"
                            :desc="[
                                'CPU Load: ${cpuLoad}%' , // Assuming cpuLoad is the CPU load data
                                'Uptime: ${uptimeFormatted}', // Assuming uptimeFormatted is the uptime data
                                'Date: ${date} ${time}'
                            ]" />
                    `);
                    },
                    error: function(xhr, status, error) {
                        console.error('Failed to fetch real-time data:', error);
                        // Handle error scenario as needed
                    }
                });
            }

            // Initial fetch
            fetchRealtimeData();

            // Fetch data every second (1000 milliseconds)
            setInterval(fetchRealtimeData, 1000);
        });
    </script>
    <script src="{{ asset('assets/js/traffic.js') }}"></script>

</x-app-layout>
