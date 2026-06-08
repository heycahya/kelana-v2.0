# Skill: Deployment Guidance

## Objective
Provide the user with explicit native setup and local server activation scripts based on the detected project stack.

## Instructions
1. Inspect the stack used in `app_build/`.
2. Output clear, copy-pasteable terminal commands for the user to run locally (e.g., `npm install && npm run dev` or `pip install -r requirements.txt && python app.py`).
3. Explicitly ask the user to paste the output logs back into the chat so the QA phase can verify that the environment runs without crashing.
4. Remind the user to perform repository synchronization manually once the local preview is verified.