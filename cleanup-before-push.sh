#!/bin/bash
# Cleanup script to remove temporary/duplicate documentation before pushing to GitHub

echo "Cleaning up temporary and duplicate documentation files..."

# Remove duplicate/temporary documentation files
rm -f ALL_DONE.md
rm -f COMPLETE_PHP8_FIXES.md
rm -f FINAL_PHP8_FIXES.md
rm -f FINAL_SUMMARY.md
rm -f PHP8_COMPATIBILITY.md
rm -f PHP8_FIXES_COMPLETE.md
rm -f REFACTORING_SUMMARY.md
rm -f STATUS.md
rm -f TESTING_QUICK_REFERENCE.md
rm -f SUCCESS.txt

echo "✓ Removed temporary documentation files"

# Optionally remove test.sh if not needed
# rm -f test.sh

# Optionally remove docker files if not using Docker
# rm -f Dockerfile
# rm -f docker-compose.yml

echo ""
echo "Files kept:"
echo "  - README.md (main documentation)"
echo "  - QUICKSTART.md (quick start guide)"
echo "  - UPGRADE.md (PHP 8.1 upgrade guide)"
echo "  - SECURITY.md (security documentation)"
echo "  - OPENSTREETMAP.md (map feature docs)"
echo "  - GOOGLE_MAPS_SETUP.md (legacy reference)"
echo "  - TESTING.md (testing guide)"
echo ""
echo "Review remaining files with: git status"
echo "When ready, stage changes with: git add -A"
echo "Then commit with a descriptive message"
