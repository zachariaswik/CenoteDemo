<?php

it('displays the home page', function () {
    $page = visit('/');

    $page->assertSee('Welcome')
        ->assertNoJavaScriptErrors();
});
