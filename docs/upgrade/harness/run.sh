#!/usr/bin/env bash
# Runs every snapshot test of this folder against one copy of the project.
#   bash run.sh <php binary> <project dir> <snapshot dir> [Test ...]
# The tests are copied into <project dir>/tests/Feature first (with
# SmokeRoutesTest, which picks the accounts they all log in with), and the
# snapshots of each one are written to <snapshot dir>. The project's
# resources/lang files are put back afterwards: the t() helper appends every
# key it does not know to them while the pages render.
set -u
php="$1"; project="$2"; snap="$3"; shift 3
here="$(cd "$(dirname "$0")" && pwd)"
tests=("$@")
if [ ${#tests[@]} -eq 0 ]; then
    tests=(AuthFlow PasswordReset LogViewerAccess Forms Import StudentTests Export ParamPages Response)
fi
mkdir -p "$snap"
cp "$here"/*SnapshotTest.php "$here"/SmokeRoutesTest.php "$project/tests/Feature/"
cd "$project" || exit 1
lang_backup="$(mktemp -d)"
cp -a resources/lang/. "$lang_backup/"
# exports leave their temporary files behind when the download is not sent
excel_before="$(ls storage/framework/laravel-excel 2>/dev/null)"
for t in "${tests[@]}"; do
    start=$(date +%s)
    result=$(SNAPSHOT_DIR="$snap" DEBUGBAR_ENABLED=false "$php" -d memory_limit=-1 vendor/bin/phpunit "tests/Feature/${t}SnapshotTest.php" 2>"$snap/_${t}.stderr" | tail -1)
    echo "$t: $result ($(( $(date +%s) - start ))s)"
done
cp -a "$lang_backup/." resources/lang/
rm -rf "$lang_backup"
for f in $(ls storage/framework/laravel-excel 2>/dev/null); do
    echo "$excel_before" | grep -qx "$f" || rm -f "storage/framework/laravel-excel/$f"
done
