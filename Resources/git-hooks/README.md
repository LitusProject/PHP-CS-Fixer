# CodeStyle Git Hooks

This directory contains git hooks that make using the php codestyle fixer easier.

## `pre-commit.sh`

The `pre-commit.sh` is designed to be called from within the pre-commit git hook.
If you don't have this git hook, simply go to `/path/to/repository/.git/hooks`
and copy or move the `pre-commit.sample` to `pre-commit`. Make sure the file is
executable.  
The default `pre-commit` hook contains a check for non-ASCII filenames or
trailing whitespace.

Add the following line to your `pre-commit` file:
```
vendor/litus/php-cs/Resources/git-hooks/pre-commit.sh
```
Make sure you add this _before any `exec` calls_! `exec` doesn't return if the
command it executes succeeds, so any lines after the `exec` call may or may
not be executed depending of the return status of the called command.

### Example git hook

```sh
#!/bin/sh
#
# An example hook script to verify what is about to be committed.
# Called by "git commit" with no arguments.  The hook should
# exit with non-zero status after issuing an appropriate message if
# it wants to stop the commit.
#
# To enable this hook, rename this file to "pre-commit".

if git rev-parse --verify HEAD >/dev/null 2>&1
then
	against=HEAD
else
	# Initial commit: diff against an empty tree object
	against=4b825dc642cb6eb9a060e54bf8d69288fbee4904
fi

# If you want to allow non-ASCII filenames set this variable to true.
allownonascii=$(git config --bool hooks.allownonascii)

# Redirect output to stderr.
exec 1>&2

# Cross platform projects tend to avoid non-ASCII filenames; prevent
# them from being added to the repository. We exploit the fact that the
# printable range starts at the space character and ends with tilde.
if [ "$allownonascii" != "true" ] &&
	# Note that the use of brackets around a tr range is ok here, (it's
	# even required, for portability to Solaris 10's /usr/bin/tr), since
	# the square bracket bytes happen to fall in the designated range.
	test $(git diff --cached --name-only --diff-filter=A -z $against |
	LC_ALL=C tr -d '[ -~]\0' | wc -c) != 0
then
	cat <<\EOF
Error: Attempt to add a non-ASCII file name.

This can cause problems if you want to work with people on other platforms.

To be portable it is advisable to rename the file.

If you know what you are doing you can disable this check using:

git config hooks.allownonascii true
EOF
	exit 1
fi

vendor/litus/php-cs/Resources/git-hooks/pre-commit.sh

# If there are whitespace errors, print the offending file names and fail.
exec git diff-index --check --cached $against --
```

### Committing

__IMPORTANT__: If you used the default pre-commit hook, commiting files containing
trailing whitespace will fail. MarkDown files _contain trailing whitespace [by design](http://daringfireball.net/projects/markdown/syntax#p)_
so they _will_ fail your commit.

To perform a commit without calling the pre-commit hook, commit with the `-n` switch:
```
git commit -n
```
