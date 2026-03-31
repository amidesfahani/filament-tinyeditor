# Changelog

All notable changes to `filament-tinyeditor` will be documented in this file.

## [5.0.0] - 2026-03-31

### Added
- Filament 5.x compatibility
- Livewire 4.x support
- Pest 4.x test framework support
- PHPUnit 12.x support
- Laravel 13.x compatibility
- Comprehensive Filament 5 compatibility test suite
- Detailed compatibility report documentation

### Changed
- Updated `filament/filament` dependency from `^4.0` to `^5.0`
- Updated `pestphp/pest` from `^3.0` to `^4.0`
- Updated `pestphp/pest-plugin-laravel` from `^3.0` to `^4.0`
- Added `phpunit/phpunit` `^12.0` to dev dependencies

### Migration Notes
- **No source code changes required** - Package is fully compatible with Filament 5
- All existing Filament 4 code continues to work without modifications
- Upgrade is as simple as updating `composer.json` and running `composer update`
- No breaking changes in package API
- All form field methods remain unchanged

### Technical Details
- Package does NOT use `Filament\Schemas` namespace (Filament 4 specific)
- Uses only stable Filament APIs (Forms, Components, Support)
- No Livewire v4 breaking changes required
- Standard form field patterns remain fully compatible
- Asset registration API unchanged

## [4.x] - Previous Releases

See git history for details on 4.x releases.
