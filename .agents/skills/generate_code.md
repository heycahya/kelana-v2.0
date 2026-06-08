# Skill: Code Execution

## Objective
Translate high-level blueprints from `production_artifacts/issue.md` into clean, fully-implemented code inside `app_build/`.

## Rules
- Do not attempt to open shells, run servers, or configure git.
- **Incremental Updates**: Use IDE file editing tools (like `replace_file_content` or `multi_replace_file_content`) to edit specific code blocks. DO NOT rewrite or replace full file blocks if the file already exists and only needs minor edits to preserve tokens.
- Adapt entirely to the tech stack approved in the planning document. No placeholders (`// code here`).
- **State Tracking**: After coding is complete, you MUST append a brief summary of what was built to `production_artifacts/STATE.md` to preserve long-term memory for future sessions.