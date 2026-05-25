#!/usr/bin/env bash
# Create a test PR using git + gh (GitHub CLI). Requires gh auth and a remote named 'origin'.
# Usage: ./scripts/create_test_pr.sh
set -euo pipefail
BRANCH=ci/test-pricing-codeowners
TARGET_BRANCH=develop

git checkout -b "$BRANCH"
git add app/Domain/Pricing/PR_TEST.md
git commit -m "chore(ci): test CODEOWNERS and Pricing Domain Quality checks" || true
git push -u origin "$BRANCH"

if command -v gh >/dev/null 2>&1; then
  gh pr create --base "$TARGET_BRANCH" --head "$BRANCH" --title "ci: test pricing CODEOWNERS and pricing quality checks" --body "This PR verifies CODEOWNERS, Pricing Domain Quality workflow (pint, phpstan, tests), and branch protections."
else
  echo "gh CLI not found — pushed branch $BRANCH. Create PR manually on GitHub." 
fi
