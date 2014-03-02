#!/bin/bash
#
# Litus/CodeStyle creates some helpers for php-cs-fixer project by Fabien Potencier.
# Built with all the love in the world by @bgotink, licensed under the GPL v3.
#
# @author Bram Gotink <bram.gotink@litus.cc>
#
# @license GPL v3 <https://gnu.org/licenses/gpl.html>

# This script can be called from within the pre-commit git hook by adding this
#   vendor/litus/php-cs/Resources/git-hooks/pre-commit.sh
# to your pre-commit hook file. Note that this line assumes you haven't used
# cd yet (that is PWD == <project_root>)
#
# This script never returns a non-zero exit code

if git rev-parse --verify HEAD >/dev/null 2>&1
then
	against=HEAD
else
	# Initial commit: diff against an empty tree object
	against=4b825dc642cb6eb9a060e54bf8d69288fbee4904
fi

splitgrep() {
    (tee /dev/fd/2 | grep -v "$@") 3>&1 1>&2- 2>&3- | grep "$@"
}

if git diff --cached --quiet --exit-code; then
    # no staged changes
    exit 0
fi

# store changed files in .php_cs-files
git diff --cached --name-only --diff-filter=ACMR $against > .php_cs-files

# run php-cs-fixer
vendor/bin/php-cs-fixer fix . |
	splitgrep -E -e '^\s+[0-9]+\)' |
	cut -d')' -f2 | tr -d ' ' |
	xargs git add

# remove changed files file
rm -f .php_cs-files
