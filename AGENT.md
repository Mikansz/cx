# Agent Guidelines for ftydtsr Project

## Build/Test/Lint Commands
- Run tests: `composer test`
- Run single test: `php artisan test --filter=TestName`
- Run development server: `composer dev`
- Code linting: `./vendor/bin/pint`
- Clear cache: `php artisan config:clear`
- Migrate database: `php artisan migrate`

## Code Style Guidelines
- PHP version: 8.2+
- Indent: 4 spaces (2 spaces for YAML files)
- Line endings: LF
- Laravel Pint is used for code style enforcement
- PSR-4 autoloading standard
- File encoding: UTF-8
- Always add trailing newline
- Trim trailing whitespace (except in .md files)

## Naming Conventions
- Controllers: PascalCase, singular, suffix with Controller
- Models: PascalCase, singular
- Migrations: snake_case, prefixed with timestamp
- Database tables: snake_case, plural
- Files: Follows Laravel conventions