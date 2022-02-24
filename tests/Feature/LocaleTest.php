<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    /** @test */
    public function set_locale_from_header()
    {
        $this->withHeaders(['Accept-Language' => 'en'])
            ->postJson('/api/login');

        $this->assertEquals('en', $this->app->getLocale());
    }
}
