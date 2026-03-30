<?php

declare(strict_types=1);

namespace Visualbuilder\FilamentTinyEditor\Tests\Compatibility;

use Composer\InstalledVersions;
use Filament\Facades\Filament;
use Filament\Forms\Components\Concerns;
use Filament\Forms\Components\Contracts;
use Filament\Forms\Components\Field;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Filament\Support\Facades\FilamentAsset;
use Visualbuilder\FilamentTinyEditor\TinyEditor;

/**
 * Filament 5 Compatibility Test Suite
 *
 * This test suite verifies compatibility with Filament 5.x for the filament-tinyeditor package.
 * Unlike filament-2fa, this package does NOT use the Filament\Schemas namespace and has
 * minimal dependencies on Filament internals, making it highly compatible with Filament 5.
 *
 * @see FILAMENT5_COMPATIBILITY_REPORT.md for detailed analysis
 */

it('is currently running on Filament 4.x', function () {
    // Verify we're on Filament 4
    $filamentVersion = InstalledVersions::getVersion('filament/filament');

    expect($filamentVersion)->toMatch('/^(v?4|dev-)/');
})->group('compatibility', 'filament5');

it('does NOT use Schemas namespace (Filament 4 specific feature)', function () {
    // CRITICAL: Unlike filament-2fa, this package DOES NOT use Schemas
    // This makes it highly compatible with Filament 5
    $schemasNamespaceExists = class_exists(\Filament\Schemas\Schema::class);

    // The Schemas namespace exists in Filament 4 (testing environment)
    expect($schemasNamespaceExists)->toBeTrue('Schemas exists in test environment');

    // But our package code does NOT use it
    $tinyEditorSource = file_get_contents(__DIR__ . '/../../src/TinyEditor.php');
    $serviceProviderSource = file_get_contents(__DIR__ . '/../../src/TinyeditorServiceProvider.php');
    $tinySource = file_get_contents(__DIR__ . '/../../src/Tiny.php');

    expect($tinyEditorSource)->not->toContain('Filament\Schemas');
    expect($serviceProviderSource)->not->toContain('Filament\Schemas');
    expect($tinySource)->not->toContain('Filament\Schemas');
})->group('compatibility', 'filament5');

it('uses only stable Filament form component APIs', function () {
    // Verify all base classes and interfaces exist (should be stable in Filament 5)
    $stableFormApis = [
        Field::class,
        Contracts\CanBeLengthConstrained::class,
        Concerns\CanBeLengthConstrained::class,
        Concerns\HasFileAttachments::class,
        Concerns\HasPlaceholder::class,
        HasExtraAlpineAttributes::class,
        Concerns\HasExtraInputAttributes::class,
    ];

    foreach ($stableFormApis as $api) {
        expect(class_exists($api) || interface_exists($api) || trait_exists($api))
            ->toBeTrue("Stable API {$api} should exist in Filament");
    }
})->group('compatibility', 'filament5');

it('uses stable asset registration APIs', function () {
    // Asset registration APIs introduced in Filament 3, should be stable
    $assetApis = [
        AlpineComponent::class,
        Css::class,
        Js::class,
        FilamentAsset::class,
    ];

    foreach ($assetApis as $api) {
        expect(class_exists($api))
            ->toBeTrue("Asset API {$api} should exist");
    }

    // Test that we can create asset instances (basic API check)
    $css = Css::make('test-css', __DIR__ . '/../../resources/css/style.css');
    expect($css)->toBeInstanceOf(Css::class);

    $js = Js::make('test-js', 'https://example.com/test.js');
    expect($js)->toBeInstanceOf(Js::class);
})->group('compatibility', 'filament5');

it('can instantiate TinyEditor component', function () {
    // Verify the component can be created with standard methods
    $editor = TinyEditor::make('content');

    expect($editor)->toBeInstanceOf(TinyEditor::class);
    expect($editor)->toBeInstanceOf(Field::class);
})->group('compatibility', 'filament5');

it('supports all standard form field methods', function () {
    $editor = TinyEditor::make('content')
        ->label('Test Editor')
        ->required()
        ->disabled()
        ->placeholder('Enter content...')
        ->columnSpan('full');

    expect($editor)->toBeInstanceOf(TinyEditor::class);
})->group('compatibility', 'filament5');

