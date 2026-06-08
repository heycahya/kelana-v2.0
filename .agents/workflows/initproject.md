---
description: Guide the user to initialize a repository manually to prevent network overhead
---

When the user triggers `/init-project <project_name>`, provide them with a sequential, clean block of terminal commands to establish their environment safely.

### Instructions to Output:
1. Instruct the user to run these commands in their absolute workspace terminal:
   ```bash
   git init
   gh repo create <project_name> --public
   git remote add origin https://github.com/heycahya/<project_name>.git
   ```

2. Guide them to prepare the folders `app_build/` and `production_artifacts/`.
3. Tell them to call `/startcycle` with their app idea to initiate the Planning Phase.