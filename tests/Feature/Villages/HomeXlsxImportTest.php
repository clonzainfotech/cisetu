<?php

use App\Models\District;
use App\Models\Home;
use App\Models\State;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Village;
use App\Services\Imports\HomeImportService;
use App\Services\Imports\Parsers\HomeSpreadsheetParser;
use App\Services\Imports\Parsers\SpreadsheetAmountParser;
use App\Services\Imports\SpreadsheetReader;
use Illuminate\Http\UploadedFile;

test('spreadsheet amount parser reads gujarati numerals', function () {
    $parser = app(SpreadsheetAmountParser::class);

    expect($parser->parse('૭૦૪૫'))->toBe(7045.0)
        ->and($parser->parse('1,75,20.00'))->toBe(17520.0);
});

test('home spreadsheet parser reads gujarati xlsx sample', function () {
    $reader = app(SpreadsheetReader::class);
    $parser = app(HomeSpreadsheetParser::class);

    $rows = $reader->read(base_path('tests/fixtures/homes-gujarati-sample.xlsx'));
    $records = $parser->parse($rows);

    expect($records)->not->toBeEmpty()
        ->and(count($records))->toBeGreaterThan(300);

    $first = collect($records)->firstWhere('property_no', '2137');

    expect($first)->not->toBeNull()
        ->and($first['house_no'])->toBe('૧૮૭૪')
        ->and($first['owner'])->toContain('જગદીશ')
        ->and($first['total'])->toBe(2790.0)
        ->and($first['address'])->toContain('ઓરીયન વિલા');
});

test('tgp csv location label rows apply address to following properties', function () {
    $parser = app(HomeSpreadsheetParser::class);

    $rows = [
        ['પ્રોપટી નંબર', 'મકાન નંબર', 'માલિકનું નામ', 'કબજેદારનું નામ', 'કુલ'],
        ['સાંઇ વાટીકા,સેગવી રોડ', null, null, null, null],
        ['2005', '૧૭૫૧', 'ચંન્દ્રકાંત દતાત્રય નીકમ', 'પોતે', '7045'],
        ['2006', '૧૭૫૨', 'રંજનબેન હસમુખભાઇ પટેલ', 'પોતે', '9915'],
        ['સાંઇબાબા મંદિર પાસે', null, null, null, null],
        ['2090', '૧૮૨૯', 'દિપીકાબેન અજીતભાઇ ઉપાધ્યાય', 'પોતે', '3775'],
    ];

    $records = $parser->parse($rows);

    $blockA = collect($records)->firstWhere('property_no', '2005');
    $blockB = collect($records)->firstWhere('property_no', '2090');

    expect($blockA['address'])->toBe('સાંઇ વાટીકા,સેગવી રોડ')
        ->and($blockB['address'])->toBe('સાંઇબાબા મંદિર પાસે');
});

test('village admin can import homes from xlsx file', function () {
    $plan = SubscriptionPlan::factory()->create();
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Surat']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'kosmba',
        'name_en' => 'Kosamba',
        'is_active' => true,
        'subscription_plan_id' => $plan->id,
    ]);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);

    $file = new UploadedFile(
        base_path('tests/fixtures/homes-gujarati-sample.xlsx'),
        'homes-gujarati-sample.xlsx',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        null,
        true,
    );

    $baseDomain = parse_url(config('app.url'), PHP_URL_HOST);
    $importUrl = 'http://kosmba.'.$baseDomain.route('homes.import', [], false);

    $response = $this->actingAs($admin)
        ->post($importUrl, [
            'file' => $file,
            'use_ai' => false,
        ]);

    $response->assertRedirect();

    expect(Home::query()->where('village_id', $village->id)->count())->toBeGreaterThan(300)
        ->and(Home::query()->where('village_id', $village->id)->where('property_no', '2137')->exists())->toBeTrue()
        ->and(Home::query()->where('village_id', $village->id)->where('property_no', '2137')->value('user_id'))->toBe($admin->id);
});

test('home import service imports csv template format', function () {
    $plan = SubscriptionPlan::factory()->create();
    $state = State::query()->create(['code' => 'GJ', 'name_en' => 'Gujarat']);
    $district = District::query()->create(['state_id' => $state->id, 'name_en' => 'Valsad']);
    $village = Village::query()->create([
        'district_id' => $district->id,
        'subdomain' => 'demo',
        'name_en' => 'Demo',
        'is_active' => true,
        'subscription_plan_id' => $plan->id,
    ]);

    $csv = implode("\n", [
        'Property No,House No,Owner,Occupant,Address,Total Tax',
        '101,23,Rajesh Patel,Rajesh Patel,Near Temple,500',
    ]);

    $path = sys_get_temp_dir().'/homes-import-'.uniqid().'.csv';
    file_put_contents($path, $csv);

    $file = new UploadedFile($path, 'homes.csv', 'text/csv', null, true);

    $admin = User::factory()->create(['role' => 'village_admin', 'village_id' => $village->id]);

    $result = app(HomeImportService::class)->import($village, $file, $admin->id, useAi: false);

    $home = Home::query()->where('village_id', $village->id)->where('property_no', '101')->first();

    expect($result->imported)->toBe(1)
        ->and((float) $home->total)->toBe(500.0)
        ->and($home->user_id)->toBe($admin->id);
});
