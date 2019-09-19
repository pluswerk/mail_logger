case "$TYPO3_VERSION" in
 9.5.*) composer require typo3/minimal="$TYPO3_VERSION" --dev ;;
    *) composer require typo3/cms="$TYPO3_VERSION" --dev ;;
esac
composer install --no-progress --no-suggest -n