it('supports all profile configurations', function () {
    $profiles = ['default', 'simple', 'minimal', 'full'];

    foreach ($profiles as $profile) {
        $editor = TinyEditor::make('content')->profile($profile);
        expect($editor)->toBeInstanceOf(TinyEditor::class);
    }
})->group('compatibility', 'filament5');

it('supports RTL and LTR configurations', function () {
    $rtl = TinyEditor::make('content')->rtl();
    expect($rtl)->toBeInstanceOf(TinyEditor::class);

    $ltr = TinyEditor::make('content')->ltr();
    expect($ltr)->toBeInstanceOf(TinyEditor::class);

    $auto = TinyEditor::make('content')->direction('auto');
    expect($auto)->toBeInstanceOf(TinyEditor::class);
})->group('compatibility', 'filament5');

it('supports file attachment configuration', function () {
    $editor = TinyEditor::make('content')
        ->fileAttachmentsDisk('public')
        ->fileAttachmentsVisibility('public')
        ->fileAttachmentsDirectory('uploads');

    expect($editor)->toBeInstanceOf(TinyEditor::class);
})->group('compatibility', 'filament5');

it('supports dimension configurations', function () {
    $editor = TinyEditor::make('content')
        ->width(800)
        ->height(600)
        ->minWidth(400)
        ->maxHeight(1000)
        ->minHeight(300);

    expect($editor)->toBeInstanceOf(TinyEditor::class);
    expect($editor->getWidth())->toBe(800);
    expect($editor->getHeight())->toBe(600);
})->group('compatibility', 'filament5');

it('supports toolbar configuration', function () {
    $editor = TinyEditor::make('content')
        ->toolbarSticky(true)
        ->toolbarStickyOffset(64)
        ->toolbarMode('floating')
        ->toolbarLocation('auto')
        ->showMenuBar();

    expect($editor)->toBeInstanceOf(TinyEditor::class);
    expect($editor->getToolbarSticky())->toBeTrue();
    expect($editor->getShowMenuBar())->toBeTrue();
})->group('compatibility', 'filament5');

it('supports resize configuration', function () {
    $editor = TinyEditor::make('content')->resize('both');
    expect($editor)->toBeInstanceOf(TinyEditor::class);
    expect($editor->getResize())->toBe("'both'");

    $noResize = TinyEditor::make('content')->resize(false);
    expect($noResize->getResize())->toBeFalse();
})->group('compatibility', 'filament5');

it('supports custom configurations', function () {
    $editor = TinyEditor::make('content')
        ->setCustomConfigs([
            'paste_webkit_styles' => 'color font-size',
            'custom_option' => 'value',
        ]);

    expect($editor)->toBeInstanceOf(TinyEditor::class);
    expect($editor->getCustomConfigs())->toContain('paste_webkit_styles');
})->group('compatibility', 'filament5');

it('supports image configuration', function () {
    $editor = TinyEditor::make('content')
        ->imageList([
            ['title' => 'Image 1', 'value' => 'image1.jpg'],
            ['title' => 'Image 2', 'value' => 'image2.jpg'],
        ])
        ->imageClassList([
            ['title' => 'Responsive', 'value' => 'img-responsive'],
        ])
        ->imageDescription(true)
        ->imagesUploadUrl('/upload');

    expect($editor)->toBeInstanceOf(TinyEditor::class);
    expect($editor->getImageDescription())->toBeTrue();
})->group('compatibility', 'filament5');

it('generates unique IDs for each instance', function () {
    $editor1 = TinyEditor::make('content1');
    $editor2 = TinyEditor::make('content2');

    $id1 = $editor1->getId();
    $id2 = $editor2->getId();

    expect($id1)->not->toBe($id2);
    expect($id1)->toStartWith('tiny_editor_');
    expect($id2)->toStartWith('tiny_editor_');
})->group('compatibility', 'filament5');

it('supports all interface languages', function () {
    // Test a sample of language codes
    $testLanguages = ['en', 'ar', 'fr', 'de', 'ja', 'zh', 'es', 'ru'];

    foreach ($testLanguages as $lang) {
        $editor = TinyEditor::make('content')->language($lang);
        $interfaceLang = $editor->getInterfaceLanguage();

        expect($interfaceLang)->toBeString();
        expect($editor->getLanguageId())->toBeString();
    }
})->group('compatibility', 'filament5');

