#!/bin/bash

set -eo

TMP_DIR="$GITHUB_WORKSPACE/zip"
mkdir "$TMP_DIR"

# If there's no .gitattributes file, write a default one into place
if [ ! -e "$GITHUB_WORKSPACE/.distignore" ]; then
	echo "ℹ︎ Creating .distignore file"

	cat > ".distignore" <<-EOL
	/.gitattributes
	/.gitignore
	/.github
	/README.md
	/.editorconfig
	/composer.json
	/index.php
	EOL
fi;

echo "➤ Copying files to $TMP_DIR"

# This will exclude everything in the .distignore file
rsync -rc --exclude-from="$GITHUB_WORKSPACE/.distignore" "$GITHUB_WORKSPACE/" "$TMP_DIR/" --delete

# Remove the release-please marker lines
sed -i '/x-release-please-start-version/d' "$TMP_DIR/readme.txt"
sed -i '/x-release-please-end/d' "$TMP_DIR/readme.txt"

echo "✅ Removed release-please marker lines from readme.txt for .org distribution"
