# EduardoLedoServerSyncBundle
- Simple bundle for syncing your project with multiple servers (dev, staging, prod) via rsync.
```
$ composer require eduardoledo/server-sync-bundle

```

### Step 2: Enable the bundle in AppKernel.php

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new EduardoLedo\ServerSyncBundle(),
    );
}
```
### Step 3: Configure the bundle

```yaml
# app/config/config.yml
eduardoledo_server_sync:
    view:
        view_response_listener: force
```
---
