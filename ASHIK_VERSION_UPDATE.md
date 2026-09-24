# Ashik Version Update

This project now uses a local, self-managed version updater. It does not require a purchase code or any external update service.

The reusable package source is available at `packages/ashik/version-updater`.
For another Laravel project, copy or publish this package and run `composer dump-autoload` after registering it.

The package also includes an installer at `/ashik-install`.

## Creating an update ZIP

1. Create a folder for the update, for example `ashik-update-1.1.0/`.
2. Put the changed application files inside that folder.
3. Add an `update_note.json` file at the folder root:

```json
{
  "build_version": 2,
  "current_version": "1.1.0",
  "root_path": "ashik-update-1.1.0",
  "code_path": {
    "app": "directory",
    "database/migrations/2026_01_01_add_example.php": "file",
    "resources/views": "directory"
  }
}
```

4. Zip the folder and upload it from `ERP Super Admin > Ashik Version Update`.
5. Take a database and file backup before applying an update.

After the files are copied, the system runs migrations and records the new build version in the settings table.
