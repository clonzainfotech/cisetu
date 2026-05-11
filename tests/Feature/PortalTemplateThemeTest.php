<?php

use App\Models\Village;

test('portal templates render classic content (theme only)', function () {
    $templates = [
        'classic',
        'compact',
        'corporate',
        'dark',
        'eco',
        'glass',
        'gradient',
        'minimal',
        'modern',
        'royal',
        'simple',
        'vibrant',
    ];

    foreach ($templates as $template) {
        $village = Village::factory()->create([
            'subdomain' => 'test-'.$template,
            'portal_template' => $template,
        ]);

        $response = $this->get('/?village='.$village->subdomain);

        $response->assertOk();
        $response->assertSee('Official Tax Payment Portal', false);
        $response->assertSee('How to Pay', false);
        $response->assertSee('Digital Governance', false);
    }
});
