---
description: The Golden Loop Workflow Orchestration (Agnostic Tech-Stack)
---

When the user initiates a cycle, orchestrate the development process strictly following these 5 golden phases. All terminal actions MUST be handed over to the User.

### PHASE 1: PLANNING (AI Pintar)
1. Act as `@architect-pm`. Read `.agents/DESIGN.md` (if it exists) for your core design philosophy and `production_artifacts/STATE.md` (if it exists) to grasp the long-term context.
2. Formulate a high-level step-by-step plan for a junior programmer. Define the database and architecture structure.
3. Save this plan directly to `production_artifacts/issue.md` using the strict template defined in `write_specs.md`.
4. Halt and instruct the User to submit this file as a GitHub Issue if they wish, or proceed to Phase 2.

### PHASE 2: ISSUE TICKETING (AI Pintar)
1. Provide the exact GitHub CLI (`gh`) terminal command text for the user to copy-paste to submit the issue manually:
   `gh issue create --title "Feature: [Project Name]" --body-file production_artifacts/issue.md`

### PHASE 3: EXECUTION & CODING (AI Murah)
1. Ask the User to create a feature branch manually via terminal: `git checkout -b feature/new-feature`.
2. Shift context to `@engineer-coder`. Read `production_artifacts/issue.md`.
3. Modify or create code files inside the `app_build/` directory using incremental file-editing tools (e.g., `replace_file_content`). DO NOT rewrite entire files unless completely necessary.
4. Update `production_artifacts/STATE.md` with a summary of the newly implemented features.
5. Instruct the User to run the local server/build commands and PASTE the terminal logs back into the chat.

### PHASE 4: REVIEW GATE (AI Pintar)
1. Shift context back to `@architect-pm`. Inspect the files generated inside `app_build/` AND analyze the terminal logs pasted by the User.
2. Check for code smells, redundancies, or security vulnerabilities.
3. Provide a structured review report in chat. If fixes are needed, instruct `@engineer-coder` to edit the files before final approval.
4. Once approved, instruct the User to commit, push, and create a Pull Request (PR) manually using `gh`.

### PHASE 5: DEBUGGING MODE (AI Pintar - If Error Occurs)
1. If the user reports a red error log, STOP all code modifications immediately.
2. Analyze the Root Cause of the issue. Explain the breakdown to the user.
3. Write a correction ticket inside `production_artifacts/bug_fix.md` and provide the command text for the user to log the bug issue manually.