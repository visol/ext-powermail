ext-powermail
=============

This is a fork of the TYPO3 extension "powermail", based on Powermail 2.1 for TYPO3 6.2.

This is a (hopefully) momentary fork to avoid problems with the Powermail release management. In the past, Powermail often included breaking changes in minor releases. Therefore this fork is maintained to have a stable Powermail version based on Powermail 2.1.

Semantic versioning is used: http://semver.org/

The initial version is 3.0.0-f.

Changes in Powermail are backported as needed by the maintainers of this fork.

Changes in 3.0.0-F
------------------

### Enable IRRE for form localization

see http://forge.typo3.org/issues/42862

In the original Powermail forms can only be translated field-by-field because the l10n configuration prevents the usage of IRRE. This is not usable for pages with a lot of forms and disturbs the field sorting. A new extension configuration is added to enable translating a whole form using IRRE and still using the configuration from the default language.
