case "$TYPO3_VERSION" in
  *) composer require typo3/minimal="$TYPO3_VERSION" --dev ;;
esac
composer install --no-progress --no-suggest -n
