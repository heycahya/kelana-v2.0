# Skill: High-Level Planning (issue.md)

## Objective
Turn raw user ideas into rigorous technical blueprints for junior programmers using a strict formatting standard.

## Instructions
1. **Analyze Context**: Read `.agents/DESIGN.md` to ensure your architecture aligns with the user's core design philosophy. Also read `production_artifacts/STATE.md` to understand current progress. Suggest the absolute best framework based on the user's goal (agnostic tech-stack).
2. **Draft Layout**: Detail the data structures, system boundaries, and step-by-step feature iterations.
3. **Save Artifact**: Write the final specification into `production_artifacts/issue.md`. You MUST use the following strict markdown structure:
   - **Tech Stack**: Exact frameworks and versions.
   - **File Tree**: Absolute paths for files to be created/modified in `app_build/`.
   - **Data Schema**: Explicit structure of data models.
   - **Acceptance Criteria**: Checklists for completion.
4. **Zero-Terminal Rule**: Print out a confirmation message and hand over the next steps to the user with the exact terminal command text to log the GitHub Issue.