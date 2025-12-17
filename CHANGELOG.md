# Changelog

All notable changes to the Volley PHP SDK will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-01-16

### Added
- Initial release of the Volley PHP SDK
- Full API coverage for all Volley endpoints:
  - Organizations (list, get, create)
  - Projects (list, create, update, delete)
  - Sources (list, create, get, update, delete)
  - Destinations (list, create, get, update, delete)
  - Connections (create, get, update, delete)
  - Events (list, get, replay)
  - Delivery Attempts (list with filters)
  - Webhooks (send)
- Type-safe models for all API request/response structures
- Custom exception class (`VolleyException`) with HTTP status code tracking
- Organization context management
- Comprehensive unit tests with mocked HTTP responses
- Integration tests for real API calls
- Full documentation (README, TESTING, RELEASING)
- Example code demonstrating SDK usage
- PHP 7.4+ and PHP 8.0+ support
- Composer package ready for Packagist

[1.0.0]: https://github.com/volleyhq/volley-php/releases/tag/v1.0.0

