<?php

$dir = '/Users/yogirangoliya/Herd/cis/resources/views/portals/';
$files = glob($dir.'*.blade.php');

$replacements = [
    'ગ્રામ પંચાયત | Digital Property Tax Portal' => 'ગ્રામ પંચાયત {{ $village->name_local }} | Digital Property Tax Portal',
    'Official Digital Portal for Gram Panchayat' => 'Official Digital Portal for {{ $village->name_local }}',
    '<h1>{{ $village->name_local }}</h1>' => '<h1>ગ્રામ પંચાયત {{ $village->name_local }}</h1>',
    '{{ $village->name_local }} DigitalPay' => 'ગ્રામ પંચાયત {{ $village->name_local }} DigitalPay',
    '{{ $village->name_local }} કચેરી' => 'ગ્રામ પંચાયત {{ $village->name_local }} કચેરી',
    '919316781820' => '{{ $village->whatsapp_number }}',
    'GP Digital Portal' => '{{ $village->name_local }}',
    '{{ $village->name_local }}<br>' => 'ગ્રામ પંચાયત {{ $village->name_local }}<br>',
    'src="assets/images/logo.jpeg"' => 'src="{{ $village->logo ? Storage::url($village->logo) : asset(\'assets/images/logo.jpeg\') }}"',
    'onerror="this.style.display=\'none\'"' => 'onerror="this.src=\'{{ asset(\'assets/images/logo.jpeg\') }}\'"',
    '<div class="footer-brand-name">{{ $village->name_local }}</div>' => '<div class="footer-brand-name">{{ strtoupper($village->name_en) }}</div>',
    '<div class="footer-brand-tag">Digital Citizen Portal</div>' => '<div class="footer-brand-tag" style="color:var(--saffron); font-weight:900;">DIGITAL CITIZEN PORTAL</div>',
];

foreach ($files as $file) {
    if (basename($file) === 'classic.blade.php') {
        continue;
    }

    $content = file_get_contents($file);
    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }
    file_put_contents($file, $content);
}

echo 'Done updating '.(count($files) - 1)." templates.\n";