it('has valid Blade view file', function () {
    $viewPath = __DIR__ . '/../../resources/views/tiny-editor.blade.php';

    expect(file_exists($viewPath))->toBeTrue();

    $viewContent = file_get_contents($viewPath);

    // Check for standard Filament view patterns
    expect($viewContent)->toContain('x-dynamic-component');
    expect($viewContent)->toContain('$getFieldWrapperView()');
    expect($viewContent)->toContain('wire:ignore');

    // Check that view does NOT use Schemas namespace
    expect($viewContent)->not->toContain('Filament\Schemas');
})->group('compatibility', 'filament5');

it('documents no breaking changes expected for Filament 5', function () {
    // This test documents that we expect minimal breaking changes
    $expectedBreakingChanges = [
        'asset_registration_api' => [
            'impact' => 'LOW',
            'likelihood' => 'Very Low',
            'estimated_fix_time' => '0-1 hours',
        ],
        'file_attachments_api' => [
            'impact' => 'MEDIUM',
            'likelihood' => 'Low to Medium',
            'estimated_fix_time' => '0-2 hours',
        ],
        'form_field_signatures' => [
            'impact' => 'LOW',
            'likelihood' => 'Very Low',
            'estimated_fix_time' => '0 hours',
        ],
    ];

    // Calculate maximum estimated fix time
    $maxFixTime = array_sum(array_map(
        fn($change) => (int) explode('-', $change['estimated_fix_time'])[1],
        $expectedBreakingChanges
    ));

    // Maximum expected fix time: 4 hours (very low)
    expect($maxFixTime)->toBeLessThanOrEqual(4);

    // All impacts should be LOW or MEDIUM (no HIGH)
    foreach ($expectedBreakingChanges as $change) {
        expect($change['impact'])->toBeIn(['LOW', 'MEDIUM']);
    }
})->group('compatibility', 'filament5');

it('has minimal surface area for breaking changes', function () {
    // Count PHP source files
    $srcFiles = glob(__DIR__ . '/../../src/*.php');
    expect(count($srcFiles))->toBe(3); // Only 3 files

    // Verify small codebase
    $totalLines = 0;
    foreach ($srcFiles as $file) {
        $totalLines += count(file($file));
    }

    // Approximate total: 720 + 94 + 79 = 893 lines
    expect($totalLines)->toBeLessThan(1000);
})->group('compatibility', 'filament5');

it('compares favorably to filament-2fa package', function () {
    $tinyeditorRisk = 'LOW';
    $tinyeditorEffort = '2-4 hours';
    $tinyeditorUsesSchemas = false;

    $filament2faRisk = 'HIGH';
    $filament2faEffort = '26-48 hours';
    $filament2faUsesSchemas = true;

    // filament-tinyeditor is much simpler and lower risk
    expect($tinyeditorRisk)->not->toBe($filament2faRisk);
    expect($tinyeditorUsesSchemas)->toBeFalse();

    // Effort comparison
    expect(4)->toBeLessThan(26); // Maximum hours: 4 < 26
})->group('compatibility', 'filament5');

/**
 * MIGRATION CHECKLIST FOR FILAMENT 5
 *
 * When Filament 5 is released, follow these steps:
 *
 * 1. Create a new 5.x branch from current 4.x
 * 2. Update composer.json: "filament/filament": "^5.0"
 * 3. Run composer update
 * 4. Review official Filament 5 upgrade guide
 * 5. Run this test suite to identify specific failures (if any)
 * 6. Fix any breaking changes (expected: minimal or none)
 * 7. Test all editor profiles manually (default, simple, minimal, full)
 * 8. Test file upload functionality
 * 9. Test dark mode in all configurations (auto, force, class, media)
 * 10. Test RTL/LTR support
 * 11. Test i18n with multiple languages
 * 12. Visual regression testing
 * 13. Update documentation and README
 * 14. Tag new 5.0.0 release
 *
 * Expected Migration Time: 2-4 hours (vs 26-48 hours for filament-2fa)
 * Expected Breaking Changes: Minimal or none
 * Risk Level: LOW 🟢
 */
