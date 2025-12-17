# Releasing the Volley PHP SDK

This document describes the process for releasing a new version of the Volley PHP SDK.

## Prerequisites

1. Ensure all tests pass:
```bash
composer install
vendor/bin/phpunit
```

2. Update version numbers:
   - Update `Version::SDK_VERSION` in `src/Volley/Version.php`
   - Update version in `composer.json` (if needed)

3. Update CHANGELOG.md with the new version and changes

## Release Process

### 1. Create a Git Tag

```bash
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### 2. Create a GitHub Release

1. Go to the [GitHub repository](https://github.com/volleyhq/volley-php)
2. Click "Releases" → "Draft a new release"
3. Select the tag you just created
4. Add release notes (copy from CHANGELOG.md)
5. Publish the release

### 3. Publish to Packagist

The SDK is published to [Packagist](https://packagist.org/packages/volleyhq/volley-php).

**First-time setup** (if not already done):
1. Create a Packagist account
2. Submit the package URL: `https://github.com/volleyhq/volley-php`
3. Enable auto-update hook in Packagist

**For each release**:
- Packagist will automatically detect the new tag and update the package
- If auto-update is not enabled, manually update the package in Packagist

### 4. Verify Release

1. Verify the package is available on Packagist:
   ```bash
   composer show volleyhq/volley-php
   ```

2. Test installation:
   ```bash
   composer require volleyhq/volley-php
   ```

3. Verify the version:
   ```php
   use Volley\Version;
   echo Version::SDK_VERSION;
   ```

## Version Numbering

Follow [Semantic Versioning](https://semver.org/):
- **MAJOR** version for incompatible API changes
- **MINOR** version for new functionality in a backward-compatible manner
- **PATCH** version for backward-compatible bug fixes

## Post-Release Checklist

- [ ] All tests pass
- [ ] Version numbers updated
- [ ] CHANGELOG.md updated
- [ ] Git tag created and pushed
- [ ] GitHub release created
- [ ] Package available on Packagist
- [ ] Documentation updated (if needed)
- [ ] Announcement posted (if major release)

